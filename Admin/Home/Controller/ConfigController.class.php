<?php
namespace Home\Controller;
use Think\Controller;
class ConfigController extends GlobalController {
	function _initialize(){
		$this->_logined();
	}
	
	/**
	 * 全控制器设置
	 * 
	 */
	 private function __global(){
	 	$this->topNav = "user";
	 }
	/**
	 * 
	 * eagle_91@sina.com
	 * created 2014-09-10
	 */
    public function index(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('History_operation');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $
	    $list = $userMdl->order('id DESC')->where("operator_name='{$userInfo['username']}'")->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('index'); 
    }
	
	public function apply_list(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$apply_deal = I('post.apply_deal',"未读","trim");
		if(empty($apply_deal)){
			$apply_deal = "未读";
		}
		$pageSize = C('admin_pageCount');
	    $userMdl = M('User');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $where = 'pwd = "" and apply_deal="'.$apply_deal.'"';
	    if($apply_deal == "已处理"){
	    	$where = 'apply_deal="'.$apply_deal.'"';
	    }
	    $list = $userMdl->order('id DESC')->where($where)->select();
		/**$configC = C('role');
		foreach($list as $key=>$val){
			$list[$key]['roleName'] = $configC[$val['role']];
			$list[$key]['dateTime'] = date('Y-m-d H:i:s',$val['created']);
		}**/
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	    $this->assign('apply_deal', $apply_deal);
		$this->display('apply_list'); 
    }

