<?php
namespace Home\Controller;
use Think\Controller;
class AboutusController extends GlobalController {
	function _initialize(){
		$this->_logined();
		$path_info = $_SERVER['PATH_INFO'];
                $path_info = explode("/", $path_info);
                if(strpos($path_info[1],"help_") !== false){
                        $id = explode("_",$path_info[1]);
                        $_GET['id'] = $id[1];
			$this->help();
                        exit;
                }
	}
	

    /**
     * 全控制器设置
     * 
     */
    private function __global(){
        $this->assign("sonNav","好蟹汇-关于我们");
    }

    public function aboutus(){
        $this->__global();
        $this->display('about_us'); 
    }

    public function honor(){
        $this->__global();

        $this->assign("sonNav","聚焦好蟹汇");
        $this->display('8btn'); 
    }

    public function help(){
        $this->__global();
        $type = isset($_GET['type']) ? $_GET['type'] : 'help'; 
	$id = intval($_GET['id']);
	$detail = M("Help_center")->where("id=".$id)->find();
	$this->assign("detail", $detail);
	$this->assign("id", $id);
        $this->assign("sonNav","好蟹汇-帮助中心");
        $this->display("help"); 
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
    
}
