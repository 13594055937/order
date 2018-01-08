<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends GlobalController {
	public function _initialize(){
		$this->_logined();
	}
	
	/**
	 * 全控制器设置
	 * 
	 */
	private function __global(){
	 	$this->topNav = "user";
		//所属公司
		$company_list = M("Company")->select();
		$this->assign("company_list", $company_list);
	}

	public function dongjie(){
		$get = I('get.');
		$id = $get['id'];
		$userMdl = M("Base_customer");
		$data = array('id'=>$id, 'status'=>0);
		$userMdl->data($data)->save();
		$this->index();
	}

	public function create_pwd(){
		$chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',0,1,2,3,4,5,6,7,8,9);
		$new_pwd = '';
		for($i=0; $i<8;$i++){
			$new_pwd .= $chars[mt_rand(0,35)];
		}
		return $new_pwd;
	}

	public function pass_createpwd(){
		$id = I('get.id',0,'intval');
		$useMdl = D('User');
		$new_pwd = $this->create_pwd();
		$data = array(
			'id' => $id,
			'pwd' => $new_pwd,
		);
		$return = $useMdl->save($data);
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！密码是".$new_pwd)));
		}
	}

	/**
	 * 验证密码
	 * 
	 */
	private function _valiPwd($val){
		$return = preg_match('/^[a-zA-Z0-9_]{6,15}/', $val);
		return $return == 0?FALSE:TRUE;
	}
	public function admin_index(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('User');
		$where_str = parent::getWhereByPost(I('post.'));
	    $count = $userMdl->where($where_str)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();

		$list = $userMdl->where($where_str)->order('id DESC')->limit(($p-1)*$pageSize.','.$pageSize)->select();
		$this->assign("list", $list);
		$this->assign("page", $show);
		$this->assign("data", I('post.'));
		$this->assign("post", I('post.'));
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('admin_index'); 
    }

    public function admin_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('User');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('admin_add');
	}

	public function admin_save(){
		$post = I('post.');
		$useMdl = M('User');
		$user_list = $useMdl->where("username='".$post['username']."'")->select();
		if(!empty($post['userpwd'])){
			$post['userpwd'] = parent::getMd5Pwd($post['username'],$post['userpwd']);
		}
		if(empty($user_list[0])){
			$post['createtime'] = time();
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
		}
	}

	public function admin_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('User');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	public function editpwd(){
		$post = I('post.');
		$id = $post['id'];
		$old_pwd = $post['old_userpwd'];
		$userpwd = $post['userpwd'];
		if(empty($id) || empty($old_pwd) || empty($userpwd) ){
			echo "丢失参数！";exit;
		}
		$userMdl = M('User');
		$userinfo = $userMdl->where($id)->find();
		if(empty($userinfo) || $userinfo['userpwd'] != parent::getMd5Pwd($userinfo['username'],$old_pwd)){
			echo "原始密码不正确！";exit;
		}
		$new_pwd = parent::getMd5Pwd($userinfo['username'],$userpwd);
		$userMdl->data(array("id"=>$id,"userpwd"=>$new_pwd))->save();
		echo "成功"; exit;
	}

	public function daochu(){
		$userMdl = M("User");
		$where_str = parent::getWhereByPost(I('post.'));
		$count = $userMdl->where($where_str)->count();
		$pageSize = C("admin_pageCount");
		$p = ceil($count/$pageSize);
		$file_name = date("Y_m_d")."_user.xls";
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment; filename={$file_name}");
		echo iconv("UTF-8","GBK",'编号')."\t".iconv("UTF-8","GBK",'姓名')."\t".iconv("UTF-8","GBK",'联系方式')."\t"
			.iconv("UTF-8","GBK",'openid')."\t".iconv("UTF-8","GBK",'用户类型')."\t".iconv("UTF-8","GBK",'所属公司')."\t"
			.iconv("UTF-8","GBK",'状态')."\n";
		for($i=1; $i<=$p;$i++){
			$list = $userMdl->where($where_str)->field("usercode,username,mobile,openid,usertype,company,status")->where($where_str)->limit(($i-1)*$pageSize.','.$pageSize)->select();
			foreach ($list as $key=>$v)
			{
				foreach ($v as $k1 => $value) {
					if($k1 == 'status') {
						if($value==1){
							echo iconv("UTF-8","GBK",'启用')."\t";
						}else{
							echo iconv("UTF-8","GBK",'停用')."\t";
						}
					}elseif($k1 == 'usertype') {
						if($value==1){
							echo iconv("UTF-8","GBK",'工程师')."\t";
						}elseif($value ==2){
							echo iconv("UTF-8","GBK",'派单人')."\t";
						}else{
							echo iconv("UTF-8","GBK",'管理员')."\t";
						}
					}else{
						echo iconv("UTF-8","GBK",$value)."\t";
					}
				}
				echo "\n";
			}
		}
		exit;
	}

}
