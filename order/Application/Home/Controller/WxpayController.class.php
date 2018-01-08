<?php
/**
 *  PayController.class.php
 * ============================================================================
 * 版权所有 (C) 2015-2016 壹尚科技有限公司，并保留所有权利。
 * 网站地址:   http://www.ethank.com.cn
 * ----------------------------------------------------------------------------
 * 许可声明：这是一个开源程序，未经许可不得将本软件的整体或任何部分用于商业用途及再发布。
 * ============================================================================
 * Author: 勾国印 (gouguoyin@ethank.com.cn) 
 * Date: 2015年8月13日 下午5:13:14  
*/
namespace Home\Controller;
use Think\Controller;
//微信支付类
Vendor('WxPayPubHelper.WxPayPubHelper');
class WxpayController extends Controller {
 
	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	public function index(){
		//判断是否已经授权登录
	    $user_id = session('user');
		$user_id = $user_id['id'];
	    
	    $openid = M('user')->where(array('id' => $user_id))->field('openid')->select();
	    
	    if (!$openid) {
	        $this->ajaxReturn(array('error_msg' => '该用户未授权'));
	    }
	    $openid = $openid[0]['openid'];
	    //获取订单号
	    $price = I('price', 0,"intval");
		if(empty($price)){
			$this->ajaxReturn(array('error_msg' => '参数有误'));
		}

	    $order_info = M('Orders');
		
		$order_id = 'dh'.date("Ymdhis").$user_id;
		$datas = array("user_id"=>$user_id,"price"=>$price,"create_time"=>time(),"pay_status"=>0,"openid"=>$openid,"orderid"=>$order_id);
	    $order_info->data($datas)->add();
	    //使用jsapi接口
	    $jsApi = new \JsApi_pub();
	    
	    //使用统一支付接口，获取prepay_id
	    $unifiedOrder = new \UnifiedOrder_pub();
	    //设置统一支付接口参数
	    //自定义订单号，此处仅作举例
	    $out_trade_no = $order_id;
	    //设置必填参数
	    $total_fee = $price*100;
	    
	    $body = "订单编号{$order_id}";
	    $unifiedOrder->setParameter("openid", "$openid");//用户标识
	    $unifiedOrder->setParameter("body", $body);//商品描述
	    
	    $unifiedOrder->setParameter("out_trade_no", $out_trade_no);//商户订单号
	    $unifiedOrder->setParameter("total_fee", $total_fee);//总金额
	    //$unifiedOrder->setParameter("attach", "order_sn={$res['order_sn']}");//附加数据
	    $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL);//通知地址
	    $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
	    
	    
	    //非必填参数，商户可根据实际情况选填
	    //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
	    //$unifiedOrder->setParameter("device_info","XXXX");//设备号
	    //$unifiedOrder->setParameter("attach","XXXX");//附加数据
	    //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	    //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
	    //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
	    //$unifiedOrder->setParameter("openid","XXXX");//用户标识
	    //$unifiedOrder->setParameter("product_id","XXXX");//商品ID
	    //$prepay_id = $unifiedOrder->getPrepayId();
	    //通过prepay_id获取调起微信支付所需的参数
	    //$jsApi->setPrepayId($prepay_id);
	    $prepay_id = $unifiedOrder->getPrepayId();
	    //通过prepay_id获取调起微信支付所需的参数
	    $jsApi->setPrepayId($prepay_id);
	    $jsApiParameters = $jsApi->getParameters();
	    
	    $wxconf = json_decode($jsApiParameters, true);
	    
	    if ($wxconf['package'] == 'prepay_id=') {
	        $this->ajaxReturn(array('error_msg' => '当前订单存在异常，不能使用支付'));
	    }
	    
	    if ($wxconf['package'] == 'prepay_id=') {
	        //$this->ajaxReturn(array('error_msg' => '当前订单存在异常，不能使用支付'));
	    }
	    if(IS_AJAX){
	        $this->ajaxReturn(array(
	            'status' => 'ok',
	            'wxconf' => $wxconf,
	        ));
	    }
	    
