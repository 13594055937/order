<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta content="yes" name="apple-mobile-web-app-capable">
		<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
        <title>我要派单</title>
        <link rel="stylesheet" href="/Public/css/uikit.css" />
		<link rel="stylesheet" href="/Public/css/iven.css" />
        <link rel="stylesheet" href="/Public/css/form-select.css" />
        <script src="/Public/js/jquery.js"></script>
        <script src="/Public/js/uikit.min.js"></script>
        <script src="/Public/js/form-select.min.js"></script>
    </head>
    <body>
	<div class="uk-container uk-container-center">
		<style>
			#switch-option option span{ font-size:14px; color:#666;}
		</style>
		<script>
			//select监听事件
			$(document).ready(function() {
                $("#submitit").click(function(){
                    var company = $.trim($("#company").find('option:selected').val());
                    var jobtype = $.trim($("#jobtype").find('option:selected').val());
                    var hopetime = $.trim($("#hopetime").val());
                    var jobcontent = $.trim($("#jobcontent").val());
                    if(company == "" || jobtype == "" || hopetime == "" || jobcontent == ""){
                        alert("必填项不能为空");
                        return false;
                    }
                    $("form").submit();
                })
                $("#reset_form").click(function(){
                    window.location.reload();
                    return false;
                })
			});
		</script>

        <form class="uk-form assi-form" action="<?php echo U('task/savetask');?>" method="post">
            <!-- 这里选择 - 切换-->
            <h2 class="iv-h2">公司名称 <span class="uk-float-right"><i class="red">*</i>必填</span></h2>
            <div class="assi-form">
                <div class="uk-form-select" data-uk-form-select>
                    <span></span>
                    <select id="company" name='customerId'>
                        <option value="">请选择公司</option>
                        <?php if(is_array($company_list)): foreach($company_list as $key=>$company): ?><option value="<?php echo ($company["name"]); ?>" <?php if(isset($userinfo) && $userinfo['company'] == $company['name']): ?>selected<?php endif; ?>><?php echo ($company["name"]); ?></option><?php endforeach; endif; ?>
                    </select>
                </div>
            </div>
            <div></div> <!--左边空div是第0个 - 对应 ‘请选择任务类型’-->
            <div class="switch-1">
            <input type='hidden' id="type_name" value="<?php echo ($type); ?>" >
			<h2 class="iv-h2">期望时间 <span class="uk-float-right"><i class="red">*</i>必填</span></h2>
            <input type="text" name="hopetime" id="hopetime" placeholder="请选择期望时间" />
            <ul class="uk-grid uk-grid-width-1-4">
                <li><label><input type="checkbox" name="extrademand[]" value="尽快" class="design_list" >尽快</label></li>
                <li><label><input type="checkbox" name="extrademand[]" value="加急" class="design_list" >加急</label></li>
            </ul>
            <h2 class="iv-h2">维护分类 <span class="uk-float-right"><i class="red">*</i>必填</span></h2>
            <div class="uk-grid">
                <div class="uk-width">
                    <div class="uk-form-select" data-uk-form-select>
                        <span></span>
                        <select id="jobtype" name="jobtype">
                            <option value="">请选择维护类型</option>
                            <?php if(is_array($job_type_list)): foreach($job_type_list as $key=>$job_type): ?><option value="<?php echo ($job_type["name"]); ?>"><?php echo ($job_type["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <h2 class="iv-h2">维护说明 <span class="uk-float-right"><i class="red">*</i>必填</span></h2>
            <textarea name="jobcontent" id="jobcontent" cols="30" rows="6" placeholder="请简述维护说明"></textarea>
            <div class="uk-text-center">
                <button class="uk-margin-top uk-button uk-button-primary" id="submitit">
                    <i class="uk-icon-mouse-pointer uk-icon-justify"></i>提交
                </button>
                &nbsp;&nbsp;
                <button class="uk-margin-top uk-button uk-button-primary" id="reset_form">
                    <i class="uk-icon-mouse-pointer uk-icon-justify"></i>重填
                </button>
            </div>
		</div>
        </form>
    </div>
     <!--footer-->

	<script src="/Public/js/jquery.js"></script>
    <script src="/Public/js/uikit.min.js"></script><!--框架js文件，勿改-->
	<script src="/Public/js/slideshow.min.js"></script><!--首页图片切换-->
    <script src="/Public/js/slideshow-fx.min.js"></script><!--首页图片切换增强效果-->
	<script src="/Public/js/form-select.min.js"></script>
    </body>
</html>