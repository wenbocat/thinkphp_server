<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: Page.class.php 2712 2012-02-06 10:12:49Z liu21st $
namespace Common;
class AjaxPage {
    // ��ҳ��ÿҳ��ʾ��ҳ��
    public $rollPage = 5;
    // ҳ����תʱҪ���Ĳ���
    public $parameter  ;
    // Ĭ���б�ÿҳ��ʾ����
    public $listRows = 20;
    // ��ʼ����
    public $firstRow ;
    // ��ҳ��ҳ����
    protected $totalPages  ;
    // ������
    protected $totalRows  ;
    // ��ǰҳ��
    protected $nowPage    ;
    // ��ҳ��������ҳ��
    protected $coolPages   ;
    // ��ҳ��ʾ����
    protected $config  = array('header'=>'����¼','prev'=>'��һҳ','next'=>'��һҳ','first'=>'��һҳ','last'=>'���һҳ','theme'=>' %totalRow% %header% %nowPage%/%totalPage% ҳ %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
    // Ĭ�Ϸ�ҳ������
    protected $varPage;


    public function __construct($totalRows,$listRows='',$ajax_func,$parameter='') {
        $this->totalRows = $totalRows;
        $this->ajax_func = $ajax_func;
        $this->parameter = $parameter;
        $this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
        if(!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //��ҳ��
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function nowpage($totalRows,$listRows='',$ajax_func,$parameter='') {
        $this->totalRows = $totalRows;
        $this->ajax_func = $ajax_func;
        $this->parameter = $parameter;
        $this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
        if(!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //��ҳ��
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);

        return $this->nowPage;
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }


    public function show() {
        if(0 == $this->totalRows) return '';
        $p = $this->varPage;
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        //���·�ҳ�ַ���
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a class='ajaxify' id='big' href='JavaScript:".$this->ajax_func."(".$upRow.")'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a class='ajaxify' id='big' href='javascript:".$this->ajax_func."(".$downRow.")'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<a class='ajaxify' id='big' href='javascript:".$this->ajax_func."(".$preRow.")'>��".$this->rollPage."ҳ</a>";
            $theFirst = "<a class='ajaxify' id='big' href='javascript:".$this->ajax_func."(1)' >".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a class='ajaxify' id='big' href='javascript:".$this->ajax_func."(".$nextRow.")' >��".$this->rollPage."ҳ</a>";
            $theEnd = "<a class='ajaxify' id='big' href='javascript:".$this->ajax_func."(".$theEndRow.")' >".$this->config['last']."</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "&nbsp;<a class='ajaxify' id='big' href='javascript:".$this->ajax_func."(".$page.")'>&nbsp;".$page."&nbsp;</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "&nbsp;<span class='current'>".$page."</span>";
                }
            }
        }
        $pageStr  =  str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }

}

?>