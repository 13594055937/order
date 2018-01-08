<?php
namespace Home\Controller;
use Think\Controller;
class ProductController extends GlobalController {
	function _initialize(){
		$this->_logined();
		$path_info = $_SERVER['PATH_INFO'];
                $path_info = explode("/", $path_info);

                if(strpos($path_info[1],"codebuy_") !== false){
                        $id = explode("_",$path_info[1]);
                        $_GET['id'] = $id[1];
                        $this->codebuy();
			exit;
                }
	}
	
	/**
	 * 全控制器设置
	 * 
	 */
	 private function __global(){
	 	$this->assign("sonNav","好蟹汇-扫码购买");
	 }
	/**
	 * 
	 * eagle_91@sina.com
	 * created 2014-09-10
	 */
    public function hxh(){
    	$this->__global();
    	$this->getLikaLihe();

	$this->assign("curclass","hxh");
    	$this->assign("sonNav","好蟹汇-礼卡");
		$this->display('hxh'); 
    }

    public function customization(){//定制
        $this->__global();

        
        $this->assign("sonNav","好蟹汇-企业定制");
        $this->display('custom'); 
    }

    public function putcrab(){//提蟹
        $this->__global();
        $get = I("get.");
        $step = isset($get['step']) ? intval($get['step']) : 1;
        $this->assign("step", $step);

        $this->assign("sonNav","好蟹汇-提蟹");
	$this->assign("curclass","putcrab");
        $this->display('system'); 
    }

    public function getCardMessage(){
        $post = I("post.");
	if(!is_mobile()){
        $yzm = $post['yzm'];
        $Verify = $this->getVerify();
        if($Verify->check($yzm,10000) == false){
            echo json_encode(array("status"=>1,"message"=>"图形验证码不正确"));
            exit;
        }}
        $suggestM = M("Xie_card");
        $card_info = $suggestM->where("card_no = '".$post['card_no']."' and card_pwd ='".$post['card_pwd']."' and isUsed=0 ")->select();
        if(empty($card_info[0])){
            echo json_encode(array("status"=>1,"message"=>"不存在的卡号或密码"));
            exit;
        }
	if($card_info[0]['status'] !=1){
	    echo json_encode(array("status"=>2,"message"=>"您的卡号异常,请联系客服电话<a href='tel:400-1383-777'>400-1383-777</a>"));
            exit;
	}
        //是否提交过申请提货或预约自提
        $card_applyM = M("Card_apply");
        $card_apply = $card_applyM->where("xie_card_id = ".$card_info[0]['id'])->select();
        if(!empty($card_apply)){
            if($card_apply[0]['is_subscribe'] == 0) {
                echo json_encode(array("status"=>1,"message"=>"已申请过提货，请耐心等待"));exit;
            }else{
                echo json_encode(array("status"=>1,"message"=>"已预约自提"));exit;
            }
        }
        echo json_encode(array("status"=>0,"message"=>"", "data"=>$card_info[0]));
    }

    public function saveApply(){
        $this->__global();
        $post = I("post.");
        $suggestM = M("Xie_card");
        $cardinfo = $suggestM->where("id=".$post['xie_card_id']." and isUsed= 0 and status=1")->find();
        if(empty($cardinfo)){
            echo json_encode(array("status"=>1,"message"=>"没有该卡号信息"));
            exit;
        }
        $card_applyM = M("Card_apply");
        $oldpost = $post;
        $oldpost['apply_time'] = date("Y-m-d");
        $post['apply_time'] = time();
        $post['apply_channel'] = "网上";
        if(isset($post['fahuo_time'])){
            $post['fahuo_time'] = strtotime($post['fahuo_time']);
        }
        if(isset($post['address'])){
            $post['address'] =  $post['s_province'] . $post['s_city'] .$post['s_county'].$post['address'];
        }
        $card_applyM->data($post)->add();
        echo json_encode(array("status"=>0,"message"=>"成功","data"=>$oldpost));
    }

