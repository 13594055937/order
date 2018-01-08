<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends GlobalController {
    public function index(){
      	$userInfo = session('user');
		if(empty($userInfo)){
			$this->redirect('login/index');
		}else{
			$this->redirect('order/index');
		}
    }

    public function login_out(){
    	session_destroy();
    	$this->redirect("login/index");
    }

    public function get_area_option(){
        $cityid = $_POST['cityid'];
        $areaMdl = M("Areas");
        $list = $areaMdl->where("cityid = $cityid")->select();
        
        foreach ($list as $key => $value) {
            $selected ="";
            if($key==0){
                $selected = " checked=true";
            }
            echo "<option value='{$value['id']}' {$selected}>{$value['name']}</option>" ;
        }
        exit;
    }
    public function erweima_manage(){
        $erMdl = M("Erweima");
        $list = $erMdl->select();
        $this->assign("data", $list[0]);
        $this->display("erweima_add");
    }
    
    public function erweima_save(){
        $post = I('post.');

        $useMdl = M('Erweima');
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728;// 设置附件上传大小
        $upload->exts      =     array('png','jpeg','jpg');// 设置附件上传类型
        $upload->rootPath  =     './Public/images/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件 
        $info   =   $upload->upload();
        $thumb_img = "";
        if(!$info){
            if(isset($post['img']) && $post['img'] != ""){
                $thumb_img = $post['img'];
            }else{
                $this->error("请上传正确格式的图片，(png,jpg,jpeg)","erweima_manage");
            }

        }else{
            $thumb_img = $info['img']['savepath'].$info['img']['savename'];
        }

        if($post['id']<=0){
            $data = array(
                'img' => $thumb_img,
            );
            $return = $useMdl->data($data)->add();
        }else{
            $data = array(
                'id' => $post['id'],
                'img' => $thumb_img,
            );
            $return = $useMdl->save($data);
        }
        if($return === FALSE){
            $this->error('保存失败！','erweima_manage');
        }else{
            $this->success('保存成功！','erweima_manage');
        }
    }
}
