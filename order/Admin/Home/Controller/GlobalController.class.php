<?php
namespace Home\Controller;
use Think\Controller;
class GlobalController extends Controller {
	var $user_info;
	var $where_cityid;
	var $citys;
	/**
	 * 验证登陆
	 */
	public function iconv_utf8(&$param){
		if(is_array($param)){
			foreach ($param as $key => $value) {
				$param[$key] = iconv("gb2312","utf-8",$value);
			}
		}
	}

	protected function _logined(){
		$this->assign("front_url", FRONT_URL);
		$this->user_info = session('adminuser');
		if(empty($this->user_info)){
			$this->redirect('login/index');
		}
		if(isset($_POST)){
			//$this->iconv_utf8($_POST);
		}
		if(isset($_GET)){
			//$this->iconv_utf8($_GET);
		}
		$this->assign("usertype_list",array('管理员','工程师','派单人'));
		$this->assign("admin_user", $this->user_info);
		$user_info = $this->user_info;
		$this->assign("manage_list", $user_info['manage_list']);
		
		$path_info = $_SERVER['PATH_INFO'];
    	$path_info = explode("/", $path_info);
    	$this->sonNav = $path_info[1];
    	$this->assign("sonNav",$this->sonNav);
	 	$edit_info = I("get.edit_info","添加信息");
		$this->assign("edit_info",$edit_info);
		//$this->write_operate_log();
	}

	protected function write_operate_log(){
		return;
		$isdel = strpos($_SERVER['PATH_INFO'], "_del");
		$issave = strpos($_SERVER['PATH_INFO'], "_save");
		$type = "";
		$link_address = "";
		if($isdel){
			$type = "del";
			$data = $_GET;
		}

		if($issave){
			$type = 'add';
			$data = $_POST;
			if(isset($data['id']) && $data['id']>0){
				$link_address = substr($_SERVER['PATH_INFO'], 0, $issave)."_add";
				$link_address = "{:U('".$link_address."',array('id'=>".$data['id']."))}";
				$type = 'save';
			}	
		}
		if($type !=""){
			$history_operationMdl = M("History_operation");
			$userinfo = $this->user_info;
			$data['method'] = $_SERVER['PATH_INFO'];
			$content = json_encode($data);
			$data = array("content"=>$content,"operator_name"=>$userinfo['username'],"operate_time"=>date("Y-m-d"),"link_address"=>$link_address,"type"=>$type);
			$history_operationMdl->data($data)->add();
		}
	}

	public function getWhere($where = array()){
		if(!empty($where)){
			$wheres = array();
			$get = I("get.");
			foreach ($where as $value) {
				if(isset($get[$value]) &&(!empty($get[$value]) || $value == 'status')){
					$wheres[] = " ".$value ."='".$get[$value]."'";
				}
			}

			if(!empty($wheres)){
				return implode(" and ", $wheres);
			}
		}
		return "";
	}

	public function getCityInfo($provinceId){
		$cityM = M("City");
		$citylist = $cityM->where("provinceid=".$provinceId)->select();
		return $citylist;
	}

	public function getCityInfoByName($province){
		$cityM = M("City");
		$arealist = $cityM->join("left join province on city.provinceid=province.id")->where("name='".$province."'")->select();
		return $arealist;
	}

	public function getAreaInfo($cityid){
		$cityM = M("District");
		$arealist = $cityM->where("cityid=".$cityid)->select();
		return $arealist;
	}

	public function getAreaInfoByName($city){
		$cityM = M("District");
		$arealist = $cityM->join("left join city on city.id=district.cityid")->where("cityname='".$city."'")->select();
		return $arealist;
	}

	public function getWhereByPost($post){
		$where_array = array();
		foreach($post as $field => $val){
			if($val!==""){
				$where_array[] = " {$field} like '%{$val}%'";
			}
		}
		return implode(" and ", $where_array);
	}

	public function getMd5Pwd($name,$pwd){
		return md5($name.$pwd."~!@");
	}
}
