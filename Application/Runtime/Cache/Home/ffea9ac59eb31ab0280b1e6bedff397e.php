<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta content="yes" name="apple-mobile-web-app-capable">
		<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
        <title>微信电伙</title>
        <link rel="stylesheet" href="/Public/css/uikit.css" /><!--框架css文件，勿改-->
        <link rel="stylesheet" href="/Public/css/iven.css" /><!--用户自定义css文件，所有写在这里，若与框架初始化样式不同，可以写新的覆盖-->
        <link rel="stylesheet" href="/Public/css/slideshow.css" /><!--首页图片切换-->
        <link rel="stylesheet" href="/Public/css/dotnav.css" /><!--首页图片切换圆点-->
    </head>
    <body>
    <!--header start-->
    <header>
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-small uk-flex-middle">
                <div class="uk-width-2-5">
                    <div class="logo"><a href=""><img src="/Public/images/<?php echo ($pic); ?>" alt=""></a></div>
                </div>
                <div class="uk-width-3-5">
                    <div class="post-btn uk-flex  uk-flex-row-reverse">
                        <?php if(isset($userinfo) && !empty($userinfo) && !empty($userinfo['telphone'])): ?><div><a href="<?php echo U('task/sendtask');?>" class="uk-button uk-button-primary">发布任务</a>
                            <a href="<?php echo U('task/index');?>" class="uk-button uk-button-yellow">任务接单</a></div><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>   
    </header>
<link href="/Public/css/common.css" rel="stylesheet">
<link href="/Public/css/newslist.css" rel="stylesheet">
<div class="content">
      <div class="w-1">
        <div class="null"><img src="/Public/images/404.png">
          <ul>
            <li> <a href="/store/ychdzx.html">阳澄湖大闸蟹</a></li>
            <li> <a href="/product/hxh.html">好蟹汇</a></li>
            <li> <a href="/product/putcrab.html">提蟹</a></li>
            <li class="active"> <a href="/">返回首页</a></li>
          </ul>
        </div>
      </div>
    </div>
 <!--footer-->

	<script src="/Public/js/jquery.js"></script>
    <script src="/Public/js/uikit.min.js"></script><!--框架js文件，勿改-->
	<script src="/Public/js/slideshow.min.js"></script><!--首页图片切换-->
    <script src="/Public/js/slideshow-fx.min.js"></script><!--首页图片切换增强效果-->
	<script src="/Public/js/form-select.min.js"></script>
    </body>
</html>