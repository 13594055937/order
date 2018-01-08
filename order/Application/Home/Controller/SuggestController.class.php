<?php
namespace Home\Controller;
use Think\Controller;
class SuggestController extends GlobalController {
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

    public function index(){
        $this->__global();
        $type = I("get.type", "交易举报");
        $weiquan = M("Weiquan_type");
        $weiquan_type = $weiquan->where("status=1")->select();
        $this->assign("weiquan", $weiquan_type);
        $this->assign("type", $type);
        $suggestM = M("Suggest");
		$usersession = session("user");
		if($type!='投诉建议'){
			if($type =="交易举报"){
				$list = $suggestM->join("left join main_task on suggest.task_id=main_task.id")->where("suggest_type='交易举报'")->field("suggest.*,main_task.pro_name,main_task.pro_code")->order("suggest.id desc")->select();
			}else{
				if(isset($_GET['record']) ){
					$list = $suggestM->join("left join main_task on main_task.id=suggest.task_id")->where("suggest_type='交易维权' and user_id=".$usersession['id'])->field("suggest.*,main_task.pro_name,main_task.pro_code")->order(" suggest.id desc")->select();
					$this->assign("record", $_GET['record']);
				}else{
					$list = M("Main_task")->where("(main_task.uid= ".$usersession['id']." or main_task.accept_uid =".$usersession['id'].") and main_task.status=1 and id not in(select task_id from suggest where suggest_type='交易维权' and user_id=".$usersession['id'].")")->order(" id desc")->select();
				}
			}
		}else{
			$list = $suggestM->where("suggest_type='投诉建议'")->order(" id desc")->select();
		}
		$this->assign("list", $list);
        $this->display('index'); 
    }
    
    public function tosuggest(){
        $this->__global();
        $type = I("get.type", "投诉建议");
        $weiquan = M("Weiquan_type");
        $weiquan_type = $weiquan->where("status=1")->select();
        $this->assign("weiquan", $weiquan_type);
        $this->assign("type", $type);
        $suggestM = M("Main_task");
        $id = I("get.taskid",0,"intval");
        if($id){
            $list = $suggestM->join("left join task_type on task_type.id=main_task.task_type_id")->field("main_task.*,task_type.task_type_name")->where("main_task.id ='{$id}'")->select();
            $this->assign("list", $list[0]);
        }
        $this->assign("id", $id);
        $this->display('tosuggest'); 
    }
    public function detail(){
        $this->__global();
        $id = I("get.id", 1, "intval");
        $suggestM = M("Suggest");
        $list = $suggestM->join("left join weiquan_type on weiquan_type.id=suggest.weiquan_type")->where("suggest.id=".$id)->field("suggest.*,weiquan_type.name")->select();
        if(!empty($list[0]) && $list[0]['task_id']>0){
            $main_taskM = M("Main_task");
            $mainlist = $main_taskM->where("id=".$list[0]['task_id'])->select();
            $this->assign("maintask", $mainlist[0]);
        }
        $this->assign("suggest", $list[0]);
        $this->display("detail");
    }

    public function savesuggest(){
        $this->__global();
        $post = I('post.');
        if(isset($post['pro_name']) && !empty($post['pro_name'])&&isset($post['pro_name']) && !empty($post['pro_name'])){
            $taskM = M("Main_task");
            $task = $taskM->where("pro_name ='".$post['pro_name']."' or pro_code='".$post['pro_code']."'")->select();
            if(!empty($task[0])){
                $post['task_id'] = $task[0]['id'];
            }
        }
        if($post['suggest_type'] !='投诉建议' && $post['task_id']==0){
            $this->error("任务不存在!", "index");
            exit;
        }
        $useMdl = M('Suggest');
        $userinfo = session("user");
        $post['user_id'] = $userinfo['id'];
        $post['suggest_time'] = time();
        $return = $useMdl->data($post)->add();
        $this->assign("suggest_type", $post['suggest_type']);
        $this->display("success");    
    }
    
	public function deal(){
        $this->__global();
        $get = I('get.');
        $useMdl = M('Suggest');
        $userinfo = session("user");
        $post['status'] = 2;
        $return = $useMdl->where("id=".$get['suggest_id']." and user_id=".$userinfo['id'])->data($post)->save();
		$this->assign("type",$get['type']);
        $this->display("dealsuccess");    
    }
}
