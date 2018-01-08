<?php
namespace Home\Controller;
use Think\Controller;
class TaskController extends GlobalController {
	function _initialize(){
		$this->_logined();
	}
	
	/**
	 * 全控制器设置
	 * 
	 */
	 private function __global(){
         $this->topNav = "Forum";
         //公司列表
         $company_list = M("company")->where("status=1")->select();
         $this->assign("company_list", $company_list);
         //问题类型列表
         $job_type_list = M("Jobtype")->where("status=1")->select();
         $this->assign("job_type_list", $job_type_list);
	 }
	/**
	 * 
	 * eagle_91@sina.com
	 * created 2014-09-10
	 */
    public function index(){
    	$this->__global();
        echo "no entry coming!";exit;
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
    	$task_type_list = $task_type->where("status=1 and accept_user_type like '%".$session_user['user_type']."%'")->select();
    	$this->assign("task_type", $task_type_list);

    	$maintask = M('Main_task');
    	$where_array = array("is_baobei =0 and main_task.status!=4 and accept_uid=0 and expire_time>".time());
    	if(!empty($city)){
    		$where_array[] = " province = {$city}";
    	} 
		$where_array[] = " task_type.accept_user_type like '%".$session_user['user_type']."%'";
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

    	$maintasklist = $maintask->join('left join task_type on task_type.id = main_task.task_type_id')->field("main_task.*,task_type.task_type_name")->where($where)->order(" create_time desc ")->select();
    	$this->assign("maintask", $maintasklist);

    	$provinceMdl = M("Province");
    	$provincelist = $provinceMdl->select();
    	$this->assign("province", $provincelist);

    	$this->assign("get_info", I("post."));//选择框的信息
		$this->display('index'); 
    }
	
