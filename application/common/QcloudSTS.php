<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-08
 * Time: 18:14
 */

namespace app\common;


class QcloudSTS
{
    function _hex2bin($data) {
        $len = strlen($data);
        return pack("H" . $len, $data);
    }
    // obj 转 query string
    function json2str($obj, $notEncode = false) {
        ksort($obj);
        $arr = array();
        if(!is_array($obj)){
            return null;
        }
        foreach ($obj as $key => $val) {
            array_push($arr, $key . '=' . ($notEncode ? $val : rawurlencode($val)));
        }
        return join('&', $arr);
    }
    // 计算临时密钥用的签名
    function getSignature($opt, $key, $method, $config) {
        $formatString = $method . $config['domain'] . '/?' . $this->json2str($opt, 1);
        $sign = hash_hmac('sha1', $formatString, $key);
        $sign = base64_encode($this->_hex2bin($sign));
        return $sign;
    }
    // v2接口的key首字母小写，v3改成大写，此处做了向下兼容
    function backwardCompat($result) {
        if(!is_array($result)){
            return null;
        }
        $compat = array();
        foreach ($result as $key => $value) {
            if(is_array($value)) {
                $compat[lcfirst($key)] = $this->backwardCompat($value);
            } elseif ($key == 'Token') {
                $compat['sessionToken'] = $value;
            } else {
                $compat[lcfirst($key)] = $value;
            }
        }
        return $compat;
    }
    // 获取临时密钥
    function getTempKeys($config) {
        $result = null;
        try{
            if(array_key_exists('policy', $config)){
                $policy = $config['policy'];
            }else{
                if(array_key_exists('bucket', $config)){
                    $ShortBucketName = substr($config['bucket'],0, strripos($config['bucket'], '-'));
                    $AppId = substr($config['bucket'], 1 + strripos($config['bucket'], '-'));
                }else{
                    return null;
                }
                if(array_key_exists('allowPrefix', $config)){
                    if(!(strpos($config['allowPrefix'], '/') === 0)){
                        $config['allowPrefix'] = '/' . $config['allowPrefix'];
                    }
                }else{
                    return null;
                }
                $policy = array(
                    'version'=> '2.0',
                    'statement'=> array(
                        array(
                            'action'=> $config['allowActions'],
                            'effect'=> 'allow',
                            'principal'=> array('qcs'=> array('*')),
                            'resource'=> array(
                                'qcs::cos:' . $config['region'] . ':uid/' . $AppId . ':' . $config['bucket'] . $config['allowPrefix']
                            )
                        )
                    )
                );
            }
            $policyStr = str_replace('\\/', '/', json_encode($policy));
            $Action = 'GetFederationToken';
            $Nonce = rand(10000, 20000);
            $Timestamp = time();
            $Method = 'POST';
            if(array_key_exists('durationSeconds', $config)){
                if(!(is_integer($config['durationSeconds']))){
                    return null;
                }
            }
            $params = array(
                'SecretId'=> $config['secretId'],
                'Timestamp'=> $Timestamp,
                'Nonce'=> $Nonce,
                'Action'=> $Action,
                'DurationSeconds'=> $config['durationSeconds'],
                'Version'=>'2018-08-13',
                'Name'=> 'cos',
                'Region'=> $config['region'],
                'Policy'=> urlencode($policyStr)
            );
            $params['Signature'] = $this->getSignature($params, $config['secretKey'], $Method, $config);
            $url = $config['url'];
            $ch = curl_init($url);
            if(array_key_exists('proxy', $config)){
                $config['proxy'] && curl_setopt($ch, CURLOPT_PROXY, $config['proxy']);
            }
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->json2str($params));
            $result = curl_exec($ch);
            if(curl_errno($ch)) $result = curl_error($ch);
            curl_close($ch);
            $result = json_decode($result, 1);
            if (isset($result['Response'])) {
                $result = $result['Response'];
                if(isset($result['Error'])){
                    return null;
                }
                $result['startTime'] = $result['ExpiredTime'] - $config['durationSeconds'];
            }
            $result = $this->backwardCompat($result);
            return $result;
        }catch(Exception $e){
            if($result == null){
                $result = "error: " . + $e->getMessage();
            }else{
                $result = json_encode($result);
            }
            return null;
        }
    }

    // get policy
    function getPolicy($scopes){
        if (!is_array($scopes)){
            return null;
        }
        $statements = array();

        for($i=0, $counts=count($scopes); $i < $counts; $i++){
            $actions=array();
            $resources = array();
            array_push($actions, $scopes[$i]->get_action());
            array_push($resources, $scopes[$i]->get_resource());

            $statement = array(
                'actions' => $actions,
                'effect' => $scopes[$i]->get_effect(),
                'resource' => $resources
            );
            array_push($statements, $statement);
        }

        $policy = array(
            'version' => '2.0',
            'statement' => $statements
        );
        return $policy;
    }
}

class Scope{
    var $action;
    var $bucket;
    var $region;
    var $resourcePrefix;
    var $effect = 'allow';
    function __construct($action, $bucket, $region, $resourcePrefix){
        $this->action = $action;
        $this->bucket = $bucket;
        $this->region = $region;
        $this->resourcePrefix = $resourcePrefix;
    }

    function set_effect($isAllow){
        if($isAllow){
            $this->effect = 'allow';
        }else{
            $this->effect = 'deny';
        }
    }

    function get_action(){
        if($this->action == null){
            return null;
        }
        return $this->action;
    }

    function get_resource(){
        if($this->bucket == null){
            return null;
        }
        if($this->resourcePrefix == null){
            return null;
        }
        $index = strripos($this->bucket, '-');
        if($index < 0){
            return null;
        }
        $appid = substr($this->bucket, $index + 1);
        if(!(strpos($this->resourcePrefix, '/') === 0)){
            $this->resourcePrefix = '/' . $this->resourcePrefix;
        }
        return 'qcs::cos:' . $this->region . ':uid/' . $appid . ':' . $this->bucket . $this->resourcePrefix;
    }

    function get_effect(){
        return $this->effect;
    }
}