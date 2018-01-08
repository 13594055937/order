<?php
namespace Home\Controller;
use Think\Controller;
class NewsController extends GlobalController {
	function _initialize(){
		$this->_logined();
		$path_info = $_SERVER['PATH_INFO'];
       		$path_info = explode("/", $path_info);
		if($path_info[1] == 'gsxw'){
			$_GET['news_type'] = '公司新闻';
			$this->newslist();
			exit;
		}
		if($path_info[1] == 'hydt'){
                        $_GET['news_type'] = '行业动态';
                        $this->newslist();exit;
                }
		if($path_info[1] == 'ssxy'){
                        $_GET['news_type'] = '世说蟹语';
                        $this->newslist();exit;
                }
		if($path_info[1] == 'ychzx' || $path_info[1] == 'xwzx'){
                        $_GET['news_type'] = '阳澄湖资讯';
                        $this->newslist();exit;
                }
		if(strpos($path_info[1],"news_") !== false){
			$id = explode("_",$path_info[1]);
                        $_GET['id'] = $id[1];
                        $this->newsdetail();exit;
                }	
		if(strpos($path_info[1],"videos_") !== false){
                        $id = explode("_",$path_info[1]);
                        $_GET['id'] = $id[1];
                        $this->videodetail();exit;
                }
	
		
	}
	

    /**
     * 全控制器设置
     * 
     */
    private function __global(){
        $this->assign("sonNav","好蟹汇-新闻中心");
	$this->assign("curclass","news");
    }

    public function newslist(){
        $this->__global();
        $get = I("get.");
        $p = I('get.p',1,'intval');
        $news_type = isset($get['news_type']) ? $get['news_type'] : "公司新闻";
        $newsM = M("News");
        $pageSize = 6;
        $where_str = " news_type ='".$news_type."'";
        $count = $newsM->where($where_str)->count();
        $Page = new \Think\Page($count,$pageSize);
        $show = $Page->show(); 
        $this->assign('page',$show);// 赋值分页输出
        $showall = isset($_GET['showall']) ? 1:0;
        if($showall && is_mobile()){
            $list = $newsM->where($where_str)->order('paixu DESC,id desc')->select();
        }else{
            $list = $newsM->where($where_str)->order('paixu DESC,id desc')->limit(($p-1)*$pageSize.','.$pageSize)->select();
        }
        $this->assign("newslist", $list);
        $this->assign("news_type", $news_type);

        $this->assign("showall", $showall);
        $this->display('newslist'); 
	exit;
    }
    
    public function newsdetail(){
        $this->__global();
        
        $get = I("get.");
        $id = intval($get['id']);
        $newsM = M("News");
        $data = $newsM->where("id=".$id)->find();
        if(!empty($data)){
            $preNews = $newsM->where("news_type='".$data['news_type']."' and id<".$id)->order(" id desc")->limit(1)->select();
            $nextNews = $newsM->where("news_type='".$data['news_type']."' and id>".$id)->order(" id asc")->limit(1)->select();
            $this->assign("preNews", $preNews[0]);
            $this->assign("nextNews", $nextNews[0]);
        }
	if($data['news_type'] == '公司新闻'){
		$news_jianjie = 'gsxw';
	}
	if($data['news_type'] == '行业动态'){
                $news_jianjie = 'hydt';
        }
	if($data['news_type'] == '世说蟹语'){
                $news_jianjie = 'ssxy';
        }
	if($data['news_type'] == '阳澄湖资讯'){
                $news_jianjie = 'ychzx';
        }
	$this->assign("news_jianjie", $news_jianjie);
        $this->assign("data", $data);
        $this->assign("sonNav","好蟹汇-新闻详情");
        $this->display('newsdetail'); 
	exit;
    }

    public function videolist(){
        $this->__global();
        $get = I("get.");
        $p = I('get.p',1,'intval');
        $newsM = M("Videos");
        $pageSize = 6;
        $count = $newsM->count();
        $Page = new \Think\Page($count,$pageSize);
        $show = $Page->show(); 
        $this->assign('page',$show);// 赋值分页输出
        $showall = isset($_GET['showall']) ? 1:0;
        if($showall && is_mobile()){
            $list = $newsM->order('paixu DESC,id desc')->select();
        }else{
            $list = $newsM->order('paixu DESC,id desc')->limit(($p-1)*$pageSize.','.$pageSize)->select();
        }
        $this->assign("videolist", $list);

        $this->assign("showall", $showall);

        $this->assign("sonNav","好蟹汇-视频中心");
        $this->display('videolist'); 
    }
    
    public function videodetail(){
        $this->__global();
        $get = I("get.");
        $id = intval($get['id']);
        $newsM = M("Videos");
        $data = $newsM->where("id=".$id)->find();
        if(!empty($data)){
            $preNews = $newsM->where("id<".$id)->order(" id desc")->limit(1)->select();
            $nextNews = $newsM->where("id>".$id)->order(" id asc")->limit(1)->select();
            $this->assign("preVideos", $preNews[0]);
            $this->assign("nextVideos", $nextNews[0]);
        }
        $this->assign("data", $data);
        $this->assign("detail", $data);
        $this->assign("sonNav","好蟹汇-视频详情");
        $this->display('videodetail'); 
    }

}
