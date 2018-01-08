<?php
namespace Home\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
use Com\JSSDK;
class GlobalController extends Controller {
	/**
	 * 验证登陆
	 */
    var $left_main_nav ;
    var $middle_main_nav;
    var $baseurl;
    var $current_city_info ;
    var $base_config;
	var $session_uid;
    public function getmd5($str){
        $str = md5(mb_convert_encoding($str , 'UTF-16LE' , 'UTF-8'));
        $len = strlen($str);
        $md5_str = "";
        for($i=0;$i<$len;$i++){
            if($i%2 ==0 && $str[$i] == '0'){

            }else{
                $md5_str = $md5_str.$str[$i];
            }
        }
        return $md5_str;
    }
	
	/**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    public function ind($id = ''){
		if(isset($_GET['code']) && !empty($_GET['code'])){
			session_start();
			$appid = C("appid");
			$appsecret = C("appSecret");
			$init_code = isset($_GET['code']) ? $_GET['code'] : "";
			if( !isset($_SESSION[$init_code.'openid']) || empty($_SESSION[$init_code.'openid'])){
				$code = $_GET['code'];
				$token = session("token");
				if(!$token){
					$auth  = new WechatAuth($appid, $appsecret);
					$token = $auth->getAccessToken('code', $code);
					session("token", $token);
				}
				$openid = $token['openid'];
				$_SESSION[$init_code.'openid'] = $openid;
			}
			$openid = $_SESSION[$init_code.'openid'];
			$info = $this->addUser($openid);
            session("user", $info);
		}
        else{
			//调试
			try{
				$appid = C("appid"); //AppID(应用ID)
				$token = 'hongbao'; //微信后台填写的TOKEN
				$crypt = 'tGCnZZqOMnhEHAtLKdebfk7OyoWZmuLT88Yf1lm0SRu';
				/* 加载微信SDK */
				$wechat = new Wechat($token, $appid, $crypt); 
				echo 99;
				/* 获取请求信息 */
				$data = $wechat->request();
				if($data && is_array($data)){
					$this->demo($wechat, $data);
				}
			} catch(\Exception $e){
				echo 1233;
				file_put_contents('./error.json', json_encode($e->getMessage()));
			}
		}
        
    }

    /**
     * DEMO
     * @param  Object $wechat Wechat对象
     * @param  array  $data   接受到微信推送的消息
     */
    private function demo($wechat, $data){
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        //add users
                        $a = $this->addUser($data['FromUserName']);
                        session("user", $a);
                        $wechat->replyText('亲：'.$a['nick']."我等你很久了！");
                        break;

                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;

                    default:
                        $wechat->replyText("亲，我等你很久了！\n正尔科技|工业电气平台为您分享项目信息，行业动态、技术知识和正能量好文!");
                        break;
                }
                break;

            case Wechat::MSG_TYPE_TEXT:
                switch ($data['Content']) {
                    case '文本':
                        $wechat->replyText('亲，我等你很久了！\n正尔科技|工业电气平台为您分享项目信息，行业动态、技术知识和正能量好文!暂时还没有查到任何信息哦！');
                        break;

                    case '图片':
                        $media_id = '1J03FqvqN_jWX6xe8F-VJr7QHVTQsJBS6x4uwKuzyLE';
                        $wechat->replyImage($media_id);
                        break;
                    default:
                        $info = $this->addUser($data['FromUserName']);
                        session("user", $info);
                        //$url = "";//"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx19445c99bf8500a4&redirect_uri=http%3A%2F%2Fvpbong.cn%2Findex.php%2FHome%2Findex%2FshareHongbao.html&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
                        $wechat->replyText($data['FromUserName']."欢迎访问公众平台2！内容在开发中");
                        break;
                }
                break;
            
            default:
                # code...
                break;
        }
    }

    public function addUser($openid='', $last_openid=""){
        $mdl = M("User");
        $list = $mdl->where("openid='{$openid}'")->select();
        file_put_contents("a.log", date("Y-m-d H:i:s")."openid='{$openid}'......\n", FILE_APPEND);
        if(!empty($list[0]) && !empty($list[0]['nick'])) return $list[0];
        file_put_contents("a.log", date("Y-m-d H:i:s")."......\n", FILE_APPEND);
        $appid = C("appid");
        $appsecret = C("appSecret");
        $WechatAuth = new WechatAuth($appid, $appsecret);
        $userinfo = $WechatAuth->userInfo($openid);
        if(isset($userinfo['openid']) && !empty($userinfo['openid'])){
           $data = array("openid"=>$openid,
                  'username'=>$userinfo['nickname'],
                  'headimgurl'=>$userinfo['headimgurl'],
                  'createtime'=>date("Y-m-d H:i:s"),
                  "nick"=>$userinfo['nickname'],
                  "userpwd"=>$this->getMd5Pwd($userinfo['nickname'],C('init_pwd'))
                );
            $mdl->data($data)->add();  
            file_put_contents("a.log", date("Y-m-d H:i:s")."///////\n", FILE_APPEND);
       }
       $list = $mdl->where("openid='{$openid}'")->select();  
       return $list[0];
    }

    
    private function create_noncestr($length = 32) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    private function formatQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v){
            if (null != $v && "null" != $v && "sign" != $k) {
                if($urlencode){
                   $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar ="";
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    //数组转XML
    private function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    private function curl_post_ssl($url, $vars, $second=30)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

        curl_setopt($ch,CURLOPT_SSLCERT,'./apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLKEY,'./apiclient_key.pem');
        curl_setopt($ch,CURLOPT_CAINFO,'./rootca.pem');

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $vars);
        $data = curl_exec($ch);
        file_put_contents('./data_ling_'.date("Y_m_d").'.json', json_encode($data),FILE_APPEND);
        if($data){
            curl_close($ch);
            return $data;
        }else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }

    private function addSendLog($openId, $money, $from_openid=0) {
        $data = array(
            'openid' => $openId,
            'hongbao' => $money,
            'get_gift_time' => time(),
            'type'=>$from_openid
        );
        $m = M("Hongbao_log");
        $m->data($data)->add();
    }
    
    private function getOrderId() {
        $data = array(
            'add_time' => time(),
        );
        $m = M("Order_ids");
        $id = $m->data($data)->add();
        $str = '000000000'. $id;
        return substr($str, -10);
    }
	
	protected function _logined(){
        $path_info = $_SERVER['PATH_INFO'];
    	$path_info = explode("/", $path_info);
    	$sonNav = $path_info[1];
    	$this->assign('sonNav',$sonNav);
        if(isset($_GET['mobile'])&&isset($_GET['debug'])){
            $user = M("User")->where("mobile='".$_GET['mobile']."'")->select();
            session("user", $user[0]);
        }
		$userInfo = session('user');
		if(!empty($userInfo) && !empty($userInfo['id'])){
			$this->assign("userinfo", $userInfo);
			$this->session_uid = $userInfo['id'];
            if(!empty($sonNav)&&!in_array($sonNav, array("modifyUserInfo","verify","sendsms","getcityinfo","index","go_index")) && empty($userInfo['telphone'])){
				//$this->success("请先完善您的个人资料",U("user/modifyUserInfo"),2);
				//exit;
            }
            /**
            if(empty($userInfo) && !in_array($sonNav, array("register","login","go_login","sendsms","reset_register","edit_pwd"))){
        		if($sonNav == "dianzan" || $sonNav == "shoucang"){
        			echo "login";exit;
        		}
                $this->redirect("user/login");
            }*/
        }

        $configs = array("baozhengjin"=>500);
        $this->assign("config", $configs);
	}

    public function getCityInfo($provinceId){
        $cityM = M("City");
        $citylist = $cityM->where("provinceid=".$provinceId)->select();
        return $citylist;
    }

    public function getIPLoc_sina($queryIP){    
        $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$queryIP;    
        $ch = curl_init($url);     
        curl_setopt($ch,CURLOPT_ENCODING ,'utf8');     
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
        $location = curl_exec($ch);    
        $location = json_decode($location);    
        curl_close($ch);         
        $loc = "";   
        if($location===FALSE) return "";     
        if (empty($location->desc)) {    
            $loc = $location->city;//.$location->city.$location->district.$location->isp;  
        }else{         
            $loc = $location->desc;    
        }    
        return $loc;
    }

    public function GetIP(){
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
        else
        $ip = "unknown";
        return($ip);
    }
    
public function getMd5Pwd($name,$pwd){
		return md5($name.$pwd."~!@");
	}
}
