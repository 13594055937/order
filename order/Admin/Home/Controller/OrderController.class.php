<?php
namespace Home\Controller;
use Think\Controller;
class OrderController extends GlobalController {
	public function _initialize(){
		$this->_logined();
	}
	
	/**
	 * 全控制器设置
	 * 
	 */
	private function __global(){
		//到场人员列表
		$engineer_list = M("Engineer")->where("status=1")->select();
		$this->assign("engineer_list", $engineer_list);
		//客户列表
		$customer_list = M("Customer")->where("status=1")->select();
		$this->assign("customer_list", $customer_list);
		//医院列表
		$company_list = M("company")->where("status=1")->select();
		$this->assign("company_list", $company_list);
		//问题类型列表
		$job_type_list = M("Jobtype")->where("status=1")->select();
		$this->assign("job_type_list", $job_type_list);
		//所在区域
		$province_list = M("Province")->select();
		$this->assign("province_list", $province_list);
		//试着添加缓存
	}

	public function index(){
    	$this->__global();
	    $userMdl = M('G_order');
	    $get = I("get.");
	    $where_str = $this->getWhereByPost(I('post.'));
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
		$count = $userMdl->where($where_str)->count();
		$Page = new \Think\Page($count,$pageSize);
		$show = $Page->show();
		$this->assign("page", $show);
		$this->assign("data", I('post.'));
		$this->assign("post", I('post.'));


	    $list = $userMdl->join("left join customer on customer.name = g_order.customerId")
			->field("g_order.*,customer.code,customer.province,customer.city,customer.area")
			->where($where_str)->order('id DESC')->limit(($p-1)*$pageSize.','.$pageSize)->select();
	    $this->assign("list", $list);
		$this->display('index'); 
    }

