<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
	/**
	 * 
	 * eagle_91@sina.com
	 * created 2014-09-10
	 */
    public function index(){
		$this->display('index'); 
    }
	
	/**
	 * 接口地址
	 * 
	 * 
	 */
	public function login(){
		$name = I('post.name','','trim');
		$pwd = I('post.pwd','','trim');
		if(empty($name) || empty($pwd)){
			exit(json_encode(array('status'=>'false','msg'=>'请输入账号或者密码！')));
		}
		$userMdl = M('User');
		$pwd = md5($name.$pwd."~!@");
		$exist = $userMdl->where("username='{$name}' and userpwd ='{$pwd}' and status=1")->select();
		if(empty($exist[0]['username'])){
			echo "不存在该账号或密码不正确或者不可用";
			exit;
		}
		$userMdl->data(array('id'=>$exist[0]['id'],'latestLogin'=>time()))->save();
		session('adminuser',$exist[0]);
		echo "success";
		exit;
	}
	
	/**
	 * 验证码
	 */
	public function verify(){
		header("Content-type: image/jpeg");
		$config = array(
			'fontSize' => 20,
			'length' => 4, // 验证码位数
			'imageH' => 40,
			'imageW' => 160
		);
		$Verify = new \Think\Verify($config);
		$Verify->entry();
	}
}
