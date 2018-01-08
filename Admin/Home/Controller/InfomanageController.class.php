<?php
namespace Home\Controller;
use Think\Controller;
class InfomanageController extends GlobalController {
	public function _initialize(){
		$this->_logined();
	}
	
	/**
	 * 全控制器设置
	 * 
	 */
	 private function __global(){
	 	$this->topNav = "infomanage";
	 }
	/**
	 * 
	 * eagle_91@sina.com
	 * created 2014-09-10
	 */
	//留言管理
    public function suggest(){
    	$this->__global();
	    $userMdl = M('Suggest');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('suggest'); 
    }

	 public function other_code(){
        	$this->__global();
            	//$userMdl = M('Suggest');
            	//$list = $userMdl->order('id DESC')->select();
            	//$this->assign('list',$list);// 赋值数据集
                $this->display('other_code');
	    }

	function suggest_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Suggest');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	//areas
	public function engineertype(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('Engineertype');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->where("status=1")->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('engineer_type_index'); 
    }
	
	public function engineer_type_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Engineertype');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('engineer_type_add');
	}

	public function engineer_type_save(){
		$post = I('post.');
		$useMdl = M('Engineertype');
		if(!isset($post['name']) || empty($post['name'])){
			$this->error("名称不能为空","engineertype");
		}
		if($post['id']<=0){
			$post['inserttime'] = time();
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		if($return === FALSE){
			$this->error("保存失败","engineertype");
		}else{
			$this->success("保存成功","engineertype");
		}
	}

	public function engineer_type_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Engineertype');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	//city
	public function engineerrate(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('Engineerrate');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->where("status=1")->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('engineer_rate_index'); 
    }
	
	public function engineer_rate_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Engineerrate');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('engineer_rate_add');
	}

	public function engineer_rate_save(){
		$post = I('post.');
		$useMdl = M('Engineerrate');
		if(!isset($post['name']) || empty($post['name'])){
			$this->error("名称不能为空","engineerrate");
		}
		if($post['id']<=0){
			$post['inserttime'] = time();
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		if($return === FALSE){
			$this->error("保存失败","engineerrate");
		}else{
			$this->success("保存成功","engineerrate");
		}
	}


	function engineer_rate_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Engineerrate');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	/*网站用户管理*/
    public function customers_manage_index(){
    	$this->__global();
	    $userMdl = M('User');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('customers_manage_index'); 
    }
	
	public function customers_manage_add(){
		$this->__global();
		$userid = I('get.user_id',0,'intval');
		if($userid>0){
			$userMdl = M('User');
			$data = $userMdl->find($userid);
			$this->assign('data',$data);
		}
		$headMdl = M("Head_title");
		$list = $headMdl->select();
		$this->assign("head_title", $list);
		$this->display('customers_manage_add');
	}

	public function customers_manage_save(){
		$post = I('post.');
		$useMdl = M('User');
		if($post['id']<=0){
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

	function customers_manage_del(){
		$userid = I('get.user_id',0,'intval');
		if($userid<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('User');
		$userMdl->delete($userid);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	/*年级管理*/
    public function jobtype(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('Jobtype');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('jobtype_index'); 
    }
	
	public function job_type_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Jobtype');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('jobtype_add');
	}

	public function job_type_save(){
		$post = I('post.');
		$useMdl = M('Jobtype');
		if(!isset($post['name']) || empty($post['name'])){
			$this->error("名称不能为空","jobtype");
		}
		if($post['id']<=0){
			$post['inserttime'] = time();
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		if($return === FALSE){
			$this->error("保存失败","jobtype");
		}else{
			$this->success("保存成功","jobtype");
		}
	}

	function job_type_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Jobtype');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}
	
	/*广告管理*/
	public function ad_manage_index(){
    	$this->__global();
	    $userMdl = M('Img_manage');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('ad_manage_index'); 
    }
	
	public function ad_manage_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Img_manage');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('ad_manage_add');
	}

	public function ad_manage_save(){
		$post = I('post.');
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','JPG');// 设置附件上传类型
        $upload->rootPath  =     './Public/images/upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        $pic = "";
        if(!$info) {// 上传错误提示错误信息
           if($post['id']<=0 && false)
           $this->error($upload->getError(),'ad_manage_index');
        }else{// 上传成功
            $pic = '';
            foreach($info as $file){
                    $pic .= $file['savepath'].$file['savename'];
                }
        }
		if($pic !=''){
			$post['small_pic'] = "upload/".$pic;
		}
		
		$useMdl = M('Img_manage');
		if($post['id']<=0){
			$post['create_time'] = date("Y-m-d H:i:s");
			$return = $useMdl->data($post)->add();
		}else{
			$post['update_time'] = date("Y-m-d H:i:s");
			$return = $useMdl->save($post);
		}
		$this->success("保存成功","ad_manage_index");
	}

	public function ad_manage_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Img_manage');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	//帮助中心添加
	public function help_index(){
    $this->__global();
    $userMdl = M('Help_center');
    $list = $userMdl->order('id DESC')->select();
    $this->assign('list',$list);// 赋值数据集
    $this->display('help_index');
}

public function help_add(){
    $this->__global();
    $id = I('get.id',0,'intval');
    if($id>0){
        $userMdl = M('Help_center');
        $data = $userMdl->find($id);
        $this->assign('data',$data);
    }
    $this->display('help_add');
}

public function help_save(){
    $post = I('post.');
    $useMdl = M('Help_center');
    if($post['id']<=0){
        $return = $useMdl->data($post)->add();
    }else{
        $return = $useMdl->save($post);
    }
    $this->success("保存成功","help_index");
}

public function help_del(){
    $id = I('get.id',0,'intval');
    if($id<=0){
            exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
    }
    $userMdl = M('Help_center');
    $userMdl->delete($id);
    exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
}
	/*logo管理*/
	public function logo(){
    	$this->__global();
	    $userMdl = M('Base_webconfig');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('logo'); 
    }
	
	public function logo_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Base_webconfig');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('logo_add');
	}

	public function logo_save(){
		$post = I('post.');
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','JPG');// 设置附件上传类型
        $upload->rootPath  =     './Public/images/upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        $pic = "";
        if(!$info) {// 上传错误提示错误信息
           if($post['id']<=0 && false)
           $this->error($upload->getError(),'logo');
        }else{// 上传成功
            $pic = '';
            foreach($info as $file){
                    $pic .= $file['savepath'].$file['savename'];
                }
        }
		if($pic !=''){
			$post['small_pic'] = "upload/".$pic;
		}
		
		$useMdl = M('Base_webconfig');
		if($post['id']<=0){
			$post['create_time'] = date("Y-m-d H:i:s");
			$return = $useMdl->data($post)->add();
		}else{
			$post['update_time'] = date("Y-m-d H:i:s");
			$return = $useMdl->save($post);
		}
		$this->success("保存成功","logo");
	}

	public function logo_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Base_webconfig');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}
	/*视频管理*/
	public function video_manage_index(){
    	$this->__global();
	    $userMdl = M('Videos');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('video_manage_index'); 
    }
	
	public function video_manage_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Videos');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('video_manage_add');
	}

	public function video_manage_save(){
		$post = I('post.');
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','JPG');// 设置附件上传类型
        $upload->rootPath  =     './Public/images/upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        $pic = "";
        if(!$info) {// 上传错误提示错误信息
           if($post['id']<=0 && false)
           $this->error($upload->getError(),'video_manage_index');
        }else{// 上传成功
            $pic = '';
            foreach($info as $file){
                    $pic .= $file['savepath'].$file['savename'];
                }
        }
		if($pic !=''){
			$post['small_pic'] = "upload/".$pic;
		}
		$useMdl = M('Videos');
		if($post['id']<=0){
			$post['create_time'] = date("Y-m-d H:i:s");
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		$this->success("保存成功","video_manage_index");
	}

	function video_manage_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Videos');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}
	/*后台用户管理*/
	public function useradmin_manage_index(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('Admin_user');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->order('id DESC')->join(" left join citys on citys.cityid=admin_user.manage_list")->field("admin_user.*,citys.name as city_name")->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('useradmin_manage_index'); 
    }
	
	public function useradmin_manage_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		$user_id = I('get.user_id',0,'intval');
		$username = I('get.username',"","trim");
		if($id>0){
			$userMdl = M('Admin_user');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$citysMdl = M('Citys');
		$citys = $citysMdl->select();
		$this->assign("citys",$citys);
		$this->assign("username", $username);
		$this->assign("user_id", $user_id);
		$this->display('useradmin_manage_add');
	}

	public function useradmin_manage_save(){
		$post = I('post.');
		$useMdl = M('Admin_user');
		$post['update_time'] = date("Y-m-d H:i:s");
		$userinfo = $this->user_info;
		unset($post['confirm_pwd']);
		if($post['id']<=0){
			$post['create_time'] = date("Y-m-d H:i:s");;
			$post['create_user_name'] = $userinfo['username'];
			$post['update_user_name'] = $userinfo['username'];
			$return = $useMdl->data($post)->add();
		}else{
			$post['update_user_name'] = $userinfo['username'];
			$return = $useMdl->save($post);
		}
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
		}
	}

	function useradmin_manage_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Admin_user');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	/*不常用信息管理*/
	public function config_manage_index(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('Config_js');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('config_manage_index'); 
    }
	
	public function config_manage_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		$user_id = I('get.user_id',0,'intval');
		$username = I('get.username',"","trim");
		if($id>0){
			$userMdl = M('Config_js');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$edit_info = I("get.edit_info","添加信息");
		$this->assign("edit_info",$edit_info);
		$this->assign("username", $username);
		$this->assign("user_id", $user_id);
		$this->display('config_manage_add');
	}

	public function config_manage_save(){
		$post = I('post.');
		$useMdl = M('Config_js');
		$post['update_time'] = date("Y-m-d H:i:s");
		$userinfo = $this->user_info;
		if($post['id']<=0){
			$post['create_user_name'] = $userinfo['username'];
			$post['update_user_name'] = $userinfo['username'];
			$return = $useMdl->data($post)->add();
		}else{
			$post['update_user_name'] = $userinfo['username'];
			$return = $useMdl->save($post);
		}
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
		}
	}

	function config_manage_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Config_js');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}
	//热搜
	public function search_log(){
    	$this->__global();
	    $userMdl = M('Search_log');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('search_log'); 
    }
	
	public function search_log_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Search_log');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('search_log_add');
	}

	public function search_log_save(){
		$post = I('post.');
		$useMdl = M('Search_log');
		if($post['id']<=0){
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

	public function search_log_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Search_log');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	/*系统管理*/
	public function system_info_manage_index(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('System_info');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('system_info_manage_index'); 
    }
	
	public function system_info_manage_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		$user_id = I('get.user_id',0,'intval');
		$username = I('get.username',"","trim");
		if($id>0){
			$userMdl = M('System_info');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$edit_info = I("get.edit_info","添加信息");
		$this->assign("edit_info",$edit_info);
		$this->assign("username", $username);
		$this->assign("user_id", $user_id);
		$this->display('system_info_manage_add');
	}

	public function system_info_manage_save(){
		$post = I('post.');
		$useMdl = M('System_info');
		$post['update_time'] = date("Y-m-d H:i:s");
		$userinfo = $this->user_info;
		if($post['id']<=0){
			$post['create_user_name'] = $userinfo['username'];
			$post['update_user_name'] = $userinfo['username'];
			$return = $useMdl->data($post)->add();
		}else{
			$post['update_user_name'] = $userinfo['username'];
			$return = $useMdl->save($post);
		}
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
		}
	}

	public function system_info_manage_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('System_info');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	//首页导航
	public function index_nav(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('Index_nav');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->order(' index_nav.paixu DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('index_nav'); 
    }
	
	public function index_nav_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		$userMdl = M('Index_nav');
		if($id>0){
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$index_nav_list = $userMdl->where("parent_id=0")->select();
		$this->assign("index_nav_list", $index_nav_list);
		$this->display('index_nav_add');
	}

	public function index_nav_save(){
		$post = I('post.');
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','JPG');// 设置附件上传类型
        $upload->rootPath  =     './Public/images/nav/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        $pic = "";
        if(!$info) {// 上传错误提示错误信息
           //todo
        }else{// 上传成功
            $pic = '';
            foreach($info as $file){
                    $pic .= $file['savepath'].$file['savename'];
                }
        }
		if($pic !=''){
			$post['info_imgs'] = "nav/".$pic;
		}
		$useMdl = M('Index_nav');
		$post['update_time'] = date("Y-m-d H:i:s");
		$userinfo = $this->user_info;
		if($post['id']<=0){
			$post['create_user_name'] = $userinfo['username'];
			$post['update_user_name'] = $userinfo['username'];
			$return = $useMdl->data($post)->add();
		}else{
			$post['update_user_name'] = $userinfo['username'];
			$return = $useMdl->save($post);
		}
		$this->success("保存成功","index_nav");
	}


	public function index_nav_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Index_nav');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	/*广告管理*/
	public function rq_link(){
    	$this->__global();
	    $userMdl = M('Rq_link');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('rq_link'); 
    }
	
	public function rq_link_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Rq_link');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('rq_link_add');
	}

	public function rq_link_save(){
		$post = I('post.');
		$useMdl = M('Rq_link');
		$post['update_time'] = date("Y-m-d H:i:s");
		if($post['id']<=0){
			$post['create_time'] = date("Y-m-d H:i:s");
			$return = $useMdl->data($post)->add();
		}else{
			$post['update_time'] = date("Y-m-d H:i:s");
			$return = $useMdl->save($post);
		}
		$this->success("保存成功","rq_link");
	}

	public function rq_link_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Rq_link');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	//报名管理
	public function tuiding(){
		$this->__global();
		$pici_id = I('get.pici_id', 0 ,'intval');
		$baoming_type = I('get.baoming_type', 1 , 'intval');
		//申请退款1，已退款2,拒绝3
		$tuidingMdl = M("Baoming_order");
		$list = $tuidingMdl->join("left join user on user.id = baoming_order.user_id")->field("baoming_order.*,user.username,user.telphone")
		->where("pici_id={$pici_id} and baoming_order.status=2")->select();
		$this->assign("baoming_type", $baoming_type);
		$this->assign("list", $list);
		$this->display("tuiding");
	}

	 public function totuikuan(){
        $this->__global();
        $pici_id = I('get.id', 0 ,'intval');
		$get = I("get.");
        $tuidingMdl = M("Baoming_order");
		$lists = $tuidingMdl->where("id=".$pici_id)->select();
		$post['id'] = $pici_id;
		$post['status'] = 3;
		$tuidingMdl->data($post)->save();
		$piciMdl = M("Huodong_pici");
		
		$list_huoMdl = M("Huodong_baoming");
		$lists = $list_huoMdl->join("left join huodong_pici as hp on hp.huodong_id=huodong_baoming.id")->field("jifen")->where("hp.id = ".$get['pici_id'])->select();
		if(!empty($lists[0]) && $lists[0]['jifen'] > 0){
			$userMdl = M("User");
			$userMdl->where("id=".$get['user_id'])->setDec("jifen", $lists[0]['jifen']);
			$jifen_logMdl = M("Jifen_log");
			$data = array("userid"=>$get['user_id'],"jifen"=>$lists[0]['jifen'],"reason"=>"退款成功pici_id".$get['pici_id'],"time"=>date("Y-m-d"));
			$jifen_logMdl->data($data)->add();
		}
		$tuidingMdl->delete($pici_id);
		$this->success("确定退款成功",U("infomanage/tuiding",$get));
        }	
	
	//报名管理
	public function baoming(){
		$this->__global();
		$pici_id = I('get.pici_id', 0 ,'intval');
		$baoming_type = I('get.baoming_type', 1 , 'intval');
		//未付款0，付款1，退款中2，已退款3
		$baomingMdl = M("Baoming_order");
		$list = $baomingMdl->join("left join user on user.id= baoming_order.user_id")
		->where("pici_id=$pici_id and baoming_order.status=1")->order("create_time desc")->select();
		$this->assign("baoming_type", $baoming_type);
		$this->assign("list", $list);
		$this->display("baoming");
	}

	//沙龙报名管理
	public function shalong_baoming(){
		$this->__global();
		$shalong_id = I('get.shalong_id', 0 ,'intval');
		$shalong_type = I('get.shalong_type', 1 , 'intval');
		//未付款0，付款1，退款中2，已退款3
		$baomingMdl = M("Subscribe_shalong");
		$list = $baomingMdl->join("left join user on user.id= subscribe_shalong.user_id")
		->where("shalong_id=$shalong_id")->order("create_time desc")->select();
		$this->assign("list", $list);
		$this->assign("shalong_type", $shalong_type);
		$this->display("shalong_baoming");
	}

	//后台给用户发送信息
	public function customers_send_info_add(){
		$this->__global();
		$user_id = I("get.user_id",0, 'intval');
		$username = isset($_GET['username']) ? $_GET['username'] :"";
		$this->assign("user_id", $user_id);
		$this->assign("username", $username);
		$this->display("send_user_info");
	}

	public function send_user_info_save(){
		$post = I('post.');
        $useMdl = M('User_message');
        $post['sender'] = '管理员';
        $post['create_time'] = date("Y-m-d");
        $return = $useMdl->data($post)->add();
        exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
	}

	public function user_message(){
		$this->__global();
		$m = M("User_message");
		$list = $m->join("left join user on user.id=user_message.user_id")->field("user.telphone,user.username,user_message.*")->select();
		$this->assign("list", $list);
		$this->display("user_message_index");
	}

	public function user_message_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('User_message');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	public function add_jifen(){
		$user_id = I("post.user_id",1,"intval");
		$jifen_num = I("post.jifen_num",1,"intval");
		$useMdl = M('User');
		$useMdl->where("id={$user_id}")->setInc("jifen", $jifen_num);
		$jifen_logMdl = M("Jifen_log");
		$data['userid'] = $user_id;
		$data['jifen'] = $jifen_num;
		$data['reason'] = "后台管理员添加";
		$data['time'] = date("Y-m-d H:i:s");
		$jifen_logMdl->data($data)->add();
		echo "添加成功";
	}
	
	public function dengji_index(){
    		$this->__global();
	    $userMdl = M('Head_title');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('dengji_index'); 
   	 }
	
	public function dengji_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Head_title');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('dengji_add');
	}

	public function dengji_save(){
		$post = I('post.');
		$useMdl = M('Head_title');
		if($post['id']<=0){
			$post['create_time'] = date("Y-m-d H:i:s");
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

	function dengji_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Head_title');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}
	
	/*积分兑换管理*/
	public function coin_exchange(){
    	$this->__global();
	    $userMdl = M('jifen_duikuan');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('coin_exchange'); 
    }
	
	 public function show_exchange(){
        	$this->__global();
		$id = I("get.id",1,"intval");
            $userMdl = M('Jifen_log');
            $list = $userMdl->join("left join jifen_duikuan on jifen_log.jifen_duikuan_id = jifen_duikuan.id left join user on user.id=jifen_log.userid")
	->field("jifen_log.*,jifen_duikuan.change_goods,user.username,user.telphone,user.jifen as user_jifen")	
	->where("jifen_duikuan_id=".$id)->order('jifen_log.id DESC')->select();
            $this->assign('list',$list);// 赋值数据集
                $this->display('show_exchange');
	    }

	public function coin_exchange_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('jifen_duikuan');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('coin_exchange_add');
	}

	public function coin_exchange_save(){
		$post = I('post.');
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','JPG');// 设置附件上传类型
        $upload->rootPath  =     './Public/images/upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        $pic = "";
        if(!$info) {// 上传错误提示错误信息
           if($post['id']<=0 && false)
           $this->error($upload->getError(),'ad_manage_index');
        }else{// 上传成功
            $pic = '';
            foreach($info as $file){
                    $pic .= $file['savepath'].$file['savename'];
                }
        }
		if($pic !=''){
			$post['small_pic'] = "upload/".$pic;
			/*
			$image = new \Think\Image(); 
			// 在图片右下角添加水印文字 ThinkPHP 并保存为new.jpg
			$image->open('./Public/images/upload/'.$pic)->water('./Public/images/upload/water.png',\Think\Image::IMAGE_WATER_SOUTHEAST)->save("./Public/images/upload/".$pic); */
		}
		$useMdl = M('jifen_duikuan');
		if($post['id']<=0){
			$post['create_time'] = date("Y-m-d");
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		$this->success("保存成功","coin_exchange");
	}

	public function coin_exchange_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('jifen_duikuan');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}
	public function coin_exchange_setfasong(){
		$id = I('get.id',0,'intval');
		$jifenMdl = M("Jifen_log");
		$data = array("id"=>$id,"status"=>1);
		$jifenMdl->data($data)->save();
		$this->success("设置成功",U("infomanage/coin_exchange"));
	}
	public function user_set_manage(){
		$id = I('get.user_id',0,'intval');
		$is_manage = I("get.is_manage", 0 ,"intval");
		$jifenMdl = M("User");
		$data = array("id"=>$id,"is_manage"=> $is_manage);
		$jifenMdl->data($data)->save();
		$this->success("设置成功",U("infomanage/customers_manage_index"));
	}
	
	public function userinfo_save(){
	 	$post = I('post.');
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','JPG');// 设置附件上传类型
        $upload->rootPath  =     './Public/images/upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        $pic = "";
        if($info){// 上传成功
            $pic = '';
            foreach($info as $file){
                $pic .= $file['savepath'].$file['savename'];
            }
        }
		if($pic !=''){
			$post['small_pic'] = "upload/".$pic;
		}
		$useMdl = M('User');
		$return = $useMdl->save($post);
		$this->success("保存成功",U("infomanage/customers_manage_index"));
	 }
	 
	 public function contact_index(){
    	$this->__global();
	    $userMdl = M('Contact_us');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('contact_index'); 
   	 }
	
	public function contact_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Contact_us');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('contact_add');
	}

	public function contact_save(){
		$post = I('post.');
		$useMdl = M('Contact_us');
		if($post['id']<=0){
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		$this->success("保存成功",U("infomanage/contact_index"));
	}

	public function contact_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Contact_us');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	/**
	 * [contact_index description]
	 * @return [type] [description]
	 */
	public function weiquan_type(){
    	$this->__global();
	    $userMdl = M('Weiquan_type');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('weiquan_type_index'); 
   	 }
	
	public function weiquan_type_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Weiquan_type');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('weiquan_type_add');
	}

	public function weiquan_type_save(){
		$post = I('post.');
		$useMdl = M('Weiquan_type');
		if($post['id']<=0){
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		$this->success("保存成功",U("infomanage/weiquan_type_index"));
	}

	public function weiquan_type_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Weiquan_type');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}


	/**
	 * 任务类型
	 * @return [type] [description]
	 */
	public function task_type(){
    	$this->__global();
	    $userMdl = M('Task_type');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('task_type_index'); 
   	 }
	
	public function task_type_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Task_type');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('task_type_add');
	}

	public function task_type_save(){
		$post = I('post.');
		$useMdl = M('Task_type');
		
		$post['user_types'] = implode(",", $post['user_types']);
		if($post['id']<=0){
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		$this->success("保存成功",U("infomanage/task_type"));
	}

	public function task_type_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Task_type');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	/**
	 * [task_trand_index description]任务品牌
	 * @return [type] [description]
	 */
	public function task_trand_index(){
    	$this->__global();
	    $userMdl = M('Task_trand');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('task_trand_index'); 
   	 }
	
	public function task_trand_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Task_trand');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('task_trand_add');
	}

	public function task_trand_save(){
		$post = I('post.');
		$useMdl = M('Task_trand');
		if($post['id']<=0){
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		$this->success("保存成功",U("infomanage/task_trand_index"));
	}

	public function task_trand_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Task_trand');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}



	public function design_product_index(){
    	$this->__global();
	    $userMdl = M('Design_product');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('design_product_index'); 
   	 }
	
	public function design_product_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Design_product');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('design_product_add');
	}

	public function design_product_save(){
		$post = I('post.');
		$useMdl = M('Design_product');
		if($post['id']<=0){
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		$this->success("保存成功",U("infomanage/design_product_index"));
	}

	public function design_product_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Design_product');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}
	
	public function index_news(){
    	$this->__global();
	    $userMdl = M("Pc_news");

	    $list = $userMdl->order('paixu DESC')->limit(($p-1)*$pageSize.','.$pageSize)->select();
	    $this->assign("list", $list);

		$this->display('index_news'); 
    }

	public function news_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Pc_news');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('news_add');
	}

	public function news_save(){
		$post = I('post.');
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','JPG');// 设置附件上传类型
        $upload->rootPath  =     './Public/images/upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        $pic = "";
        if(!$info) {// 上传错误提示错误信息
           if($post['id']<=0)
           $this->error($upload->getError(),'index_news');
        }else{// 上传成功
            $pic = '';
            foreach($info as $file){
                    $pic .= $file['savepath'].$file['savename'];
                }
        }
		if($pic !=''){
			$post['small_pic'] = "upload/".$pic;
		}
		
		$useMdl = M('Pc_news');
		if($post['id']<=0){
			$post['create_time'] = date("Y-m-d H:i:s");
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		$this->success("保存成功","index_news");
	}

	public function news_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Pc_news');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}
	public function base_config(){
    	    $this->__global();
	    $userMdl = M('Base_webconfig');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('base_config'); 
   	 }
	
	public function base_config_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Base_webconfig');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('base_config_add');
	}

	public function base_config_save(){
		$post = I('post.');
		$useMdl = M('Base_webconfig');
		if($post['id']<=0){
			$post['create_time'] = date("Y-m-d H:i:s");
			$return = $useMdl->data($post)->add();
		}else{
			$post['update_time'] = date("Y-m-d H:i:s");
			$return = $useMdl->save($post);
		}
		$this->success("保存成功","base_config");
	}
	
	public function seo_index(){
    	    $this->__global();
	    $userMdl = M('Content_seo');
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
		$this->display('seo_index'); 
   	 }
	
	public function seo_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Content_seo');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('seo_add');
	}

	public function seo_save(){
		$post = I('post.');
		$useMdl = M('Content_seo');
		if($post['id']<=0){
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

	function seo_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Content_seo');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}
}
