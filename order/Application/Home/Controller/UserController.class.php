<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends GlobalController {
	function _initialize(){
		$this->_logined();
	}

	public function initUser(){
		$userinfo = session('user');
		if(empty($userinfo) || empty($userinfo['id'])){
			$this->redirect("user/login");
		}
	}
	/**
	 * 全控制器设置
	 * 
	 */
	 private function __global(){
	 	$this->topNav = "user";
	 }

	 public function member(){
	 	$this->__global();

		$userinfo = session("user");
		$id = $userinfo['id'];
	 	//计算各个板块的数量
	 	$photoshanghaiMdl = M("Baoming_order");
	 	$shanghai_nums = $photoshanghaiMdl->where("user_id=".$id)->count();
	 	$this->assign("shanghai_nums", $shanghai_nums);

	 	$shalongMdl = M("Subscribe_shalong");
	 	$shalong_nums = $shalongMdl->where("user_id=".$id)->count();
	 	$this->assign("shalong_nums", $shalong_nums);

	 	$user_fabu_huodongMdl = M("User_fabu_huodong");
	 	$fabu_nums = $user_fabu_huodongMdl->where("user_id=".$id)->count();
	 	$this->assign("fabu_nums", $fabu_nums);

	 	$postMdl = M("Topic");
	 	$topic_nums = $postMdl->where("userid=".$id)->count();
	 	$this->assign("topic_nums", $topic_nums);

	 	$user_zuopingMdl = M("User_zuoping");
	 	$user_zuoping_nums = $user_zuopingMdl->where("user_id=".$id)->count();
	 	$this->assign("user_zuoping_nums", $user_zuoping_nums);

	 	$study_spaceMdl = M("Shoucang");
	 	$study_nums = $study_spaceMdl->where("user_id=".$id)->count();
	 	$this->assign("study_nums", $study_nums);

	 	$gonggaoMdl = M("Gonggao");
	 	$gonggao_list = $gonggaoMdl->order("id desc")->limit(10)->select();
	 	$this->assign("gonggao_list", $gonggao_list);

		$p = I('get.p',1,'intval');
		$pageSize = 5;//C('admin_pageCount');
	    $userMdl = M('Baoming_order');
	    $where = "user_id=".$userinfo['id'];
	    $count = $userMdl->join("left join huodong_pici as pic on pic.id=baoming_order.pici_id left join huodong_baoming as hb on hb.id=pic.huodong_id")
	    ->where($where)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->join("left join huodong_pici as pic on pic.id=baoming_order.pici_id left join huodong_baoming as hb on hb.id=pic.huodong_id")
	    ->where($where)->field("baoming_order.*,pic.huodong_id,pic.pici_name,hb.huodong_name,hb.huodong_title,hb.small_pic")->order("baoming_order.id desc")->limit(($p-1)*$pageSize.','.$pageSize)->select();
	    $this->assign('list',$list);// 赋值数据集

	    $this->assign('page',$show);
	 	$this->display('member');
	 }

	 public function about(){//关于野驴
	 	$this->display('about');
	 }

	 public function contact(){//联系野驴
	 	$this->display('contact');
	 }

	 public function mem_news(){

	 	$this->__global();

		$p = I("get.p", 1, "intval");
	 	$m = M("User_message");
		$userinfo = session("user");
	    $where = "user_id=".$userinfo['id'];
	    $count = $m->where($where)->count();
	    $pageSize = 20;
	    $Page = new \Think\Page($count,$pageSize);
	    $show1 = $Page->show();
	    $list = $m->where($where)->order("id desc")->limit(($p-1)*$pageSize.','.$pageSize)->select();
	    $datas = array("user_id"=>$userinfo['id'],"status"=>1);
	    $m->where($where)->data($datas)->save();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show1);
	    $this->display("mem_news");
	 }

	 public function mem_activity(){//联系野驴
	 	$this->__global();
	 	$userinfo = session("user");
	 	$orderMdl = M("Baoming_order");
	 	$count_num = $orderMdl->where("user_id=".$userinfo['id'])->count();
	 	$this->assign("count_num", $count_num);

	 	$userMdl = M("User_fabu_huodong");
	 	$p = I('get.p',1,'intval');
		$pageSize = 5;//C('admin_pageCount');
	    $where = "user_id=".$userinfo['id'];
	    $count = $userMdl->where($where)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->where($where)->order("id desc")->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	 	//成功的活动
	 	//自己发起的活动
	 	$this->display('mem_activity');
	 }

	 public function jifen_duihuan(){
	 	$this->__global();
	 	$id = I("get.id",0,"intval");//jifen_duihuan表的id
	 	$jfMdl = M("Jifen_duikuan");
	 	$jf = $jfMdl->where("id=".$id)->select();
	 	if(empty($jf[0]['nums'])){
	 		$this->error("不存在的id");exit;
	 	}
	 	$userinfo = session("user");
	 	if(empty($userinfo['id'])){
	 		$this->redirect("login");
	 	}
	 	$userMdl = M("User");
	 	$user_list = $userMdl->where("id=".$userinfo['id'])->select();
	 	if(empty($user_list[0]) || $user_list[0]['jifen'] < $jf[0]['jifen_nums']){
	 		$this->error("积分不足");exit;
	 	}
	 	$jifen_log = M("Jifen_log");
	 	$data = array('userid'=>$userinfo['id'],'jifen_duikuan_id'=>$id,'jifen'=>-$jf[0]['jifen_nums'],'reason'=>'兑换'.$jf[0]['change_goods'],'nums'=>$jf[0]['nums'],'time'=>date("Y-m-d"));
	 	$jifen_log->data($data)->add();

	 	$userMdl = M("User");
	 	$userMdl->where("id=".$userinfo['id'])->setDec("jifen",$jf[0]['jifen_nums']);
		$this->success("成功用积分兑换物品",U("user/coin_exchange"));
	 }

	 public function coin_exchange(){//积分兑换
	 	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = 10;//C('admin_pageCount');
	    $userMdl = M('Jifen_duikuan');
	    $where = "1=1";//"start_time >".time()." and end_time<".time();
	    $count = $userMdl->where($where)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->where($where)->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	    $this->display("coin_exchange");
	 }

	 public function mem_center(){//联系野驴
	 	//积分使用情况
	 	$user_info = session("user");
	 	$id = $user_info['id'];
	 	$jifenMdl = M("Jifen_log");
	 	$jifen_list = $jifenMdl->where('userid='.$id)->order("id desc")->select();
	 	$this->assign("jifen_list", $jifen_list);

	 	$head_titleMdl = M("Head_title");
	 	$dengji_list = $head_titleMdl->order("id asc")->select();
	 	$this->assign("dengji_list", $dengji_list);
	 	$this->display('mem_center');
	 }

	 public function mem_forum(){//联系野驴
	 	$this->__global();
	 	$userinfo = session("user");
	 	$userMdl = M("Topic");
	 	$p = I('get.p',1,'intval');
		$pageSize = 5;//C('admin_pageCount');
	    $where = "userid=".$userinfo['id'];
	    $count = $userMdl->where($where)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->where($where)->order("id desc")->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	 	$this->display('mem_forum');
	 }

	 public function mem_line(){//我的线路
	 	$this->__global();
	 	$userinfo = session("user");
		$p = I('get.p',1,'intval');
		$pageSize = 4;//C('admin_pageCount');
	    $userMdl = M('Baoming_order');
	    $where = "pic.pici_name is not null and hb.huodong_name is not null and user_id=".$userinfo['id'];
	    $count = $userMdl->join("left join huodong_pici as pic on pic.id=baoming_order.pici_id left join huodong_baoming as hb on hb.id=pic.huodong_id")
	    ->where($where)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->join("left join huodong_pici as pic on pic.id=baoming_order.pici_id left join huodong_baoming as hb on hb.id=pic.huodong_id")
	    ->where($where)->field("baoming_order.*,pic.huodong_id,pic.pici_name,hb.huodong_name,hb.huodong_title,hb.small_pic")->order("baoming_order.id desc")->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	 	$this->display('mem_line');
	 }
	 //取消订单
	 public function cancal_order(){
	 	$this->__global();
	 	$userinfo = session("user");
	 	$id = I("get.id", 0 ,"intval");
	 	$orderMdl = M("Baoming_order");
	 	$list = $orderMdl->join("left join huodong_pici as pic on pic.id=baoming_order.pici_id")->where("user_id=".$userinfo['id']." and baoming_order.id=".$id." and status=1")
		->field("baoming_order.*,pic.*, baoming_order.baoming_nums as bm_nums")->select();
	 	if(!empty($list[0])){//有该笔订单
	 		if(intval($list[0]['last_tuiding_date'])<intval(date("Y-m-d"))){
	 			$this->error("取消订单有效时间已到",U("user/mem_line"));exit;
	 		}
	 		$data=array("status"=>2,"id"=>$id,"last_operation_time"=>date("Y-m-d"));
	 		$orderMdl->data($data)->save();
			$piciMdl = M("Huodong_pici");
			$piciMdl->where("id=".$list[0]['pici_id'])->setDec("baoming_nums",$list[0]['bm_nums']);
	 		$this->success("取消订单成功",U("user/mem_line"));exit;
	 	}else{
	 		$this->error("取消订单失败",U("user/mem_line"));exit;
	 	}
	 }

	 public function mem_salon(){//我的沙龙
	 	$this->__global();
	 	$userinfo = session("user");
		$p = I('get.p',1,'intval');
		$pageSize = 5;//C('admin_pageCount');
	    $userMdl = M('Subscribe_shalong');
	    $where = "shalong.shalong_name is not null and user_id=".$userinfo['id'];
	    $count = $userMdl->join("left join shalong on shalong.id=subscribe_shalong.shalong_id")
	    ->where($where)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->join("left join shalong on shalong.id=subscribe_shalong.shalong_id")
	    ->where($where)->field("shalong.*,subscribe_shalong.*,shalong.id as salon_id")->order("subscribe_shalong.id desc")->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	 	$this->display('mem_salon');
	 }

	 public function mem_study(){//联系野驴
	 	$this->__global();
	 	$userinfo = session("user");
		$p = I('get.p',1,'intval');
		$pageSize = 5;//C('admin_pageCount');
	    $userMdl = M('Shoucang');
	    $where = "study_space.study_space_name is not null and user_id=".$userinfo['id'];
	    $count = $userMdl->join("left join study_space on study_space.id=shoucang.study_id")
	    ->where($where)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->join("left join study_space on study_space.id=shoucang.study_id")
	    ->where($where)->field("study_space.*,shoucang.create_time,shoucang.id as shoucang_id")->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	 	$this->display('mem_study');
	 }

	 public function del_shoucang(){
	 	$this->__global();
	 	$userinfo = session("user");
	 	$id = I("get.id", 0 ,"intval");
	 	$userMdl = M("Shoucang");
	 	$list = $userMdl->where("id=".$id." and user_id=".$userinfo['id'])->select();
	 	if(!empty($list[0])){
	 		$userMdl->delete($id);
	 		$this->success("删除成功");exit;
	 	}else{
	 		$this->error("错误的参数");exit;
	 	}
	 }
	
	 public function del_zuoping(){
            $this->__global();
            $userinfo = session("user");
            $id = I("get.id", 0 ,"intval");
            $userMdl = M("User_zuoping");
            $list = $userMdl->where("id=".$id." and user_id=".$userinfo['id'])->select();
            if(!empty($list[0]) || $userinfo['is_manage'] == 1){
                $userMdl->delete($id);
		if($userinfo['is_manage'] == 1){
                                $this->success("删除成功",U("works/works"));exit;
                        }
                $this->success("删除成功");exit;
				exit;
            }else{
                    $this->error("错误的参数");exit;
            }
     }
	
    public function del_user_pingjia(){
            $this->__global();
            $userinfo = session("user");
            $id = I("get.id", 0 ,"intval");
            $userMdl = M("Zuoping_pingjia");
            $list = $userMdl->where("id=".$id)->select();
            if(!empty($list[0]) && $userinfo['is_manage'] == 1){
                $userMdl->delete($id);
		if($userinfo['is_manage'] == 1){
                                $this->success("删除成功",U("works/works"));exit;
                        }
                $this->success("删除成功");exit;
				exit;
            }else{
                    $this->error("错误的参数");exit;
            }
     }
    
    public function del_message(){
        $this->__global();
        $userinfo = session("user");
        $id = I("get.id", 0 ,"intval");
        $userMdl = M("User_message");
        $list = $userMdl->where("id=".$id." and user_id=".$userinfo['id'])->select();
        if(!empty($list[0])){
            $userMdl->delete($id);
            $this->success("删除成功");exit;
			exit;
        }else{
            $this->error("错误的参数");exit;
        }
     }

	public function del_post(){
	 	$this->__global();
	 	$userinfo = session("user");
	 	$id = I("get.id", 0 ,"intval");//post表id
	 	$userMdl = M("Post");
	 	$list = $userMdl->where("id=".$id." and userid=".$userinfo['id'])->select();
	 	if(!empty($list[0]) || $userinfo['is_manage'] == 1){
	 		$userMdl->delete($id);
			if($userinfo['is_manage'] == 1){
                                $this->success("删除成功",U("forum/forum"));exit;
                        }
	 		$this->success("删除成功");exit;
	 	}else{
	 		$this->error("错误的参数");exit;
	 	}
	 }

	 public function del_topic(){
	 	$this->__global();
	 	$userinfo = session("user");
	 	$id = I("get.id", 0 ,"intval");//post表id
	 	$userMdl = M("Topic");
	 	$list = $userMdl->where("id=".$id." and userid=".$userinfo['id'])->select();
	 	if(!empty($list[0]) || $userinfo['is_manage'] == 1){
	 		$userMdl->delete($id);
			if($userinfo['is_manage'] == 1){
				$this->success("删除成功",U("forum/forum"));exit;
			}
	 		$this->success("删除成功");exit;
	 	}else{
	 		$this->error("错误的参数");exit;
	 	}
	 }

	 public function mem_works(){//我的作品
	 	$this->__global();
	 	$userinfo = session("user");
		$p = I('get.p',1,'intval');
		$pageSize = 5;//C('admin_pageCount');
	    $userMdl = M('User_zuoping');
	    $where = "user_id=".$userinfo['id'];
	    $count = $userMdl->where($where)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->where($where)->order("id desc")->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	 	$this->display('mem_works');
	 }

	 public function release_activity(){//发布活动或作品
	 	$user_fabu = I("get.user_fabu","user_fabu");
	 	$id = I("get.id",0,"intval");
	 	if($id != 0){
	 		if($user_fabu == 'user_fabu'){
	 			$userMdl = M("User_fabu_huodong");
	 		}else{
	 			$userMdl = M("User_zuoping");
	 		}
	 		$list = $userMdl->where("id=".$id)->select();
	 		$this->assign("data", $list[0]);
	 	}
		$this->assign("id", $id);
	 	$this->assign("user_fabu", $user_fabu);
	 	$this->display('release_activity');
	 }

	 public function release_post(){//联系野驴
	 	$topic_id = I("get.topic_id",0,"intval");
	 	$id = I("get.post_id",0,"intval");
		
	 	if($id != 0){
	 		$userMdl = M("Post");
	 		$list = $userMdl->where("id=".$id)->select();
	 		$this->assign("data", $list[0]);
	 	}
	 	$this->assign("id", $id);
	 	$this->assign("topic_id", $topic_id);
	 	$this->display('release_post');
	 }

	 public function release_works(){//联系野驴
	 	$this->display('release_works');
	 }

	 public function userinfo_save(){
	 	$post = I('post.');
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','JPG');// 设置附件上传类型
        $upload->rootPath  =     './Public/images/upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        $pic = "";
        if($info){// 上传成功
            $pic = '';
            foreach($info as $file){
                $pic .= $file['savepath'].$file['savename'];
            }
        }
		if($pic !=''){
			$post['small_pic'] = "upload/".$pic;
			$image = new \Think\Image(); 
			// 在图片右下角添加水印文字 ThinkPHP 并保存为new.jpg
			$image->open('./Public/images/upload/'.$pic)->water('./Public/images/upload/water.png',\Think\Image::IMAGE_WATER_SOUTHEAST)->save("./Public/images/upload/".$pic); 
		}
		
		$useMdl = M('User');
		$userinfo = session("user");
		$post['id'] = $userinfo['id'];
		$return = $useMdl->save($post);
		$list = $useMdl->where("id=".$post['id'])->select();
		session("user", $list[0]);
		$this->success("保存成功",U("user/mem_center"));
	 }

    public function go_login(){
		$post = I('post.');
		$userMdl = M('User');
		$list = $userMdl->where("telphone='".$post['telphone']."' and pwd='".$post['pwd']."'")->select();
		if(!empty($list[0])){
			session('user',$list[0]);
			$userMdl->data(array('last_operation_time'=>time(),'id'=>$list[0]['id']))->save();
			echo "success";
			exit;
		}
		exit("手机或密码不正确!");
    }

    public function save_customers_info(){
    	$post = I("post.");
    	$userinfo = session('user');
    	if($post['userid'] != $userinfo['id']){
    		exit("id 出错了");
    	}
    	$userMdl = M();
    	$update_str = array();
    	foreach($post as $key=>$value){
    		if($key != "userid"){
	    		$update_str[] = "$key='$value'";
	    	}
    	}
    	$sql ="update usr_user set ".implode(',', $update_str)." where userid=".$post['userid'];
    	$userMdl->execute($sql);
    	$useMdl = M("Usr_user");
    	$list = $useMdl->where("userid=".$post['userid'])->select();
    	session("user",$list[0]);
    	exit(true);
    }

    public function save_pwd(){
    	$post = I("post.");
    	$userinfo = session('user');
    	if($post['userid'] != $userinfo['id'] ){
    		exit("id 出错了");
    	}
    	$useMdl = M("Usr_user");
    	$list = $useMdl->where("userid=".$post['userid'])->select();
    	if($this->getmd5($post['old_password']) != $list[0]['password']){
    		exit("原始密码不正确");
    	}
    	$post['password'] = $this->getmd5($post['password']);
    	$userMdl = M();
    	$userMdl->execute("update usr_user set password='".$post['password']."' where userid=".$post['userid']);
    	exit(true);
    }

    public function login(){
    	$userinfo = session('user');
    	if(!empty($userinfo)){
    		$this->redirect("user/mem_center");
    	}
    	$this->display("login");
    }

    public function register(){
    	$userinfo = session('user');
    	if(!empty($userinfo)){
    		//$this->redirect("user/mem_center");
    	}
    	$this->display("register");
    }
	
	
    public function login_out(){
		session_unset();
		session_destroy();
		header("location:".B_PATH."/index.php");
	}

	/**个人中心项*/
	public function userinfo(){
		$this->initUser();
		$student = I("get.student", '');
		$userinfo = session("user");
		$learn_centerMdl = M("Sct_studycenter");
		$learn_center_name = $learn_centerMdl->where("studycenterid=".$userinfo['studycenterid'])->select();

		$gradeMdl = M('Grade');
		$grade_list = $gradeMdl->where("status=1")->select();
		$this->assign("teacher_center_list", $learn_center_name);
		$this->assign("grade_list", $grade_list);
		$this->assign("student", $student);
		$this->display('userinfo');
	}

    public function mypwd(){
    	$this->initUser();
    	$this->display("mypwd");
    }

    public function freedownload(){
    	$this->initUser();
    	$this->__global();
        $get = I("get.");
        $userMdl = M('File_download');
        $list = $userMdl->order('paixu asc')->select();
        $this->assign('list',$list);// 赋值数据集
    	$this->display("freedownload");
    }


    public function file_download(){
	    $this->initUser();
	 	$this->__global();
		$id = I('get.id',1,'intval');
		$healMdl = M('File_download');
		$list = $healMdl->where('id='.$id)->select();
	    if(empty($list[0]['file_position'])){
	    	header('HTTP/1.1 404 Not Found11');
	        echo "Error: 4041 Not Found.(server file path error)";
	        exit;
	    }

	    $filePath = $this->baseurl."Public/images/".$list[0]['file_position'];
	    
	    $fp = fopen($filePath,'rb');
	    if(!$filePath || !$fp){
	        header('HTTP/1.1 404 Not Found22');
	        echo $this->baseurl."Public/images/".$list[0]['file_position'];//"Error: 404 Not Found.(server file path error)";
	        exit;
	    }
	    
	    $fileName = $list[0]['file_name'];
	    $encoded_filename=rawurlencode($fileName);
	    
	    header('HTTP/1.1 200 OK');
	    header( "Pragma: public" );
	    header( "Expires: 0" );
	    header("Content-type: application/octet-stream");
	    header("Content-Length: ".filesize($filePath));
	    header("Accept-Ranges: bytes");
	    header("Accept-Length: ".filesize($filePath));
	    
	    $ua = $_SERVER["HTTP_USER_AGENT"];
	    if (preg_match("/MSIE/", $ua)) {
	        header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
	    } else if (preg_match("/Firefox/", $ua)) {
	        header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '"');
	    } else {
	        header('Content-Disposition: attachment; filename="' . $fileName . '"');
	    }
	    
	    // ob_end_clean(); <--有些情况可能需要调用此函数
	    // 输出文件内容
	    fpassthru($fp);
	    exit;
	 }

	public function create_pwd(){
		$chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',0,1,2,3,4,5,6,7,8,9);
		$new_pwd = '';
		for($i=0; $i<8;$i++){
			$new_pwd .= $chars[mt_rand(0,35)];
		}
		return $new_pwd;
	}

	public function sendsms(){
		$post = I("post.");
		if(!empty($post['is_register'])){
			if($post['yanzhengma'] != session("yanzhengma")){
				echo "验证码错误或已过期";
				exit;
			}
			$userMdl = M('User');
			$list = $userMdl->where("telphone='".$post['telphone']."'")->select();
			if(!empty($list[0])){
				echo $post['telphone']." 手机已注册过";
				exit;
			}
			
			$jifen_nums_array = $this->base_config;
			if(!empty($post['memid'])){
				$mem_list = $userMdl->where("telphone='".$post['memid']."'")->limit()->select();
				if(!empty($mem_list[0])){
					$jifen_nums = $jifen_nums_array['tuijian_jifen'];
					if($jifen_nums >0){
						$userMdl->where("telphone='".$post['memid']."'")->setInc("jifen",$jifen_nums);
						$jifen_logMdl = M("Jifen_log");
						$data = array("userid"=>$mem_list[0]['id'],"jifen"=>$jifen_nums,"reason"=>"推荐用户注册","time"=>date("Y-m-d H:i:s"));
						$jifen_logMdl->data($data)->add();
					}

				}
			}
			$post['username'] = "user_".$post['yanzhengma'].date("Ymd");
			$post['register_time'] = date("Y-m-d");
			$post['last_operation_time'] = time();
			$post['id'] = $userMdl->data($post)->add();
			$register_jifen = $jifen_nums_array['register_jifen'];
                        if($register_jifen>0){
                        	 $userMdl->where("id=".$post['id'])->setInc("jifen",$register_jifen);
                                 $jifen_logMdl = M("Jifen_log");
                                 $data = array("userid"=>$post['id'],"jifen"=>$register_jifen,"reason"=>"新注册","time"=>date("Y-m-d H:i:s"));
                                 $jifen_logMdl->data($data)->add();
				$post['jifen'] = $register_jifen;                
                        }
			session("user", $post);
			echo "success";
			exit;
		}
		$phone = I("post.phone");
		$yanzhengma = I("post.yanzhengma");
		$config =    array(
		    'fontSize'    =>    30,    // 验证码字体大小
		    'length'      =>    4,     // 验证码位数
		    'useNoise'    =>    false, // 关闭验证码杂点
			'reset' =>false,
		);
		$Verify =     new \Think\Verify($config);
		if(!empty($yanzhengma)){
			$phonecheck = $yanzhengma == session("yanzhengma");
			$captchacheck = true;//$Verify->check($post['invalidcode'],10000);
			if($phonecheck && $captchacheck){//线上要做处理
				session("modifyuser", $_POST);
				echo "success";
				exit;
			}else{
				if(!$phonecheck){
					echo '手机验证码不正确';exit;
				}else{
					echo '图形验证码不正确!!';exit;
				}
			}
		}else{
			$yzm = I("post.yzm");
			if(!empty($yzm) && $Verify->check($yzm,10000)==false){
				echo '图形验证码不正确';exit;
			}
			$userMdl = M("User");
			$list = $userMdl->where("telphone='{$phone}'")->select();
			if(!empty($list[0]) && isset($post['actions']) && !empty($post['actions'])){
				echo "该手机已注册过";exit;
			}
			//去发短信
			//http://sh2.ipyy.com/sms.aspx?action=send&userid=&account=jkwl142&password=jkwl14266&mobile=15000897931&content=内容【野驴部落】&sendTime=&extno=
			$yanzhengma = mt_rand("100000","999999");
			$contents = "您的验证码为：{$yanzhengma}【正尔科技】";
			$datas = array(
						"action"=>'send',
						"userid"=>'',
						"account"=>'jksc675',
						"password"=>'541296',
						'mobile'=>$phone,
						'content'=>$contents,
						'sendTime'=>'',
						'extno'=>'');
			$data = $this->curl_post("http://sh2.ipyy.com/sms.aspx", "post",$datas);
			session("yanzhengma", $yanzhengma);
			echo "ok";
		}
	}

	public function curl_post($url, $method="post" ,$datas=''){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		if($method == "post"){
			curl_setopt($curl, CURLOPT_POST, 1 );
			curl_setopt($curl, CURLOPT_POSTFIELDS, $datas);
		}
		$data = curl_exec($curl);
		if(curl_errno($curl)){
		return 'ERROR'.curl_error($curl);
		
		}
		curl_close($curl);
		return $data;
	} 

	public function shalong_baoming(){
		$this->__global();
	 	$userinfo = session("user");
	 	$id = I('get.id',0,"intval");
		$useMdl = M('Subscribe_shalong');
		$userinfo = session("user");
		$list = $useMdl->where("user_id=".$userinfo['id']." and shalong_id=".$id)->select();
		if(!empty($list[0])){
			$this->error("已报名过","mem_salon");
			exit;
		}
		$post['shalong_id'] = $id;
		$post['user_id'] = $userinfo['id'];
		$post['create_time'] = date("Y-m-d");
		$return = $useMdl->data($post)->add();
		$this->success("报名成功");
	}
	public function huodong_baoming(){
		$this->__global();
	 	$userinfo = session("user");
	 	$id = I('get.pici_id',0,"intval");
		$useMdl = M('Baoming_order');
		$userinfo = session("user");
		$price = I("get.price",0,"intval");
		$list = $useMdl->where("user_id=".$userinfo['id']." and pici_id=".$id)->select();
		if(!empty($list[0])){
			$data = array("status"=>1,"id"=>$list[0]['id'],"pay_price"=>$price);
			$useMdl->data($data)->save();
		}else{
			$post['order_id'] = "pay".date("YmdHis")."_".$userinfo['id'];
			$post['user_id'] = $userinfo['id'];
			$post['pici_id'] = $id;
			$post['status'] = 1;
			$post['pay_price'] = $price;
			$post['create_time'] = date("Y-m-d");
			$pay_order_id = $useMdl->data($post)->add();
		}
		//报名成功送积分
		$hdMdl = M("Huodong_baoming");
		$list = $hdMdl->join("left join huodong_pici as hpc on hpc.huodong_id = huodong_baoming.id")->field("jifen, huodong_name,pici_name")->where("hpc.id=".$id)->select();
		$piciMdl = M("Huodong_pici");
		$piciMdl->where('id='.$id)->setInc("baoming_nums");
		if(!empty($list[0]) && $list[0]['jifen']){
			$userMdl = M("User");
			$userMdl->where("id=".$userinfo['id'])->setInc("jifen", $list[0]['jifen']);
			$userinfo['jifen'] = $usreinfo['jifen'] + intval($list[0]['jifen']);
			session("user", $userinfo);
			$reason = "报名活动:(".$list[0]['huodong_name'].",".$list[0]['pici_name']."),时间为:".date("Y-m-d H:i:s");
			$data = array("jifen"=>$list[0]['jifen'],"userid"=>$userinfo['id'],"reason"=>$reason,"time"=>date('Y-m-d'));
			$jifenMdl = M("Jifen_log");
			$jifenMdl->data($data)->add();
			
		}
		$this->success("报名成功",U('user/mem_line'));
	}

	public function photograph_reg(){
		$this->__global();
		$get = I("get.");
		$m = M("Huodong_baoming");
		$list = $m->join("left join huodong_pici as pici on pici.huodong_id=huodong_baoming.id")->where("pici.id = ".$get['pici_id'])->select();
		$limit_people = intval($list[0]['limit_people']) - intval($list[0]['baoming_nums']);
		if($limit_people<=0){
			$this->error("报名人数已满",U("user/mem_line"));exit;
		}
		$this->assign("limit_people", $limit_people);
		$this->assign("huodong_name", $get['huodong_name']);
		$this->assign("pici_id", $get['pici_id']);
		$this->assign("huodong_shijian", $get['huodong_shijian']);
		$this->assign("price", $get['price']);
		$this->display("photograph_reg");
	}

	public function photograph_pay(){
		$this->__global();
	 	$userinfo = session("user");
	 	$id = I('get.pici_id',0,"intval");//批次id
		$useMdl = M('Huodong_pici');
		$list = $useMdl->join("left join huodong_baoming as hb on hb.id=huodong_pici.huodong_id")->field("huodong_pici.id,hb.price as price")->where("huodong_pici.id=".$id)->select();
		$baoming_nums = I("get.baoming_nums", 1, "intval");
		//先生成预定义的订单
		$orderMdl = M("Baoming_order");
		$order_list = $orderMdl->where("user_id=".$userinfo['id']." and pici_id=".$id)->select();
		if(!empty($order_list[0])){
			$baoming_nums = $order_list[0]['baoming_nums'];
		}
		$order_id = date("Ymdhis").$userinfo['id'];
		$list[0]['price'] =  intval($list[0]['price'] * $baoming_nums);
		if(empty($order_list[0])){
			$datas = array('order_id'=>$order_id,"user_id"=>$userinfo['id'],"pici_id"=>$list[0]['id'],"pay_price"=>$list[0]['price'],"baoming_nums"=>$baoming_nums,'status'=>0,'create_time'=>date("Y-m-d"));
			$orderMdl->data($datas)->add();
		}
		$this->assign("order_id", $order_id);
		$this->assign("list", $list[0]);
		$this->display("photograph_pay");
	}

	public function shanghai_baoming(){
		$this->__global();
	 	$userinfo = session("user");
	 	$id = I('post.id',0,"intval");//批次id
		$useMdl = M('Baoming_order');
		$userinfo = session("user");
		$list = $useMdl->where("user_id=".$userinfo['id']." and pici_id=".$id)->select();
		if(!empty($list[0])){
			echo 0;
			exit;
		}
		echo 11;
	}

	public function dianzan(){//boke列表
        $this->__global();
        $userinfo = session("user");
        if(empty($userinfo)){
            echo "login";exit;
        }
	$m = "Topic";
	$tiezi_nums = "dianzan_nums";
	$user_field = "userid";
        $get_jifen = "get_jifen";
	$field_name = "topic_id";
	$post = I("post.");
	if(isset($post['type']) && !empty($post['type'])){
		$m = ucwords($post['type']);
		$field_name = $post['type']."_id";
		$user_field = "user_id";
                $tiezi_nums = "huiyuan_huodong_dianzan_nums";
                $get_jifen = "huiyuan_huodong_get_jifen";
	}
        $newsMdl = M($m);
        $user_id = $userinfo['id'];
        $news_id = I("post.news_id",0,"intval");

        $dianzan_Mdl = M("Dianzan_log");
        $list = $dianzan_Mdl->where("user_id=".$user_id." and {$field_name}=".$news_id)->select();
        if(empty($list[0])){
            $data = array($field_name=>$news_id, "user_id"=>$user_id,"create_time"=>date("Y-m-d"));
            $dianzan_Mdl->data($data)->add();
            $newsMdl->where("id=".$news_id)->setInc("dianzan_nums");
            
	   //点赞数量达到多少，送积分
           $baseconfig = $this->base_config;
           $dianzan_list = $newsMdl->where("id=".$news_id)->select();
          if($dianzan_list[0]['dianzan_nums'] == $baseconfig[$tiezi_nums] && $baseconfig[$get_jifen]>0 ){
		if($post['type'] == 'user_fabu_huodong'){
			$datas = array("id"=>$news_id,"is_success"=>1);
			$newsMdl->data($datas)->save();
		}
                $userMdl = M("User");
		$userMdl->where("id=".$dianzan_list[0][$user_field])->setInc("jifen", $baseconfig[$get_jifen]);
		$reason = "点赞数达到".$dianzan_list[0]['dianzan_nums'].",送积分".$baseconfig[$get_jifen]."),时间为:".date("Y-m-d H:i:s");
                $data = array("jifen"=>$dianzan_list[0]['dianzan_nums'],"userid"=>$dianzan_list[0][$user_field],"reason"=>$reason,"time"=>date('Y-m-d'));
                $jifenMdl = M("Jifen_log");
		if($userinfo['id']==$dianzan_list[0][$user_field]){
			$userinfo['jifen'] = $usreinfo['jifen'] + intval($baseconfig[$get_jifen]);
                        session("user", $userinfo);
		}
                $jifenMdl->data($data)->add();	
          }
	    echo "success";exit;
        }
        echo "已点赞过";exit;
    }
	
	public function edit_pwd(){
        $post = I("post.");
        $phone = $post['phone'];
        $pwd = $post['pwd'];
        $mdl = M("User");
        $user = $mdl->where("telphone='{$phone}'")->select();
        if(!empty($user[0])){
                $data = array("pwd"=>$pwd,"id"=>$user[0]['id']);
                $mdl->data($data)->save();
                echo "success";exit;
        }
        echo "error";
   }

   /**
    * 个人中心首页
    * @return [type] [description]
    */
   public function index(){
   		$this->__global();
		$user = session("user");
		if(empty($user) || empty($user['telphone'])){
			$this->success("请先完善您的个人资料",U("user/modifyUserInfo"),2);
			exit;
		}
   		$this->display("index");
   }

   /**
    * 修改用户信息
    * @return [type] [description]
    */
   public function editUserInfo(){
   		$this->__global();
   		$design_productM = M("Design_product");//设计产品
        $design_list = $design_productM->where("status=1")->select();
        $this->assign("design_list", $design_list);//销售产品

        //省份
        $provinceMdl = M("Province");
    	$provincelist = $provinceMdl->select();
    	$this->assign("province", $provincelist);
        //行业分类
        $industry_type = M("Industry_type");
        $industry_type_list = $industry_type->where("status=1")->select();
        $this->assign("industry_type", $industry_type_list);
   		$this->display("edituserinfo");
   }
	public function saveuserinfo(){
   		$this->__global();
   		$post = I('post.');
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','JPG');// 设置附件上传类型
        $upload->rootPath  =     './Public/images/upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        $pic = array();
        if($info) { //上传成功
            foreach($info as $file){
                $pic[] = $file['savepath'].$file['savename'];
            }
        }
        if(!empty($pic)){
	        $post['id_front'] = $pic[0];
	        $post['id_end'] = isset($pic[1]) ? $pic[1] : $pic[0];
    	}
        $useMdl = M("User");
		if(!isset($post['year'])){
			$post['year'] = $post['YYYY'];
			$post['month'] = $post['MM'];
			$post['day'] = $post['DD'];
		}
        $post['sale_product'] = implode(" ", $post['sale_product']);
        $post['id'] = $this->session_uid;
        $return = $useMdl->save($post);
		$list = $useMdl->where("id=".$post['id'])->select();
		session("user", $list[0]);
        $this->display("edituserinfoSuccess");
	}
   /**
    * 修改手机
    * @return [type] [description]
    */
   public function editPhone(){
   		$this->__global();
   		$step = I("get.step","");
   		if(!empty($step)){
   			$post = I("post.");
			
			$session_user = session("user");
   			if($step == "step1"){
   				if($post['code'] != session("yanzhengma")){
   					$this->assign("error", "手机验证码输入不正确");
   					$this->display("editphone");exit;
   				}elseif($post['telphone']!=$session_user['telphone']){
   					$this->assign("error", "手机不正确");
   					$this->display("editphone");exit;
   				}else{
   					$this->assign("telphone", $post['telphone']);
   					$this->display("editphone2");exit;
   				}
   			}
   			if($step == "step2"){
   				if($post['code'] != session("yanzhengma")){
   					$this->assign("error", "手机验证码输入不正确");
   					$this->display("editphone2");exit;
   				}else{
   					$oldtel = $post['oldtelphone'];
   					$telphone = $post['telphone'];
   					$user = M("User");
   					$user->data(array("telphone"=>$telphone,'id'=>$session_user['id']))->save();
   					$this->display("editphone3");exit;
   				}
   			}
   		}
   		$this->display("editphone");
   }

   /**
    * 完善资料
    * @return [type] [description]
    */
   public function modifyUserInfo(){
   		$this->__global();
   		$step = I("get.step", '');
		session_start();
   		$sumbit_user = session("modifyuser");
		$sumbit_user = empty($sumbit_user) ? array() : $sumbit_user;
   		if($step == "2"){
			$config =    array(
                    'fontSize'    =>    30,    // 验证码字体大小
                    'length'      =>    4,     // 验证码位数
                    'useNoise'    =>    false, // 关闭验证码杂点
                        'reset' =>false,
                );
                $Verify =     new \Think\Verify($config);
			if(!empty($_POST['msgCode'])){
			$phonecheck = $_POST['msgCode'] == session("yanzhengma")?1:0;
                        $captchacheck = true;//$Verify->check($_POST['invalidcode'],10000) ?1:0;
			if(!$phonecheck || !$captchacheck){
				$this->error($phonecheck.$captchacheck.$_POST['msgCode']."--".$_POST['invalidcode']."短信验证码或图形验证码错误");exit;
			}
			}
   			$sumbit_user['telphone'] = $_POST['telphone'] ?$_POST['telphone']:$_GET['telphone'];
   			$sumbit_user['username'] = $_POST['username'] ?$_POST['username']:$_GET['username'];
			$sumbit_user['sex'] = $_POST['sex'] ?$_POST['sex']:$_GET['sex'];
			session("modifyuser", $sumbit_user);
   			$this->display('modifyuserinfo2');
   		}elseif($step == "3"){
   			if(!empty($_GET['user_type'])){
	   			$sumbit_user['user_type'] = $_GET['user_type'];
	   			session("modifyuser", $sumbit_user);
	   		}
   			$type = I("get.type",'xiangmu');
   			$design_productM = M("Design_product");//设计产品
	        $design_list = $design_productM->where("status=1")->select();
	        $this->assign("design_list", $design_list);//销售产品
	        $this->assign("user_type", $_GET['user_type']);

	        //省份
	        $provinceMdl = M("Province");
	    	$provincelist = $provinceMdl->select();
	    	$this->assign("province", $provincelist);
			$this->assign("postinfo",$_GET['post']);
	        //行业分类
	        $industry_type = M("Industry_type");
	        $industry_type_list = $industry_type->where("status=1")->select();
	        $this->assign("industry_type", $industry_type_list);
   			$this->display('modifyuserinfo3');
   		}elseif($step == "4"){
   			$post = I('post.');
			$upload = new \Think\Upload();// 实例化上传类
	        $upload->maxSize   =     314572800 ;// 设置附件上传大小
	        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','JPG');// 设置附件上传类型
	        $upload->rootPath  =     './Public/images/upload/'; // 设置附件上传根目录
	        $upload->savePath  =     ''; // 设置附件上传（子）目录
	        // 上传文件
	        $info   =   $upload->upload();
			//file_put_contents("upload.txt", var_export($_SERVER, true)."----".var_export($info,true)."++++++".var_export($_FILES,true),FILE_APPEND);
	        $pic = array();
	        if(!$info) {// 上传错误提示错误信息
			$this->error("身份证图片未上传!");
	        }else{// 上传成功
	            foreach($info as $file){
	                   $pic[] = $file['savepath'].$file['savename'];
	               }
	        }
	        $post['id_end'] = $pic[0];
	        $post['id_front'] = isset($pic[1]) ? $pic[1] : $pic[0];
	        $post['sale_product'] = implode(" ", $post['sale_product']);
	        $useMdl = M("User");
	        $post['id'] = $this->session_uid;
	        $post['apply_identify'] = 1;
	        //$post['is_identify'] = 1;
	        $post['telphone'] = $sumbit_user['telphone'];
		$post['username'] = $sumbit_user['username'];
	        $post['user_type'] = $sumbit_user['user_type'];
		$post['sex'] = $sumbit_user['sex'];
	        $post['apply_time'] = time();
	        $return = $useMdl->where("id=".$post['id'])->save($post);
	        $user = $useMdl->where("id=".$post['id'])->select();
	        session("user",$user[0]);
   			$this->display("modifyuserinfo4");
   		}else{
   			$this->display("modifyuserinfo");
   		}
   }

   public function verify(){
   		$config =    array(
		    'fontSize'    =>    30,    // 验证码字体大小
		    'length'      =>    4,     // 验证码位数
		    'useNoise'    =>    false, // 关闭验证码杂点
			'reset' =>false,
		);
		$Verify =     new \Think\Verify($config);
		$Verify->entry(10000);
   }

   /**
    * 取现
    * @return [type] [description]
    */
   public function withdrawls(){
   		$this->__global();
		$user = session("user");
		$list = M("Bankcard")->where("user_id=".$user['id'])->select();
		$this->assign("bankinfo", $list);
   		$this->display("withdrawls");
   }
   
   public function addbank(){
	 $this->__global();
	$this->display("addbank");
   }
   public function savebank(){
	 $this->__global();
	$bankM = M("Bankcard");
	$post = I("post.");
	if(empty($post['bankname']) ||empty($post['username']) || empty($post['branchbank']) ||empty($post['card'])){
		 $this->error("银行卡信息不能为空!");exit;		
	}
	$userinfo = session("user");
	$binfo = $bankM->where("user_id=".$userinfo['id']." and bankname='".$post['bankname']."' and branchbank='".$post['branchbank']."'")->select();
	if(!empty($binfo[0])){
		$this->error("该银行卡已存在");exit;
	}
	$post['user_id'] = $userinfo['id'];
	$post['create_time'] = time();
	$bankM->data($post)->add();
	$this->display("addbankSucc");	
   }
   /**
   *提现
   **/
   public function tixian(){
	   	$this->__global();
	   	$money = I("post.money", 1, 'intval');
		$post = I("post.");
		if(empty($post['bankid'])){
		$this->error("银行信息不能为空");exit;
		}
	   	$userinfo = session("user");
	   	if($money>$userinfo['money']){
	   		$this->assign("message", "提现金额过大");
	   	}else{
			$list = M("Cash_apply")->where("uid=".$userinfo['id']." and status=0")->select();
			if(!empty($list[0])){
				$this->assign("message", "你有一笔提现申请正在等待中");
				$this->display("withdrawlsSuccess");
				exit;
			}
	   		//to send hongbao
			$datas = array("type"=>1,"fee"=>$money,"uid"=>$userinfo['id'],"create_time"=>time(),'bankid'=>$post['bankid']);
			M("Cash_apply")->data($datas)->add();
			M("User")->where("id=".$userinfo['id'])->setDec("money", $money);
			$userinfo['money'] = $userinfo['money'] - $money;
			session("user", $userinfo);
			
			$data = array("user_id"=>$userinfo['id'],"use_type"=>"提现","pay_nums"=>$money,"detail_content"=>"提现","pay_type"=>"余额","create_time"=>time());
			$paylog = M("Pay_log");
			$paylog->data($data)->add();
			
	   		$this->assign("message", "恭喜您提交申请成功！");
	   		$this->assign("success", 1);
	   	}
	   	$this->display("withdrawlsSuccess");
   }

   /**
    * 个人中心去充值
    * @return [type] [description]
    */
   public function recharge(){
   		$this->__global();
   		$this->display("recharge");
   }

   /**
    * 充值记录
    * @return [type] [description]
    */
   public function rechargeLog(){
   		$this->__global();
   		$rechargelogM = M("Orders");
   		$rechargelog = $rechargelogM->where("user_id=".$this->session_uid." and pay_status=1")->order(" create_time desc")->select();
   		$this->assign("rechargelog", $rechargelog);
   		$paylogM = M("Pay_log");
   		$paylog = $paylogM->where("user_id=".$this->session_uid)->order(" create_time desc")->select();
   		$this->assign("paylog", $paylog);
		
		$paylogM = M("Rechargelog");
                $paylogs = $paylogM->where("uid=".$this->session_uid)->order(" create_time desc")->select();
                $this->assign("relog", $paylogs);

   		$this->display("rechargelog");
   }

   /**
    * 消费记录
    * @return [type] [description]
    */
   public function consumeLog(){
   		$this->__global();
   		$this->display("consume_log");
   }

   /**
    * 我的任务，发布的任务，中标的任务
    * @return [type] [description]
    */
   public function myTask(){
   		$this->__global();
   		//我发布的任务和我发布的任务
   		$sendtask = M("Main_task");
   		$userinfo = session("user");
   		$sendtask = $sendtask->join("left join task_evaluate tev on tev.task_id=main_task.id")->where("uid=".$userinfo['id']." and is_baobei=0 and expire_time >".time() ." and (tev.user_id=".$userinfo['id']." or tev.user_id is null)")->order(" main_task.id  desc")->field("main_task.*,tev.user_id as te_user_id,tev.accept_uid as te_accept_uid")->select();
   		if(!empty($sendtask[0])){
			$bidM = M("Bid");
			foreach($sendtask as $keys=>$senddata){
				$has_read = $bidM->where("task_id=".$senddata['id']." and has_read=0")->count();
				if($has_read) $sendtask[$keys]['has_read'] = 0;	
			}
		}
		$this->assign("sendtask", $sendtask);
   		$bidtask = M("Bid");
   		$bidtask = $bidtask->join("left join main_task on main_task.id=bid.task_id")->where("bid.user_id=".$userinfo['id']." and is_baobei=0 and expire_time>".time())->field("main_task.*,bid.status as bstatus,bid.bid_has_read ")->order(" main_task.id  desc")->select();
		if(!empty($bidtask[0])){
                        $bidM = M("Task_evaluate");
                        foreach($bidtask as $key=>$biddata){
                                $te = $bidM->where("task_id=".$biddata['id']." and user_id=".$userinfo['id'])->select();
                                if(!empty($te[0])) {
					$bidtask[$key]['hasPl'] = true;
				}
                        }
                }
   		$this->assign("bidtask", $bidtask);
   		$this->display("mytask");
   }

   /**
    * 我的信誉
    * @return [type] [description]
    */
   public function myHouor(){
   		$this->__global();
   		$honor = M("Task_evaluate");
   		$userinfo = session("user");
   		$honor_list = $honor->join("left join main_task on main_task.id = task_evaluate.task_id")->where("task_evaluate.accept_uid=".$userinfo['id'])->field("task_evaluate.*,main_task.pro_name,main_task.pro_code")->order(" task_evaluate. create_time desc")->select();
   		$this->assign("honordata", $honor_list);
   		$this->display("myhonor");
   }

   /**
    * 我的反馈，交易，投诉维权
    * @return [type] [description]
    */
   public function mySuggest(){
   		$this->__global();
   		$type = I("get.type","交易举报");
   		$userinfo = session("user");
   		$where = "user_id=".$userinfo['id'];
   		if(!empty($type)){
   			$where .= " and suggest_type='{$type}'";
   		}

   		$suggest = M("Suggest");
   		if($type!='投诉建议'){
   			$suggest_list = $suggest->join("left join main_task on main_task.id=suggest.task_id")->where($where)->field("main_task.pro_code,main_task.pro_name,suggest.*")->order("suggest_time desc")->select();
   		}else{
   			$suggest_list = $suggest->where($where)->order("suggest_time desc")->select();
   		}
   		$suggest->where(array("user_id"=>$userinfo['id']))->save(array("has_read"=>1));
   		$this->assign("suggest", $suggest_list);
   		$this->assign("type",$type);
   		$this->display("mysuggest");
   }

   /**
    * 金牌认证
    * @return [type] [description]
    */
   public function identify(){
   		$this->__global();
   		$this->display("identify");
   }

   /**
    * 金牌认证
    * @return [type] [description]
    */
   public function saveidentify(){
   		$this->__global();
   		$this->display("identifySuccess");
   }
   /**
    * 金牌认证
    * @return [type] [description]
    */
   public function myBaobei(){
   		$this->__global();
   		$userinfo = session("user");
   		$baobei = M("Main_task");
   		$baobeilist = $baobei->where("uid=".$userinfo['id']." and is_baobei=1")->select();
   		$this->assign("baobei", $baobeilist);

   		$this->display("mybaobei");
   }

   /**
    * [taskdetail description]我发布的任务详情
    * @return [type] [description]
    */
   public function taskdetail(){
		$this->__global();
        $id = I("get.task_id", 1, "intval");
        $taskM = M("Main_task");

        $task = $taskM->join("left join task_type on task_type.id= main_task.task_type_id")->where("main_task.id=".$id)->field("main_task.*,task_type.task_type_name")->select();
        $this->assign("task", $task[0]);
        $bidM = M("Bid");
        $bid = $bidM->join("left join user on user.id=bid.user_id")->where("task_id = ".$id)->field("user.username,user.id,bid.self_introduce")->select();
		$usersession = session("user");
		$eve = M("Task_evaluate")->where("task_id=".$id." and user_id=".$usersession['id'])->select();
		$hasEve = false;
		if(!empty($eve[0])) $hasEve=true;
		$this->assign("hasEve", $hasEve);
		$bl = M("Danbao_apply")->where("task_id=".$id)->select();
		$show_weiquan = 1;
		if($task[0]['price'] ==0){
			if(empty($bl[0])|| $bl[0]['status'] == 0) {
				$show_weiquan = 0;
			}
			if(!empty($bl[0])){
				$descr = $bl[0]['status']==1 ? "项目担保中" : ($bl[0]['status'] == 2 ?"担保申请未通过(担保金:￥{$bl[0]['apply_fee']})" :"正在平台担保申请中(担保金:￥{$bl[0]['apply_fee']})<br/>请耐心等待并随时关注动态…");
				$this->assign("descr",$descr);
			}
		}else{
			$this->assign("descr","(赏金:￥{$task[0]['price']})");
		}
		
		$this->assign("show_weiquan", $show_weiquan);
		if(!empty($bl[0])) {
			$this->assign("danbao", $bl[0]);
		}
		$pro_name = M("Province")->where("id=".$task[0]['province'])->select();
		$this->assign("pro_name", $pro_name[0]);
	$taskM->data(array('id'=>$id,'have_read'=>1))->save();	
		M("Bid")->where(array("task_id"=>$id))->save(array("has_read"=>1));
        $this->assign("bid", $bid);
		$this->display("taskdetail");
	}

	public function toevaluate(){
		$this->__global();
        $taskid = I("get.taskid", 1, "intval");
        $taskM = M("Main_task");
        $task = $taskM->where("id=".$taskid)->select();
        $this->assign("task", $task[0]);

		$this->display("toevaluate");
	}

	public function saveevaluate(){
		$this->__global();
		$evaM = M("Task_evaluate");
		$post = I("post.");
		$post['user_id'] = $this->session_uid;
		$jifen = intval($post['efficate'])+intval($post['quality'])+intval($post['attitude']);
		$realjf = 15;
		$post['create_time'] = time();
		$evaM->data($post)->add();
		$usersess = session("user");
		M("user")->where("id=".$post['accept_uid'])->setInc("jifen", $jifen);
		M("user")->where("id=".$post['accept_uid'])->setInc("realjf", $realjf);
		$this->display("evaluateSuccess");
	}
	//我投标的任务
	public function biddetail(){
		$this->__global();
        $id = I("get.id", 1, "intval");
        $taskM = M("Main_task");

        $task = $taskM->join("left join task_type on task_type.id= main_task.task_type_id")->where("main_task.id=".$id)->field("main_task.*,task_type.task_type_name")->select();
		$usersession = session("user");
		$hasbid = false;
		$hasbid = M("Bid")->where("task_id=".$id." and user_id=".$usersession['id'])->select();
		if(!empty($hasbid[0])) $hasbid = true;
		$this->assign("hasbid", $hasbid);
		$eve = M("Task_evaluate")->where("task_id=".$id." and user_id=".$usersession['id'])->select();
		$danbaolist = M("Danbao_apply")->where("task_id=".$id)->select();
		$this->assign("danbao", $danbaolist[0]);
		if($task[0]['price'] == 0){
			if(!empty($danbaolist[0])){
				$descr = $danbaolist[0]['status']==1 ? "项目担保中(担保金:￥{$danbaolist[0]['apply_fee']})" :"正在平台担保申请中(担保金:￥{$danbaolist[0]['apply_fee']})<br>请耐心等待并随时关注动态…";
				$this->assign("descr",$descr);
			}
		}else{
			$this->assign("descr","(赏金:￥{$task[0]['price']})");
		}
		$hasEve = false;
		$datas = array("bid_has_read"=>1);
		M("Bid")->where("task_id=".$id)->data($datas)->save();
		if(!empty($eve[0])) $hasEve=true;
		$this->assign("hasEve", $hasEve);
		
		$pro_name = M("Province")->where("id=".$task[0]['province'])->select();
		$this->assign("pro_name", $pro_name[0]);
		
        $this->assign("task", $task[0]);
		$this->display("biddetail");
	}
}