    public function jifen(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$user_id = I('get.user_id',0,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('Jifen_log');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->field('username,jifen_log.jifen,reason,from_unixtime(time) as time')->join("user on user.id=jifen_log.userid")->order('jifen_log.id DESC')->select();
		$username = "";
		if($user_id != 0){
			$list = $userMdl->field('username,jifen_log.jifen,reason,from_unixtime(time) as time')->join("user on user.id=jifen_log.userid")->order('jifen_log.id DESC')->where("user.id=".$user_id)->select();
			$usM = M("Base_customer");
			$user_list =  $usM->where("id=".$user_id)->select();
			$username = $user_list[0]['username'];
		}
		/**$configC = C('role');
		foreach($list as $key=>$val){
			$list[$key]['roleName'] = $configC[$val['role']];
			$list[$key]['dateTime'] = date('Y-m-d H:i:s',$val['created']);
		}**/

	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	    $this->assign('user_id', $user_id);
	    $this->assign('username', $username);
		$this->display('jifen');
    }

    /**管理员积分添加和扣除**/
    public function jifen_save(){
    	$post = I('post.');
		if(empty($post['username']) || empty($post['reason']) || empty($post['jifen'])){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数")));
		}
		$useMdl = D('Jifen_log');
		
		$useMdl->create();
		if(false){
			exit(json_encode(array("status"=>"false","msg"=>"1保存失败！")));
		}

		$use = D('User');
		$list = $use->where("username='".$post['username']."'")->select();
		if(empty($list)){
			exit(json_encode(array("status"=>"false","msg"=>"填写的用户名不存在")));
		}

		if($list[0]['jifen'] + $post['jifen'] <0){
			exit(json_encode(array("status"=>"false","msg"=>"只剩下".$list[0]['jifen']."积分可扣除了")));
		}

		$data = array(
			'id' => $list[0]['id'],
			'username' => $post['username'],
			'jifen' => $list[0]['jifen'] +  $post['jifen'],
		);
		$use->create();
		$return = $use->save($data);
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}
		$data2 = array(
				'jifen'=>$post['jifen'],
				'userid'=>$list[0]['id'],
				'reason'=>$post['reason'],
				'time'=>time(),
			);
		$return = $useMdl->data($data2)->add();
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
		}
    }
	/**
	 * 接口地址
	 * 
	 * 
	 */
	public function add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('User');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$head_title_Mdl = M('Head_title');
		$list = $head_title_Mdl->select();
		$this->assign("head_title_list", $list);
		$this->display('add');
	}
	
	public function jifen_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		$user_id = I('get.user_id',0,'intval');
		$username = I('get.username',"","trim");
		if($id>0){
			$userMdl = M('Jifen_log');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->assign("username", $username);
		$this->assign("user_id", $user_id);
		$this->display('jifen_add');
	}
	/**
	 * 保存配置ip
	 * 
	 */
	public function deal_applay(){
		$id = I('get.id','',"intval");
		if($id==""){
			exit;
		}
		$useMdl = D('User');
		$data = array('id'=>$id, "apply_deal"=>'已处理');
		$useMdl->save($data);
		$this->redirect("apply_list");
	}

	public function save(){
		$post = I('post.');
		$useMdl = D('User');
		
		if($post['passwd'] != $post['cmpwd']){
			exit(json_encode(array("status"=>"false","msg"=>"密码不统一！")));
		}
		
		$vali = $this->_valiPwd($post['passwd']);
		
		$useMdl->create();
		if(false){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}
		$user_list = $useMdl->where("username='".$post['username']."'")->select();
		if(empty($user_list[0])){
			$data = array(
				'club_id' => $post['club_id'],
				'username'=>$post['username'],
				'pwd' => $post['passwd'],
				'head_title'=>$post['head_title'],
				'apply_deal'=>'已处理',
				'create_time'=>time()
			);
			$return = $useMdl->data($data)->add();
		}else{
			$data = array(
				'id' => $user_list[0]['id'],
				'club_id' => $post['club_id'],
				'username'=>$post['username'],
				'pwd' => $post['passwd'],
				'head_title'=>$post['head_title'],
				'apply_deal'=>'已处理'
			);
			$return = $useMdl->save($data);
		}
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
		}
	}
	
	public function dongjie(){
		$get = I('get.');
		$id = $get['id'];
		$userMdl = M("Base_customer");
		$data = array('id'=>$id, 'status'=>0);
		$userMdl->data($data)->save();
		$this->index();
	}

	public function jiefen(){
		$get = I('get.');
		$id = $get['id'];
		$userMdl = M("Base_customer");
		$data = array('id'=>$id, 'status'=>1);
		$userMdl->data($data)->save();
		$this->index();
	}

	public function create_pwd(){
		$chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',0,1,2,3,4,5,6,7,8,9);
		$new_pwd = '';
		for($i=0; $i<8;$i++){
			$new_pwd .= $chars[mt_rand(0,35)];
		}
		return $new_pwd;
	}

	public function pass_createpwd(){
		$id = I('get.id',0,'intval');
		$useMdl = D('User');
		$new_pwd = $this->create_pwd();
		$data = array(
			'id' => $id,
			'pwd' => $new_pwd,
		);
		$return = $useMdl->save($data);
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！密码是".$new_pwd)));
		}
	}

	/**
	 * 验证密码
	 * 
	 */
	private function _valiPwd($val){
		$return = preg_match('/^[a-zA-Z0-9_]{6,15}/', $val);
		return $return == 0?FALSE:TRUE;
	}
	/**
	 * 删除ip
	 * 
	 */
	function del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('User');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

	 function index_list_del(){
                $id = I('get.id',0,'intval');
                if($id<=0){
                        exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
                }
                $userMdl = M('Index_list');
                $userMdl->delete($id);
		
		
                exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
        }	

	public function index_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Index_list');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('index_add');
	}

	public function index_save(){
		$post = I('post.');
		$useMdl = D('Index_list');
		$upload = new \Think\Upload();// 实例化上传类
	    $upload->maxSize   =     3145728 ;// 设置附件上传大小
	    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	    $upload->rootPath  =     './Public/images/'; // 设置附件上传根目录
	    $upload->savePath  =     ''; // 设置附件上传（子）目录
	    // 上传文件 
	    $info   =   $upload->upload();
	    if(!$info) {// 上传错误提示错误信息
		   	$pic = $post['bk_img'];
		   	$post['bk_img'] = $pic;
	    }
	    if(!isset($pic)){// 上传成功
	    	$pic = '';
	    	foreach($info as $file){
		        $pic .= $file['savepath'].$file['savename'];
		    }
		    $post['bk_img'] = $pic;
	    }

		if($post['id']<=0){
			$data = $post;
			$return = $useMdl->data($data)->add();
		}else{
			$data = $post;
			$return = $useMdl->save($data);
		}
		if($return === FALSE){
			$this->error("保存失败!", "index_list");
		}else{
			$this->success("保存成功！!", "index_list");
		}
	}

	public function index_list(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = 30;//C('admin_pageCount');
	    $userMdl = M('Index_list');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show(); 
	    $list = $userMdl->order("parent_id asc")->select();
		/**$configC = C('role');
		foreach($list as $key=>$val){
			$list[$key]['roleName'] = $configC[$val['role']];
			$list[$key]['dateTime'] = date('Y-m-d H:i:s',$val['created']);
		}**/
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('index_list'); 
    }

    public function show_user(){
    	$user_id = intval($_GET['id']);
    	$userMdl = M('User');
    	$list = $userMdl->where("id = ".$user_id)->select();
    	$data=array("id"=>$user_id,"apply_deal"=>"已读");
    	$userMdl->data($data)->save();
    	$this->assign('data', $list[0]);
    	$this->display("show_user");
    }

    public function show_user_detail(){
    	$this->__global();
		$user_id = I("get.user_id",0,"intval");
		$userMdl = M("Base_customer");
		$list =  $userMdl->where("id=".$user_id)->select();
		$this->assign("list",$list[0]);
		$this->assign("user_id",$user_id);
    	$this->assign("health_name","show_user_detail");
    	$this->display("show_user_detail");
    }

    public function user_info_edit(){
    	$post = I("post.");
    	$user_id = I("get.user_id",0,"intval");
    	$userMdl = M("Base_customer");
    	$post['id'] = $user_id;
    	$userMdl->data($post)->save();
    	exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
    }

    public function show_topic_post(){
    	$this->__global();
		$post = I('post.');
		$p = I('get.p',1,'intval');
		$user_id = I("get.user_id",0,"intval");
		$pageSize = 8;// C('admin_pageCount');
	    $userMdl = M('Topic');
	    $count = $userMdl->where("userid=".$user_id)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
		$bbsMd = D('Topic');
		$list = $bbsMd->field('*,timediff(now(), from_unixtime(last_reply_time)) as reply_time,from_unixtime(time) as post_time,topic.id as topic_id')->join("user on user.id=topic.userid")->where("userid=".$user_id)->select(); 
		
		$topic_title = array(0,'最新分享','旅行游记','养生之道','活动分享','八卦闲聊');
		$this->assign('topic_title', $topic_title);
		$this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);
	    $this->assign("health_name","show_topic_post");
	    $this->assign("user_id",$user_id);
    	$this->display("show_user_detail");
    }

    public function show_shoucang(){
    	$this->__global();
		$post = I('post.');
		$p = I('get.p',1,'intval');
		$pageSize = 8;// C('admin_pageCount');
	    $userMdl = M('Topic');
		$user_id = I("get.user_id",0,"intval");
	    $count = $userMdl->where("userid=".$user_id)->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
		$bbsMd = D('Shoucang');
		$list = $bbsMd->field('*,timediff(now(), from_unixtime(last_reply_time)) as reply_time,from_unixtime(topic.time) as post_time,topic.id as topic_id, shoucang.id as shoucang_id')->join("topic on topic.id=shoucang.post_id")->join("user on user.id=topic.userid")->where("user_id=".$user_id)->select(); 
		$topic_title = array(0,'最新分享','旅行游记','养生之道','活动分享','八卦闲聊');
		$this->assign('topic_title', $topic_title);
		$this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
    	$this->assign("health_name","show_shoucang");
    	$this->assign("user_id",$user_id);
    	$this->display("show_user_detail");
    }

    public function out_file(){
    	$this->__global();
		$get = I('get.');
		$user_id = $get['user_id'];
		$activity_name = $get['activity_name'];
		$file_name = date("Y_m_d")."_{$user_id}_{$activity_name}".".xls";
		header("Content-type:application/vnd.ms-excel"); 
        header("Content-Disposition:attachment; filename={$file_name}");
        if($activity_name == "jifen"){
        	echo iconv("UTF-8","GBK",'用户名')."\t".iconv("UTF-8","GBK","会员登录ID")."\t".iconv("UTF-8","GBK","积分操作原因")."\t".iconv("UTF-8","GBK","头衔")."\t".iconv("UTF-8","GBK","操作积分数")."\t".iconv("UTF-8","GBK","现有积分数")."\t".iconv("UTF-8","GBK","操作时间")."\n";
        	$Md = M('Jifen_log');
        	$list = $Md->join("user on user.id= jifen_log.userid")->field("username,club_id,reason,head_title,jifen_log.jifen as jifen1,user.jifen as now_jifen,from_unixtime(time)")->where("userid=".$user_id)->select();
        }
        if(in_array($activity_name,array('product','shalong','huodong_anpai'))){
        	echo iconv("UTF-8","GBK",'用户名')."\t".iconv("UTF-8","GBK","联系方式")."\t".iconv("UTF-8","GBK","邮箱")."\t".iconv("UTF-8","GBK","使用积分")."\t".iconv("UTF-8","GBK","报名人数")."\t".iconv("UTF-8","GBK","预约状态")."\n";
        	$Md = M("Subscribe_".$activity_name);
        	$list = $Md->join("user on user.id= Subscribe_".$activity_name.".user_id")->field("Subscribe_".$activity_name.".username,talk_way,Subscribe_".$activity_name.".email,use_jifen,person_nums,Subscribe_".$activity_name.".status as sub_status")->where("user_id=".$user_id)->select();
        }
        if(in_array($activity_name,array('person_answer','health_record','dispath_vegetables'))){
        	echo iconv("UTF-8","GBK",'用户名')."\t".iconv("UTF-8","GBK","会员登录ID")."\t".iconv("UTF-8","GBK","问题")."\t".iconv("UTF-8","GBK","头衔")."\t".iconv("UTF-8","GBK","医生回答")."\t".iconv("UTF-8","GBK","提问时间")."\t".iconv("UTF-8","GBK","回复时间")."\n";
        	$Md = M('Person_answer');
        	$list = $Md->join("user on user.id= person_answer.user_id")->field("username,club_id,question,head_title,answer_content,from_unixtime(person_answer.create_time),from_unixtime('reply_time')")->where("user_id=".$user_id)->select();
        }
        foreach ($list as $key=>$v)
        {
        //输出到xls
        	foreach ($v as $k1 => $value) {
        		if($k1 == "sub_status"){
	        		if(intval($value) == 2){
	        			$value="不通过";
	        		}elseif(intval($value) == 1){
	        			$value = "通过";
	        		}else{
	        			$value = "正在审核";
	        		}
	        	}
	            echo iconv("UTF-8","GBK",$value)."\t";  
        	}
        	echo "\n";
        }
        eixt;
	    return ;
    }

    public function head_title(){
    	$Mdl  = M('Head_title');
    	$list = $Mdl->select();
    	$this->assign('list', $list);
    	$this->display("head_title_index");
    }


    public function head_title_del(){
        $id = I('get.id',0,'intval');
        if($id<=0){
            exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
        }
        $userMdl = M('Head_title');
        $userMdl->delete($id);
        exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
    }	

	public function head_title_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Head_title');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->display('head_title_add');
	}

	public function head_title_save(){
		$post = I('post.');
		$useMdl = M('Head_title');
		if(empty($post['id'])){
			$data = array("name"=>$post['name']);
			$useMdl->data($data)->add();
		}else{
			$data = array("id"=>$post['id'],"name"=>$post['name']);
			$useMdl->data($data)->save();
		}
		exit(json_encode(array("status"=>"true","msg"=>"添加成功！")));
	}

	function main_help(){
		$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('Main_help');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->order('id DESC')->select();
		
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('help_index');
	}

	public function help_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Main_help');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->assign('roleList',C('role'));
		$this->display('help_add');
	}

	public function help_save(){
		$post = I('post.');
		if(empty($post['title']) || empty($post['content'])){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数")));
		}
		$useMdl = D('Main_help');
		
		$useMdl->create();
		if(false){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}
		if($post['id']<=0){
			$data = array(
				'title' => $post['title'],
				'content' => $post['content'],
				'zaiyao' => $post['zaiyao'],
				'from_target' => $post['from_target'],
				'paixu'=>$post['paixu'],
				'create_time' => time()
			);
			$return = $useMdl->data($data)->add();
		}else{
			$data = array(
				'id' => $post['id'],
				'title' => $post['title'],
				'content' => $post['content'],
				'zaiyao' => $post['zaiyao'],
				'from_target' => $post['from_target'],
				'paixu'=>$post['paixu'],
				'create_time' => time()
			);
			$return = $useMdl->save($data);
		}
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
		}
	}

	public function news(){
		$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('News');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->order('id DESC')->select();
		
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('news_index');
	}

	public function news_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('News');
			$data = $userMdl->find($id);
			$this->assign('data',$data);
		}
		$this->assign('roleList',C('role'));
		$this->display('news_add');
	}

	public function news_save(){
		$post = I('post.');
		if(empty($post['title'])){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数")));
		}
		$useMdl = D('News');
		
		$useMdl->create();
		if(false){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}
		$post['create_time'] = date("Y-m-d");
		if($post['id']<=0){
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
		}
	}

	public function news_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('News');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}


	public function admin_index(){
    	$this->__global();
		$p = I('get.p',1,'intval');
		$pageSize = C('admin_pageCount');
	    $userMdl = M('Admin_user');
	    $count = $userMdl->count();
	    $Page = new \Think\Page($count,$pageSize);
	    $show = $Page->show();
	    $list = $userMdl->order('id DESC')->select();
	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
		$this->display('admin_index'); 
    }

    public function admin_add(){
		$this->__global();
		$id = I('get.id',0,'intval');
		if($id>0){
			$userMdl = M('Admin_user');
			$data = $userMdl->find($id);
			$this->assign('data2',$data);
		}
		$this->display('admin_add');
	}

	public function admin_save(){
		$post = I('post.');
		$useMdl = M('Admin_user');
		
		if($post['pwd'] != $post['cmpwd']){
			exit(json_encode(array("status"=>"false","msg"=>"密码不统一！")));
		}
		$user_list = $useMdl->where("username='".$post['username']."'")->select();
		unset($post['cmpwd']);
		if($post['visit_list'] !=''){
			$post['visit_list'] = implode(',', $post['visit_list']);
		}
		if(empty($user_list[0])){
			$return = $useMdl->data($post)->add();
		}else{
			$return = $useMdl->save($post);
		}
		if($return === FALSE){
			exit(json_encode(array("status"=>"false","msg"=>"保存失败！")));
		}else{
			exit(json_encode(array("status"=>"true","msg"=>"保存成功！")));
		}
	}

	function admin_del(){
		$id = I('get.id',0,'intval');
		if($id<=0){
			exit(json_encode(array("status"=>"false","msg"=>"丢失参数！")));
		}
		$userMdl = M('Admin_user');
		$userMdl->delete($id);
		exit(json_encode(array("status"=>"true","msg"=>"删除成功！")));
	}

}
