<?php
namespace Home\Controller;
use Think\Controller;
class CardController extends GlobalController {
	function _initialize(){
		$this->_logined();
	}
	
	/**
	 * 全控制器设置
	 * 
	 */
	 private function __global(){
	 	$this->topNav = "Forum";
	 }
	/**
	 * 
	 * eagle_91@sina.com
	 * created 2014-09-10
	 */
    public function index(){
    	$this->__global();
    	$type = I('post.tasktype'); //任务类型
        $task_type = M('Task_type');
        if(empty($type) && isset($_GET['type'])){
            $l = $task_type->where("task_type_name ='".$_GET['type']."'")->select();
            if(!empty($l[0])) $type = $l[0]['id'];
        }
    	$city = I('post.city');//地域查询
    	$other = I('post.other');//其他综合查询,这个暂时不知道是查询什么
        $this->assign("tasktype", $type);
        $this->assign("city", $city);
        $this->assign("other", $other);

    	$session_user = session("user");
    	$task_type_list = $task_type->where("status=1 and user_types like '%".$session_user['user_type']."%'")->select();
    	$this->assign("task_type", $task_type_list);

    	$maintask = M('Main_task');
    	$where_array = array("is_baobei =0 and accept_uid=0  and expire_time>".time());
    	if(!empty($city)){
    		$where_array[] = " city like '%{$city}%'";
    	}
    	if(!empty($other)){
    		//todo
    	}

    	//任务大厅
    	if(!empty($type)){
    		$where_array[] = " task_type.id ='{$type}'";
    	}
    	$where = implode(' and ', $where_array);

    	$p = I('get.p',1,'intval');
        $pageSize = C('pageCount');
        $count = $maintask->join('left join task_type on task_type.id = main_task.task_type_id')->where($where)->count();
        $Page = new \Think\Page($count,$pageSize);
        $show = $Page->show();

    	$maintasklist = $maintask->join('left join task_type on task_type.id = main_task.task_type_id')->field("main_task.*,task_type.task_type_name")->where($where)->order(" create_time desc ")->limit(($p-1)*$pageSize.','.$pageSize)->select();
    	$this->assign("maintask", $maintasklist);

    	$provinceMdl = M("Province");
    	$provincelist = $provinceMdl->select();
    	$this->assign("province", $provincelist);

    	$this->assign("get_info", I("post."));//选择框的信息
		$this->display('index'); 
    }

    public function getcityinfo(){
    	$province_id = I("get.province_id",1,"intval");
    	$cityinfo = parent::getCityInfo($province_id);
    	$html = "<option value=''>请选择城市</option>";
        foreach ($cityinfo as $key => $value) {
            $html .= "<option value='{$value['cityname']}'>{$value['cityname']}</option>" ;
        }
        echo $html;
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
