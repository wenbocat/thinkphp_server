<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-24
 * Time: 22:16
 */

namespace app\common;


use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Live\V20180801\LiveClient;
use TencentCloud\Live\V20180801\Models\DescribeLiveStreamStateRequest;

class TXService
{

    protected static $API_URL = "https://adminapisgp.im.qcloud.com";

    //timelife = 0 时只发送在线用户
    public static function sendmsg($touid,$elem,$timelife=604800){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/openim/sendmsg?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data['SyncOtherMachine'] = 2;
        $req_data['To_Account'] = $touid;
        $req_data['MsgRandom'] = rand(1000000,9999999);
        $req_data['MsgTimeStamp'] = time();
        $req_data['MsgLifeTime'] = $timelife;
        $req_data['MsgBody'][0] = $elem;

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    //批量发送消息
    //timelife = 0 时只发送在线用户
    public static function sendSystemMsg($touidArr,$elem,$timelife=604800){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/openim/batchsendmsg?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data['SyncOtherMachine'] = 2;
        $req_data['To_Account'] = $touidArr;
        $req_data['MsgRandom'] = rand(1000000,9999999);
        $req_data['MsgTimeStamp'] = time();
        $req_data['MsgLifeTime'] = $timelife;
        $req_data['MsgBody'][0] = $elem;

        $req_data['OfflinePushInfo']['PushFlag'] = 0;
        $req_data['OfflinePushInfo']['Title'] = $elem['MsgContent']['Title'];
        $req_data['OfflinePushInfo']['Desc'] = $elem['MsgContent']['Desc'];
        $req_data['OfflinePushInfo']['Ext'] = $elem['MsgContent']['Data'];

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    //批量发送广播 不会统计到未读消息数量
    //timelife = 0 时只发送在线用户
    public static function sendBroadcast($touidArr,$elem,$timelife=0){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_broadcast);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/openim/batchsendmsg?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_broadcast}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data['SyncOtherMachine'] = 2;
        $req_data['To_Account'] = $touidArr;
        $req_data['MsgRandom'] = rand(1000000,9999999);
        $req_data['MsgTimeStamp'] = time();
        $req_data['MsgLifeTime'] = $timelife;
        $req_data['MsgBody'][0] = $elem;

        $req_data['OfflinePushInfo']['PushFlag'] = 0;
        $req_data['OfflinePushInfo']['Title'] = $elem['MsgContent']['Title'];
        $req_data['OfflinePushInfo']['Desc'] = $elem['MsgContent']['Desc'];
        $req_data['OfflinePushInfo']['Ext'] = $elem['MsgContent']['Data'];

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    public static function createChatRoom($ownerid,$groupid,$type="ChatRoom"){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/group_open_http_svc/create_group?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data['Owner_Account'] = $ownerid;
        $req_data['Type'] = $type;
        $req_data['Name'] = $groupid;
        $req_data['GroupId'] = $groupid;

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    public static function destoryChatRoom($groupid){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/group_open_http_svc/destroy_group?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data['GroupId'] = $groupid;

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    //发送聊天室消息
    public static function sendChatRoomMsg($groupid,$elem){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/group_open_http_svc/send_group_msg?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        //$req_data['OnlineOnlyFlag'] = 1;
        $req_data['GroupId'] = $groupid;
        $req_data['MsgRandom'] = rand(1000000,9999999);
        $req_data['MsgBody'][0] = $elem;

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    //获取用户加入的群组
    public static function getMemberGroups($memberid,$groupType=""){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/group_open_http_svc/get_joined_group_list?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data["GroupType"] = $groupType;
        $req_data["Member_Account"] = $memberid;

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    //移除群组成员
    public static function deleteGroupMember($groupid,$memberid=[]){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/group_open_http_svc/delete_group_member?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data["GroupId"] = $groupid;
        $req_data["MemberToDel_Account"] = $memberid;
        $req_data["Silence"] = 1; // 是否静默删除（选填）

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    //禁言
    public static function banUser($groupid,$userids=[],$shutUpTime=0){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/group_open_http_svc/forbid_send_msg?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data['GroupId'] = $groupid;
        $req_data['Members_Account'] = $userids;
        $req_data['ShutUpTime'] = $shutUpTime; //0 取消禁言

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    public static function importAccount($id,$name,$avatar=''){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/im_open_login_svc/account_import?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data['Identifier'] = $id;
        $req_data['Nick'] = $name;
        $req_data['FaceUrl'] = $avatar; //0 取消禁言

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    public static function deleteAccount($id){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/im_open_login_svc/account_delete?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data['DeleteItem'] = [['UserID'=>$id]];

        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }

    public static function buildCustomElem($action,$data=[],$title='',$desc=''){
        $elem['MsgType'] = 'TIMCustomElem';
        $elem['MsgContent']['Desc'] = $desc;
        $elem['MsgContent']['Title'] = $title;

        $eledata['action'] = $action;
        if(!empty($data)){
            $eledata['data'] = $data;   
        }
        $elem['MsgContent']['Data'] = json_encode($eledata);

        return $elem;
    }

    public static function checkPushActive($stream){
        $configPri = getConfigPri();
        $cred = new Credential($configPri->qcloud_secretid, $configPri->qcloud_secretkey);
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint("live.tencentcloudapi.com");

        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);
        $client = new LiveClient($cred, "", $clientProfile);

        $req = new DescribeLiveStreamStateRequest();

        $params = array(
            "AppName" => "live",
            "DomainName" => $configPri->push_domain,
            "StreamName" => $stream
        );
        $req->fromJsonString(json_encode($params));

        $resp = $client->DescribeLiveStreamState($req);
        return $resp->getStreamState() == 'active';
    }

    // 获取群成员详细资料
    public static function getGroupMemberInfo($group_id, $limit, $offset){
        $configPri = getConfigPri();
        $tsl_api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $tsl_api->genSig($configPri->txim_admin);
        $random = rand(0,4294967295);
        $api = self::$API_URL."/v4/group_open_http_svc/get_group_member_info?sdkappid={$configPri->im_sdkappid}&identifier={$configPri->txim_admin}&usersig={$sig}&random={$random}&contenttype=json";
        $req_data = [
            'GroupId' => $group_id,
            'Limit' => $limit,
            'Offset' => $offset,
        ];
        return json_decode(httpHelper($api,json_encode($req_data)),true);
    }
}