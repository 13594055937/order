<?php
namespace Home\Controller;
use Think\Controller;
class HospitalController extends GlobalController {
	public function _initialize(){
		$this->_logined();
	}
	
	/**
	 * 全控制器设置
	 * 
	 */
	private function __global(){
	 	$this->topNav = "hospital";
		//所在区域
		$province_list = M("Province")->select();
		$this->assign("province_list", $province_list);
	 }

	public function index(){
    	$this->__global();
	    $userMdl = M("Hospital");
		$get = I("get.");
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
		$where_str = parent::getWhereByPost(I('post.'));
		$count = $userMdl->where($where_str)->count();
		$Page = new \Think\Page($count,$pageSize);
		$show = $Page->show();

	    $list = $userMdl->where($where_str)->order('id DESC')->limit(($p-1)*$pageSize.','.$pageSize)->select();
	    $this->assign("list", $list);
		$this->assign("page", $show);
		$this->assign("data", I('post.'));
		$this->assign("post", I('post.'));
		$this->display('index'); 
    }
	
	public function add(){
		$this->__global();
		$id = I('get.id',0,'intval');

		$userMdl = M("Hospital");
		if($id>0){
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('add');
	}

	public function save(){
		$this->__global();
		$post = I("post.");
		$useMdl = M("Hospital");
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

	public function del(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M("Hospital");
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	public function daochu(){
		$userMdl = M("Hospital");
		$where_str = parent::getWhereByPost(I('post.'));
		$count = $userMdl->where($where_str)->count();
		$pageSize = C("admin_pageCount");
		$p = ceil($count/$pageSize);
		$file_name = date("Y_m_d")."_hospital.xls";
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment; filename={$file_name}");
		echo iconv("UTF-8","GBK",'医院编号')."\t".iconv("UTF-8","GBK",'医院名称')."\t".iconv("UTF-8","GBK",'地区')."\t".iconv("UTF-8","GBK",'医院等级')."\t".iconv("UTF-8","GBK",'门诊数')."\t".iconv("UTF-8","GBK",'床位数')."\t".iconv("UTF-8","GBK",'医院联系人')."\t".iconv("UTF-8","GBK",'联系电话')."\t".iconv("UTF-8","GBK",'状态')."\t".iconv("UTF-8","GBK",'备注')."\n";
		for($i=1; $i<=$p;$i++){
			$list = $userMdl->where($where_str)->field("code,name,concat(province,city,area) as addr,grade,menzhennum,bednum,contactname,contacttel,status,memo")->where($where_str)->limit(($i-1)*$pageSize.','.$pageSize)->select();
			foreach ($list as $key=>$v)
			{
				foreach ($v as $k1 => $value) {
					if($k1 == 'status') {
						if($value==1){
							echo iconv("UTF-8","GBK",'已上线')."\t";
						}else{
							echo iconv("UTF-8","GBK",'待施工')."\t";
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
