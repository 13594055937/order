<?php
namespace Home\Controller;
use Think\Controller;
class EngineersController extends GlobalController {
	public function _initialize(){
		$this->_logined();
	}
	
	/**
	 * 全控制器设置
	 * 
	 */
	private function __global(){
	 	$this->assign("get", $_GET);
	 	$get = I("get.");

		//人员分类
		$rate_list = M("Engineerrate")->where("status=1")->select();
		$this->assign("engineerrate_list", $rate_list);
		//人员评级
		$type_list = M("Engineertype")->where("status=1")->select();
		$this->assign("engineertype_list", $type_list);
		//所在区域
		$province_list = M("Province")->select();
		$this->assign("province_list", $province_list);
	}

	public function index(){
    	$this->__global();
	    $userMdl = M('Engineer');
	    $get = I("get.");
	    $p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $where_str = parent::getWhereByPost(I('post.'));
	    $count = $userMdl->where($where_str)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show(); 
	    $this->assign('page',$show);// 赋值分页输出
		$this->assign("data", I('post.'));
		$this->assign("post", I('post.'));
	    $list = $userMdl->where($where_str)->order('id DESC')->limit(($p-1)*$pageSize.','.$pageSize)->select();
	    $this->assign("list", $list);

		$this->display('index');
    }

    public function add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Engineer');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('add');
	}

	public function save(){
		$post = I('post.');
		$useMdl = M('Engineer');
		try {
			if($post['id']<=0){
				$post['inserttime'] = time();
				$data = $post;
				$return = $useMdl->data($data)->add();
			}else{
				$data = $post;
				$return = $useMdl->save($data);
			}
		}catch( \Exception $e){
			exit(json_encode(array("status"=>"false","msg"=>$e->getMessage())));
		}
		exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
	}

	public function getWhere($where = array()){
		if(!empty($where)){
			$get = I("get.");
			foreach ($where as $value) {
				if(!empty($get[$value])){
					$wheres[] = " ".$value ."='".$get[$value]."'";
				}
			}

			if(!empty($wheres)){
				return implode(" and ", $wheres);
			}
		}
		return "";
	}

	public function del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Engineer');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	public function daochu(){
		$userMdl = M("Engineer");
		$where_str = parent::getWhereByPost(I('post.'));
		$count = $userMdl->where($where_str)->count();
		$pageSize = C("admin_pageCount");
		$p = ceil($count/$pageSize);
		$file_name = date("Y_m_d")."_engineer.xls";
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment; filename={$file_name}");
		echo iconv("UTF-8","GBK",'人员编号')."\t".iconv("UTF-8","GBK",'姓名')."\t".iconv("UTF-8","GBK",'联系方式')."\t"
			.iconv("UTF-8","GBK",'微信号')."\t".iconv("UTF-8","GBK",'覆盖区域')."\t".iconv("UTF-8","GBK",'标准成本')."\t"
			.iconv("UTF-8","GBK",'状态')."\t".iconv("UTF-8","GBK",'备注')."\n";
		for($i=1; $i<=$p;$i++){
			$list = $userMdl->where($where_str)->field("engineerCode,engineerName,mobile,weixin,concat(province,city,area) as addr,stardfee,status,memo")->where($where_str)->limit(($i-1)*$pageSize.','.$pageSize)->select();
			foreach ($list as $key=>$v)
			{
				foreach ($v as $k1 => $value) {
					if($k1 == 'status') {
						if($value==1){
							echo iconv("UTF-8","GBK",'可服务')."\t";
						}else{
							echo iconv("UTF-8","GBK",'不可服务')."\t";
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