	    $this->display('pay');
	}
	
	//异步通知url，商户根据实际开发过程设定
	public function notify() {
	    //使用通用通知接口
	    $notify = new \Notify_pub();
	    //存储微信的回调
	    $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
	    $notify->saveData($xml);
	    //验证签名，并回应微信。
	    //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	    //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	    //尽可能提高通知的成功率，但微信不保证通知最终能成功。
	    if($notify->checkSign() == FALSE){
	        $notify->setReturnParameter("return_code", "FAIL");//返回状态码
	        $notify->setReturnParameter("return_msg", "签名失败");//返回信息
	    }else{
	        $notify->setReturnParameter("return_code", "SUCCESS");//设置返回码
	    }
	    
	    // 订单号
	    $order_id         = $notify->data['out_trade_no'];
	    // 订单金额
	    $price            = $notify->data['total_fee'];
	    
	    
	    $returnXml = $notify->returnXml();
	    //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
	    //以log文件形式记录回调信息
	    $log_name = "notify_url.log";//log文件路径
	    $this->log_result($log_name, "【接收到的notify通知】:\n".json_encode($notify->data)."\n");
	    $parameter = $notify->xmlToArray($xml);
	    //$this->log_result($log_name, "【接收到的notify通知】:\n".$parameter."\n");
	    if($notify->checkSign() == TRUE){
	        $this->log_result($log_name, "【接收到的notify222通知】:\n\n");
			if ($notify->data["return_code"] == "FAIL") {
	            //此处应该更新一下订单状态，商户自行增删操作
	            //$this->log_result($log_name, "【return_code返回值为fail】:\n".$xml."\n");
	            //更新订单数据【通信出错】设为无效订单
	            
	            // 返回状态码
	            $notify->setReturnParameter("return_code","FAIL");
	            // 返回信息
	            $notify->setReturnParameter("return_msg","签名失败");
	        } else if($notify->data["result_code"] == "FAIL"){
	            //此处应该更新一下订单状态，商户自行增删操作
	            //$this->log_result($log_name, "【result_code返回值为fail】:\n".$xml."\n");
	            //更新订单数据【通信出错】设为无效订单
	            
	            // 返回状态码
	            $notify->setReturnParameter("return_code","FAIL");
	            // 返回信息
	            $notify->setReturnParameter("return_msg","签名失败");
	        } else{
	            //$this->log_result($log_name, "【支付成功】:\n".$xml."\n");
	            $this->log_result($log_name, "【接收sucess】:\n\n");
	            //支付成功后的逻辑操作
	            
	            //订单号
	            $order_id         = $notify->data['out_trade_no'];
	            //微信交易号
	            $transaction_id   = $notify->data['transaction_id'];
	            
	            //openid
	            $openid   = $notify->data['openid'];
	            
	            //交易金额，单位分
	            $price            = $notify->data['total_fee'];
	            
	            //交易完成时间
	            $finish_time      = $notify->data['time_end'];
	            $finish_time      = date('Y-m-d H:i:s', strtotime($finish_time));
	            
	            //验证订单合法性
	            if(!$order_id) {
	                //mail_helper::mail('245629560@qq.com', '付款通知异常(weixin_h5)', 'order_id is empty'.$order_id);
	                exit('缺少必要参数');
	            }
				$this->log_result($log_name, "zuihao:\n\n");
	            $orderinfo = M("Orders");
	            $order_info = $orderinfo->where("orderid='".$order_id."' and price=".intval($price/100))->select();
				$order_info = $order_info[0];
	            if(!$order_info) {
	                //mail_helper::mail('245629560@qq.com', '付款通知异常(weixin_h5)', 'order_info is empty'.$order_id);
	                exit('该订单不存在');
	            }
	            $this->log_result($log_name, "zuihao:\n\n");
	            /* // 订单已取消
	            if($order_info['pay_status'] == 20) {
	                //mail_helper::mail('245629560@qq.com', '付款通知异常(weixin_h5)', '订单已取消，不能再支付'.$order_id);
	                exit('该订单已取消');
	            } */
	            
	            // 已经支付，无需再次支付
	            if($order_info['pay_status'] == 1) {
	                //mail_helper::mail('245629560@qq.com', '付款通知异常(weixin_h5)', '订单'.$order_id.'已经支付，无需再次支付');
	                exit('该订单已经支付');
	            }
	            
	            /* // 不是待支付状态
	            if($order_info['pay_status'] != 1) {
	                //mail_helper::mail('245629560@qq.com', '付款通知异常(weixin_h5)', 'order_status  is not eq 1'.$order_no);
	                exit('该订饭不是待支付状态');
	            } */
	            
	            // 订单被删除
	            if($order_info['status'] == 0) {
	                //mail_helper::mail('245629560@qq.com', '付款通知异常(weixin_h5)', '订单已被删除'.$order_id);
	                exit('该订单已关闭');
	            }
	             
	            
	            // 更新订单状态为已付款，未发货
	            /* if (4 == $order_info['goods_type']) {
	                $pay_status = 15;
	            } else if (5 == $order_info['goods_type']) {
	                $pay_status = 15;
	            } else {
	                $pay_status = 5;
	            } */
				$pay_status = 1;
	            M('orders')->where(array('orderid' => $order_id))->save(array(
	                'pay_status'=> $pay_status,
					'status'=>0,
	                'pay_time'    => $finish_time
	            ));
	            $userM = M("User");
				$userM->where("id=".$order_info['user_id'])->setInc("money",intval($price/100));
				session_start();
				$sessionuser = session("user");
				$sessionuser['money'] +=  intval($price/100);
				session("user",$sessionuser);
	            //减少库存
	            //\order_helper::reduce_stock($order_id);
	            
	            $notify->setReturnParameter("return_code","SUCCESS");
	        }
	    } else {
	        // 返回状态码
	        $notify->setReturnParameter("return_code","FAIL");
	        // 返回信息
	        $notify->setReturnParameter("return_msg","签名失败");
	    }
	}

	
	// 打印log
	public function log_result($file,$word)
	{
	    $fp = fopen($file,"a");
	    flock($fp, LOCK_EX) ;
	    fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
	    flock($fp, LOCK_UN);
	    fclose($fp);
	}

}
?>