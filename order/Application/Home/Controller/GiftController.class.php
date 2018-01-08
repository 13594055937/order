<?php
namespace Home\Controller;
use Think\Controller;
//礼卡--礼券
class GiftController extends GlobalController {
	function _initialize(){
		$this->_logined();
	}
	
	private function __global(){
	 	$this->topNav = "首页";
        if(isset($_GET['p'])){
            $this->assign("p",$_GET['p']);
        }else{
            $this->assign("p",1);
        }
	}

	public function ajax_detail(){
        $this->__global();
        $cardMdl = M("Gift");
        $id = intval($_GET['id']);
        $gift_info = $cardMdl->find($id);
        if(empty($gift_info)){
            echo json_encode(array("code"=>-1,"data"=>null));
            exit;
        }
        echo json_encode(array("code"=>0,"data"=>$gift_info));
        exit;
    }
   
}
