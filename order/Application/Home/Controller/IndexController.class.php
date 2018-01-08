<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;



class IndexController extends GlobalController{
        
    private function __global(){
        $this->topNav = "首页";
     }
    public function index(){
		$this->_logined();
		$this->ind();
echo 3434;exit;
		if(isset($_GET['code']) && !empty($_GET['code']))
			$this->go_index();
    }
    
	public function go_index(){
		$this->_logined();
        $this->__global();
echo 3234;exit;
        $this->display("index");
	}
	
	public function suggest(){
		$this->_logined();
        $this->__global();
		$user = session("user");
		if(empty($user) || empty($user['telphone'])){
			$this->redirect("user/modifyUserInfo");
		}
        $this->display("suggest");
	}
}