    public function codebuy(){ //扫码支付
    	$this->__global();
    	$id = I("get.id", 1,"intval");
        $detail = M("Gift_product")->join("left join gift on gift.id=gift_product.gift_id")->where("gift_product.id=".$id)->field("gift.type,gift.name ,gift.nums,gift.pic,gift_product.*")->select();
        $this->assign("product_detail", $detail[0]);
        $this->getLikaLihe();
        //公司宣传视频
        $video = M("Videos")->limit(1)->select();
        if(empty($video[0])){
            $video[0]['video_url'] = "dzx.mp4";
            $video[0]['small_pic'] = "video-3.jpg";
        }
        $this->assign("videos", $video[0]);
	   $this->display('codebuy'); 
    }

    public function getLikaLihe(){
        $this->assign("type","礼盒");
        $giftPM = M("Gift_product");
        $giftM = M("Gift");
        $gift = $giftM->order("nums desc,type desc")->select();

        $giftp = $giftPM->order("pname asc")->select();
        $this->assign("gift", $gift);
        $this->assign("giftp", $giftp);
    }

   
    public function getAjaxStoreInfo(){
        $storeM = M("Store");
        $storeInfo = array();
        $store = $storeM->select();
        foreach($store as $value){
            $storeInfo[] = $value['name'];
        }
        echo json_encode($storeInfo);
    }

    public function tixie(){//提蟹
        $this->__global();
        $get = I("get.");
        $step = isset($get['step']) ? intval($get['step']) : '';
        $this->assign("step", $step);
        $this->assign("sonNav","好蟹汇-提蟹");
        $this->display('system'.$step); 
    }

    public function product_list(){//提蟹
        $this->__global();
        $get = I("get.");
        $type = isset($_GET['type']) ? $_GET['type'] : '礼卡';
	$showall = isset($get['show_all']) ? intval($get['show_all']) : 1;
	$giftPM = M("Gift_product");
	$this->assign("type", $type);
	$limit = 5 ;


	$sale_nums_order = isset($_GET['sale_nums_order']) ? $_GET['sale_nums_order'] : "asc";
	$price_order = isset($_GET['price_order']) ? $_GET['price_order'] : "asc ";
	
	$this->assign("sale_nums_order", $sale_nums_order);
	$this->assign("price_order", $price_order);
	if($showall){
	  $limit = 500;
	}
	$guige = isset($_GET['guige']) ? $_GET['guige'] : '';
	$name =  '规格';
	if(empty($get['sale_nums_order'])){
                        $order = "price ".$price_order;
                }else{
                        $order = "sale_nums ". $sale_nums_order;
                }
	if(!empty($guige) && $guige!="1_1"){
		$new_gg = explode("_", $guige);
		$types = $new_gg[0];
		$nums = $new_gg[1];
		$commend_card = $giftPM->join("left join gift on gift.id=gift_product.gift_id")->where("gift.type='".$types."' and gift.nums=".intval($nums))->limit($limit)->field("gift_product.*,gift.name,gift.nums,gift.type,gift.guige,gift.pic")->order($order)->select();
		$name=$_GET['name'];
	}else{
		$where_new = "1=1";
		if($type!='sale_nums'){
			$where_new = "gift.type ='".$type."'";
		}
        	$commend_card = $giftPM->join("left join gift on gift.id=gift_product.gift_id")->where($where_new)->limit($limit)->field("gift_product.*,gift.name,gift.nums,gift.type,gift.guige,gift.pic")->order($order)->select();
        }
	$this->assign("name", $name);
	$this->assign("guige", $guige);
	$this->assign("commend_card", $commend_card);
	$this->assign("show_all", $showall);
        $this->assign("sonNav","好蟹汇-产品列表");
        $this->display('list');
    }
    public function system4(){
	$this->display("system4");
	}    



}
