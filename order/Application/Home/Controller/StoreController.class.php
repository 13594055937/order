<?php
namespace Home\Controller;
use Think\Controller;
class StoreController extends GlobalController {
	function _initialize(){
		$this->_logined();
	}
	

    /**
     * 全控制器设置
     * 
     */
    private function __global(){
        $this->assign("sonNav","好蟹汇-全国门店");
    }

    public function storemap(){
        $this->__global();
        $storeM = M("Store");
        $storelist = $storeM->order("id asc")->select();
	$st = array();
	foreach($storelist as $k=>$v){
		$st[$k]['AreaName'] = str_replace("市","",str_replace("省","",$v['province_name']));	
		$st[$k]['ShopName']= $v['name'];
		$st[$k]['TelPone'] = $v['contact_tel'];
		$st[$k]['Fax'] = $v['contact400'];
		$st[$k]['Adress'] = $v['address'];
		$st[$k]['tel400']= $v['contact400'];
		$st[$k]['guding'] = $v['telphone'];
		$st[$k]['link'] = $v['contact_name'];
		
	}
        $this->assign("storelist2", json_encode($st));
        $giftM = M("Gift");
        $gift = $giftM->order("type asc ,nums desc")->select();
        $this->assign("gift", $gift);
        $showall = isset($_GET['showall']) ? 1 : 0;
        $this->assign("showall", $showall);
        $this->display('storemap'); 
    }
    
    public function contacttus(){
        $this->__global();
        
        $this->assign("sonNav","好蟹汇-门店分布地址");
        $this->display('contacttus'); 
    }

    public function ychdzx(){
        $this->__global();
        $get = I("get.");
        $type = isset($get['type']) ? $get['type'] : "礼卡";
        $this->assign("type", $type);
        $giftPM = M("Gift_product");
        $giftM = M("Gift");
        $gift = $giftM->order("nums desc,type desc")->select();

        $giftp = $giftPM->order("pname asc")->select();
        $this->assign("gift", $gift);
        $this->assign("giftp", $giftp);

        $this->assign("sonNav","好蟹汇-阳澄湖大闸蟹");
	$this->assign("curclass","ychdzx");
        $this->display("ychdzx");
    }

    public function enterpriseCustome(){
        $this->__global();
        
        $this->assign("sonNav","好蟹汇-企业定制");
        $this->display('enterpriseCustome'); 
    }
    
    public function custom(){
        $this->__global();
        
        $this->assign("sonNav","好蟹汇-个人定制");
        $this->display('privateCustom'); 
    }
}