	public function sendtask(){
        $this->__global();
		$user = session("user");
		if(empty($user) || empty($user['telphone'])){
			//$this->success("请先完善您的个人资料",U("user/modifyUserInfo"),2);
		}

		if($user['usertype'] != 2){
			$this->success("非派单人员不能报修",U('task/index'),2);
			exit;
		}

        $this->display("sendtask");        
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

	public function detail(){
		$this->__global();
		$user = session("user");
		if(empty($user) || empty($user['telphone'])){
			$this->success("请先完善您的个人资料",U("user/modifyUserInfo"),2);
		}
        $id = I("get.id", 1, "intval");
        $taskM = M("Main_task");

        $task = $taskM->join("left join task_type on task_type.id= main_task.task_type_id")->where("main_task.id=".$id)->field("main_task.*,task_type.task_type_name,task_type.accept_user_type")->select();
        $this->assign("task", $task[0]);
        $bidM = M("Bid");
        $bid = $bidM->where("task_id = ".$id." and user_id=".$this->session_uid)->select();
        $hasbid = 0 ;
		if(!empty($task[0]) && !empty($task[0]['accept_user_type'])){
			$accept_user_type = explode(",",$task[0]['accept_user_type']);
			if(!in_array($user['user_type'], $accept_user_type)) $hasbid =1;
		}
        if(!empty($bid[0])){
           $hasbid = 1;
        }   
	$taskM->data(array('id'=>$id,'have_read'=>1))->save();
		$pro_name = M("Province")->where("id=".$task[0]['province'])->select();
		$this->assign("pro_name", $pro_name[0]);
        $this->assign("hasbid", $hasbid);
		$this->display("detail");
	}

    public function selectPartner(){
        $this->__global();
        $get = I("get.");
        if(!isset($get['taskid']) ||empty($get['taskid']) || !isset($get['accept_uid']) || empty($get['accept_uid']) ||!isset($get['send_uid']) ||  empty($get['send_uid']) || $get['send_uid']!= $this->session_uid || $get['accept_uid']==$get['send_uid']){
            $this->redirect("task/index");
        }
        $data['accept_uid'] = $get['accept_uid'];
        $data['confirm_corp'] = time();
        $data['id'] = $get['taskid'];
        $data['uid'] = $get['send_uid'];
        $data['status'] = 1;
        $taskM = M("Main_task");
        $taskM->data($data)->save();
        $task = $taskM->where("id=".$get['taskid'])->select();
		
		$biddata = array("bid_has_read"=>0);
		M("Bid")->where("task_id=".$get['taskid']." and user_id=".$get['accept_uid'])->data($biddata)->save();
        $this->assign("task", $task[0]);
        $this->display("selectPartnerSuccess");
    }    

    public function checkuser(){
        $this->__global();
        $get = I("get.");
        $taskid = $get['task_id'];
        $taskM = M("Main_task");
		if(!isset($get['other'])){
			$user = $taskM->join("left join user on user.id = main_task.accept_uid")->where("main_task.id=".$taskid)->field("user.*")->select();
        }else{
			$user = $taskM->join("left join user on user.id = main_task.uid")->where("main_task.id=".$taskid)->field("user.*")->select();
		}
		$pro_name = M("Province")->where("id=".$taskid)->select();
		$this->assign("pro_name", $pro_name[0]);
		
		$this->assign("user", $user[0]);
        $this->display("checkuser");
    }

    public function selectDanbao(){
        $this->__global();
        $task_id = I("get.task_id",1,"intval");
        $taskM = M("Main_task");
        $task = $taskM->where("id=".$task_id." and uid=".$this->session_uid)->select();
        $this->assign("task", $task[0]);
        $this->display("selectDanbao");
    }
    
    public function danbao_apply(){
        $this->__global();
        $danbaoM = M("Danbao_apply");
        $post = I("post.");
		$list = $danbaoM->where("task_id=".$post['task_id'])->select();
		if(empty($list[0])){
			$post['create_time'] =time();
			$danbaoM->data($post)->add();
		}
        $this->display("selectDanbaoSuccess");
    }

    public function task_fail(){
        $this->__global();
        $message = I("get.message","您的账号余额不足，无法扣除保证金");
        $this->assign("message", $message);
        $this->display("fail");
    }

    public function savetask(){
        $this->__global();
        $post = I("post.");
        $main_task = M("G_order");
        $post['extrademand'] = implode(" ", $post['extrademand']);
        $post['createtime'] = date("Y-m-d H:i:s");
        $post['uid'] = $this->session_uid;
	    $user = session("user");
        $post['requireuser'] = $user['username'];
	    $id = $main_task->data($post)->add();
        $order_code = "DH".date("Ymd").$id;
        $main_task->where("id=".$id)->save(array("ordercode"=>$order_code));
        $this->assign("id", $id);
        $this->display("success");
    }

    public function bidtask(){
        $this->__global();
        $get = I("get.");
        $user = session("user");
        $task_id = $get['id'];

        $bidM =M("Bid");
        $bid = $bidM->where("task_id=".$task_id." and user_id=".$user['id'])->select();
        if(!empty($bid[0])) exit;

        $main_taskM = M("Main_task");
        $main_taskM->where("id=".$task_id)->setInc("bid_nums",1);
	$self_introduce = I("get.self_introduce" ,"");
        $bidM->data(array("task_id"=>$task_id,"user_id"=>$user['id'],"create_time"=>time(),"self_introduce"=>$self_introduce))->add();
        $this->display("bidtask");
    }

    public function opentask(){
        $this->__global();
        $get = I("get.");
        $userid= $get['userid'];
        $taskid = $get['taskid'];
        if($userid != $this->session_uid)exit;
        $taskM = M("Main_task");
        $taskM->data(array('status'=>3,'id'=>$taskid,'admin_pass'=>0))->save();
        $this->redirect('task/index');
    }

    public function closetask(){
        $this->__global();
        $get = I("get.");
        $userid= $get['userid'];
        $taskid = $get['taskid'];
        if($userid != $this->session_uid)exit;
        $taskM = M("Main_task");
        $taskM->data(array('status'=>4,'id'=>$taskid,'admin_pass'=>0,"close_time"=>time()))->save();
    	$list = M("Main_task")->where("id=".$taskid)->select();
    	if(!empty($list[0]) ){
    		$money = 500;
    		if($list[0]['price']) $money = $list[0]['price'];
    		 M("User")->where("id=".$list[0]['uid'])->setInc("money", $money);
            $data = array("uid"=>$list[0]['uid'],"use_type"=>'保证金回退,取消任务id为'.$taskid,"total_money"=>$money,"create_time"=>time(),"taskname"=>$list[0]['pro_name'],"taskcode"=>$list[0]['pro_code'],"taskid"=>$taskid);
            M("Rechargelog")->data($data)->add();
    		
    	}
	
        $this->display('closeSuccess');
    }

    public function confirmtask(){
        $this->__global();
        $get = I("get.");
        $userid= $get['userid'];
        $taskid = $get['taskid'];
        if($userid != $this->session_uid)exit;
        $taskM = M("Main_task");
        $taskM->data(array('status'=>2,'id'=>$taskid,"finish_time"=>time()))->save();
		$taskl = $taskM->where("id=".$taskid)->select();
		$fee = $taskl[0]['price'];
		M("User")->where("id=".$taskl[0]['accept_uid'])->setInc("money", $fee);
		$this->assign("task_id", $taskid);
	//假如fee是0说明是保证金交易的，退还用户500保证金
	if($fee==0 && false){
		M("User")->where("id=".$taskl[0]['uid'])->setInc("money", 500);
	
        $data = array("uid"=>$taskl[0]['uid'],"use_type"=>'保证金回退,任务id为'.$taskid,"total_money"=>500,"create_time"=>time(),"taskname"=>$taskl[0]['pro_name'],"taskcode"=>$taskl[0]['pro_code'],"taskid"=>$taskid);
        M("Rechargelog")->data($data)->add();	
	}else{
		$data = array("uid"=>$taskl[0]['accept_uid'],"use_type"=>'完成任务,任务id为'.$taskid,"total_money"=>$fee,"create_time"=>time(),"taskname"=>$taskl[0]['pro_name'],"taskcode"=>$taskl[0]['pro_code'],"taskid"=>$taskid);
        M("Rechargelog")->data($data)->add();
	}
        $this->display("finishSuccess");
    }
}