    	//订单详情
   	 public function orderdetail(){
		$this->__global();
	    $get = I("get.");
		$orderM = M('G_order');
		$orderlist = $orderM->where("id=".intval($get['id']))->find();
		$cartM = M("Cart");
		$cartlist = array();
		if(!empty($orderlist)){
			$cartlist = $cartM->join("left join gift_product on gift_product.id=cart.product_id left join gift on gift.id=gift_product.gift_id")->where("cart.id in(".$orderlist['product_id'].")")->field("gift.pic,gift.type,gift.nums,gift.name,gift_product.pname,cart.*")->select();
		}
		$this->assign("detail", $orderlist);
		$this->assign("cartlist", $cartlist);
		$this->display("detail");
	}
	public function del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('G_order');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	public function add(){
		$this->__global();
		$id = I('get.id',0,'intval');

		$userMdl = M("G_order");
		if($id>0){
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('add');
	}

	public function save(){
		$this->__global();
		$post = I("post.");
		$useMdl = M("G_order");
		$status = $post['status'];
		if($status == 4 && (empty($post['arrivetime']) || empty($post['endtime']) || empty($post['accepttime'])  )){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败4！状态值和时间不匹配")));
		}
		if($status == 3 && (empty($post['arrivetime']) || empty($post['endtime']) || empty($post['accepttime'])  )){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败3！状态值和时间不匹配")));
		}
		if($status == 2 && (empty($post['arrivetime'])  || empty($post['accepttime'])  )){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败2！状态值和时间不匹配")));
		}
		if($status == 1 && (empty($post['accepttime'])  )){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败1！状态值和时间不匹配")));
		}

		try {
			if($post['id']<=0){
				$post['ordercode'] = time();
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

	public function getcityinfo(){
		$province = I("get.province","");
		$selectedCity = I("get.city","");
		$cityinfo = parent::getCityInfoByName($province);
		$html = "<option value=''>请选择城市</option>";
		foreach ($cityinfo as $key => $value) {
			$selected = "";
			if($selectedCity!="" && $selectedCity==$value['cityname']){
				$selected = " selected=selected";
			}
			$html .= "<option value='{$value['cityname']}' {$selected}>{$value['cityname']}</option>" ;
		}
		echo $html;
	}

	public function getareainfo(){
		$cityname = I("get.city","");
		$selectedArea = I("get.area","");
		$areainfo = parent::getAreaInfoByName($cityname);
		$html = "<option value=''>请选择城区</option>";
		foreach ($areainfo as $key => $value) {
			$selected = "";
			if($selectedArea!="" && $selectedArea==$value['districtname']){
				$selected = " selected=selected";
			}
			$html .= "<option value='{$value['districtname']}' {$selected}>{$value['districtname']}</option>" ;
		}
		echo $html;
	}

	public function daochu(){
		$userMdl = M("G_order");
		$where_str = $this->getWhereByPost(I('post.'));
		$count = $userMdl->where($where_str)->count();
		$pageSize = C("admin_pageCount");
		$p = ceil($count/$pageSize);
		$file_name = date("Y_m_d")."_order.xls";
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment; filename={$file_name}");
		echo iconv("UTF-8","GBK","工单编号")."\t".iconv("UTF-8","GBK",'公司名称')."\t".iconv("UTF-8","GBK",'问题类型')."\t".iconv("UTF-8","GBK",'覆盖区域')."\t"
			.iconv("UTF-8","GBK","工作说明")."\t".iconv("UTF-8","GBK",'到场人员')."\t".iconv("UTF-8","GBK",'期待时间')."\t".iconv("UTF-8","GBK",'接单时间')."\t"
			.iconv("UTF-8","GBK",'到场时间')."\t".iconv("UTF-8","GBK",'结束时间')."\t".iconv("UTF-8","GBK",'派单人员')."\t"
			.iconv("UTF-8","GBK",'成本')."\t".iconv("UTF-8","GBK",'状态')."\t".iconv("UTF-8","GBK",'备注')."\n";
		for($i=1; $i<=$p;$i++){
			$list = $userMdl->join("left join customer on customer.name = g_order.customerId")
				->where($where_str)->field("ordercode,requireuser,jobtype,concat(customer.province,customer.city,customer.area) as addr,jobcontent,engineername,hopetime,accepttime,arrivetime,endtime,customerId,amount,g_order.status,g_order.memo")->where($where_str)->limit(($i-1)*$pageSize.','.$pageSize)->select();
			foreach ($list as $key=>$v)
			{
				foreach ($v as $k1 => $value) {
					if($k1 == 'status') {
						if($value==1){
							echo iconv("UTF-8","GBK",'已派单')."\t";
						}elseif($value==2){
							echo iconv("UTF-8","GBK",'处理中')."\t";
						}elseif($value==3){
							echo iconv("UTF-8","GBK",'已关闭')."\t";
						}elseif($value==4){
							echo iconv("UTF-8","GBK",'已支付')."\t";
						}else{
							echo iconv("UTF-8","GBK",'申请中')."\t";
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
	public function getWhereByPost($post){
		$where_array = array();
		foreach($post as $field => $val){
			if($val!=="" ){
				if(!in_array($field,array('arrive_start','arrive_end','accept_start','accept_end','end_start','end_end'))){
					$where_array[] = " {$field}='{$val}'";
				}else{
					if(strpos($field,"_start")!==false){
						$where_array[] = str_replace("_start","time",$field).">='{$val}'";
					}
					if(strpos($field,"_end")!==false){
						$where_array[] = str_replace("_end","time",$field)."<='{$val}'";
					}
				}
			}
		}
		return implode(" and ", $where_array);
	}

        public function search(){
		$get = I("get.");
		$name = $get['term'];
		$table = $get['table'];
		$fieldname = $get['field'];
		$query = $name;
		$data = array();
		$datas = M($table)->where($fieldname ." like '%{$name}%'")->field("{$fieldname}")->select();
		if(!empty($datas)){
			foreach($datas as $value){
				$data[] = $value[$fieldname];
			}
		}
		if(empty($data)){
			echo json_encode(array('query'=>'','suggestions'=>array()));exit;
		}
		echo json_encode($data);
		//echo json_encode(array('query'=>$query,'suggestions'=>$data));
	}
}
