<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
<style>
 body{padding:0px; margin:0px; font-size:14px; color:#fff; line-height:24px; font-family:"Microsoft Yahei", "Arial Unicode MS"; background:url(/order/Public/images/background.jpg) ;}
.loginWarp{ position:absolute; top:50%; width:800px; height:500px; border-radius:5px;  margin:-250px 0px 0px -400px; left:50%}
.loginBox{width:258px; margin:175px auto 0px auto}
.loginBox h1{font-size:30px; font-weight:bold; text-align:center}
.from{ margin-top:35px;}
.label{display:block; margin-bottom:18px; padding-left:20px;}
.label .input{background:#fff; border:0; border-radius:5px; line-height:25px; padding:0px 5px;}
.btn{ margin-top:30px;}
.btn input{background:none; border:solid 1px rgba(255,255,255,0.5); border-radius:5px; font-size:14px; color:#fff; padding:3px 20px; display:block; float:left; margin-left:20px; cursor:pointer;
box-shadow:0px 6px 20px 0px rgba(245,92,84,0.5);}

</style>
</head>
<body>
<div class="loginWarp">
  <div class="loginBox">
    <h3 style="text-align:center">智网云服后台</h3>
    <div class="from">
    	<div id="error_msg" style="color:red"></div>
      <div class="label"> <span>用户：</span>
        <input name="txtUserName" type="text" id="txtUserName">
        <div class="clear"></div>
      </div>
      <div class="label"> <span>密码：</span>
        <input name="txtUserPwd" type="password" id="txtUserPwd">
        <div class="clear"></div>
      </div>
      <div class="label btn">
        <input name="" type="button" onclick="submit_form()" value="登录" class="but">
        <input name="" type="reset" onclick="submit_reset()" value="重置" class="reset">
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
</body>
<script language="JavaScript" type="text/javascript" src="/order/Public/js/jquery-min.js"></script>
<script>
  function submit_form(){
    var name = $("#txtUserName").val();
    var pwd = $("#txtUserPwd").val();
    if(name=="" || pwd==""){
      $("#error_msg").html("用户名或密码不能为空");
      return false;
    }
    $.ajax({
          'url'  :"<?php echo U('login');?>",
          'data'   :{'name':name,"pwd":pwd},
          'type'   :'post',
          'success':function(msg){
            if(msg == 'success'){
              window.location.href="<?php echo U('order/index');?>";
            }else{
              $("#error_msg").html(msg);
              return false;
            }

          }
        })
  }

  function submit_reset(){
    $("#txtUserName").val('');
    $("#txtUserPwd").val('');
  }
</script>
</html>