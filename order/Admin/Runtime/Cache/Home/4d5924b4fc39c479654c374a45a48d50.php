<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>智网云服</title>

    <!-- Bootstrap Core CSS -->
    <link href="/Public/admin_bootstrap/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="/Public/admin_bootstrap/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="/Public/admin_bootstrap/dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/Public/admin_bootstrap/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <!--<link href="/Public/admin_bootstrap/bower_components/morrisjs/morris.css" rel="stylesheet">-->

    <!-- Custom Fonts -->
    <link href="/Public/admin_bootstrap/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="/Public/admin_bootstrap/bower_components/jquery/dist/jquery.min.js"></script>
    <link type="text/css" rel="stylesheet" href="/Public/js/loadmask/jquery.loadmask.css" />
    <link type="text/css" rel="stylesheet" href="/Public/js/alerts/jquery.alerts.css" />
    <script type="text/javascript" src="/Public/js/main_admin.js"></script>
    <script type="text/javascript" src="/Public/js/loadmask/jquery.loadmask.js"></script>
    <script type="text/javascript" src="/Public/js/alerts/jquery.alerts.js"></script>
    <script type="text/javascript" src="/Public/Ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="/Public/Ueditor/ueditor.all.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.datetimepicker.js"></script>
    <link type="text/css" rel="stylesheet" href="/Public/css/jquery.datetimepicker.css" />
    <!--
    <script type="text/javascript" src="/Public/js/jquery.mobile.js"></script>
    <link type="text/css" rel="stylesheet" href="/Public/css/jquery.mobile.css" />
    -->
    <script type="text/javascript" src="/Public/jquery/jquery.validationEngine-zh_CN.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.validate.js"></script>
    <script type="text/javascript" src="/Public/js/jquery-migrate-1.1.1.js"></script>
    <script type="text/javascript" src="/Public/jquery/jqueryFormPlugin.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<style>
    .col-name{
        text-align: center;
    }    
</style>
<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">工单管理系统</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> 用户<?php echo ($admin_user["username"]); ?></a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="window.location.href='<?php echo U('index/login_out');?>'"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

             <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="<?php echo U('user/admin_index');?>"><i class="fa fa-sitemap fa-fw"></i> 用户管理<span class="fa arrow"></span></a>
                        </li>
                        <li>
                            <a href="<?php echo U('company/index');?>"><i class="fa fa-expand fa-fw"></i> 公司管理<span class="fa arrow"></span></a>
                        </li>
                        <li>
                            <a href="<?php echo U('customer/index');?>"><i class="fa fa-share fa-fw"></i> 客户管理<span class="fa arrow"></span></a>
                        </li>
                        <li>
                            <a href="<?php echo U('engineers/index');?>"><i class="fa fa-road fa-fw"></i> 工程师管理<span class="fa arrow"></span></a>
                        </li>
                        <li>
                            <a href="<?php echo U('order/index');?>"><i class="fa fa-wrench fa-fw"></i> 工单查询<span class="fa arrow"></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-inbox fa-fw"></i> 基础数据管理<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="<?php echo U('infomanage/engineerrate');?>">工程师人员评级</a></li>
                                <li><a href="<?php echo U('infomanage/engineertype');?>">工程师人员分类</a></li>
                                <li><a href="<?php echo U('infomanage/jobtype');?>">需求问题分类</a></li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            


<div role="main" class="ui-content">
	
    <div class="cp_right">
        <div class="mar">
        	<p id="title">
        		<b class="wlsz_back"></b>
        		<a href="<?php echo U('user/index');?>">用户管理</a>
        	</p>
            <div class="wlsz_jkdz right_bot">
            	<form role="form" action="<?php echo U('user/admin_save');?>" method="POST" id="myForm">
            	<input type="hidden" name="id" value="<?php echo ($data["id"]); ?>" readyonly />
                <h2><?php echo ($edit_info); ?> </h2>
             	<table class="table table-striped table-bordered table-hover">
                    <tr>
                        <td class="col-name">用户编号</td>
                        <td>
                            <input name="usercode" type="text" value="<?php echo ($data["usercode"]); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-name">用户名</td>
                        <td>
                            <input name="username" type="text" value="<?php echo ($data["username"]); ?>">
                        </td>
                    </tr>
                    <?php if(empty($data['id'])): ?><tr>
                        <td class="col-name">密码</td>
                        <td>
                            <input name="userpwd" type="password" value="<?php echo ($data["userpwd"]); ?>">
                        </td>
                    </tr><?php endif; ?>
                    <tr>
                        <td class="col-name">手机</td>
                        <td>
                            <input name="mobile" type="text" value="<?php echo ($data["mobile"]); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-name">关联微信</td>
                        <td>
                            <input name="openid" type="text" value="<?php echo ($data["openid"]); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-name">邮箱</td>
                        <td>
                            <input name="email" type="text" value="<?php echo ($data["email"]); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-name">用户类型</td>
                        <td>
                            <select name="usertype">
                                <?php if(is_array($usertype_list)): foreach($usertype_list as $k=>$usertype): ?><option value="<?php echo ($usertype); ?>" <?php if($data['usertype'] == $k): ?>selected<?php endif; ?>><?php echo ($usertype); ?></option><?php endforeach; endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="col-name">所属公司</td>
                        <td>
                           <select name="company">
                               <?php if(is_array($company_list)): foreach($company_list as $key=>$company): ?><option value="<?php echo ($company["name"]); ?>" <?php if($data['company'] == $company['name']): ?>selected<?php endif; ?>><?php echo ($company["name"]); ?></option><?php endforeach; endif; ?>
                           </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="col-name">状态</td>
                        <td>
                            <div data-role="controlgroup" data-type="horizontal">
                                <label>
                                    停用<input name="status" type="radio" value="0" <?php if($data['status'] == 0): ?>checked=true<?php endif; ?>>
                                </label>
                                <label>启用<input name="status" type="radio" value="1" <?php if($data['status'] == 1 || $data['status'] == '' ): ?>checked=true<?php endif; ?>></label>
                            </div>
                        </td>
                    </tr>
                    <tr><td colspan="2">管理分组</td></tr>
                    <tr>
                        <td style="width:200px;">用户管理 </td>
                        <td>
                         <input type="checkbox" name="user[]" value="用户管理" <?php if(isset($data['usergroup']['user'])&&in_array('用户管理',$data['usergroup']['user'])): ?>checked<?php endif; ?>>用户管理
                        </td>
                    </tr>
                    <tr>
                        <td style="width:200px;">公司管理 </td>
                        <td>
                         <input type="checkbox" name="company[]" value="公司管理" <?php if(isset($data['usergroup']['company'])&&in_array('公司管理',$data['usergroup']['company'])): ?>checked<?php endif; ?>>公司管理
                        </td>
                    </tr>
                    <tr>
                        <td style="width:200px;">客户管理</td>
                        <td>
                         <input type="checkbox" name="customer[]" value="客户管理" <?php if(isset($data['usergroup']['customer'])&&in_array('客户管理',$data['usergroup']['customer'])): ?>checked<?php endif; ?>>客户管理
                        </td>
                    </tr>
                    <tr>
                        <td style="width:200px;">工程师管理</td>
                        <td>
                         <input type="checkbox" name="engineer[]" value="工程师管理" <?php if(isset($data['usergroup']['engineer'])&&in_array('工程师管理',$data['usergroup']['engineer'])): ?>checked<?php endif; ?>>工程师管理
                        </td>
                    </tr>
                    <tr>
                        <td style="width:200px;">工单查询</td>
                        <td>
                            <input type="checkbox" name="order[]" value="工单查询" <?php if(isset($data['usergroup']['order'])&&in_array('工单查询',$data['usergroup']['order'])): ?>checked<?php endif; ?>>工单查询
                        </td>
                    </tr>
                    <tr>
                        <td style="width:200px;">基础数据管理</td>
                        <td>
                            <input type="checkbox" name="base[]" value="基础数据管理" <?php if(isset($data['usergroup']['base'])&&in_array('基础数据管理',$data['usergroup']['base'])): ?>checked<?php endif; ?>>基础数据管理
                        </td>
                    </tr>

                    <tr>
                        <?php if($edit_info != '查看信息'): ?><td colspan="2" align="center">
                                <input type="submit" class="search" data-inline="true" value="提交">
                            </td><?php endif; ?>
                    </tr>
                </table>
                <input type="button" value="返回" onclick="window.history.back(-1)" data-inline="true">
            	</form>
             </div>
            </div>
        </div>
    </div>
</div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    

    <!-- Bootstrap Core JavaScript -->
    <script src="/Public/admin_bootstrap/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="/Public/admin_bootstrap/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="/Public/admin_bootstrap/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="/Public/admin_bootstrap/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="/Public/admin_bootstrap/dist/js/sb-admin-2.js"></script>

</body>

</html>
<script>
    //查询
    $("#search_g_order").click(function(){
        $("form").attr("action",$("#search_g_order_action_url").val());
    })
    $("#out_g_order").click(function(){
        $("form").attr("action",$("#out_g_order_action_url").val());
    })
</script>
<script language="JavaScript">
$(document).ready(function(){
    $("#myForm").validate({
        rules:{username:"required",
               usercode:"required",
            },
        messages:{
            username:"用户名不能为空",
            usercode:"用户编码不能为空",
        },
        submitHandler: function(form) {
            $(form).ajaxSubmit(options);
            return false;
        }
    });
	var options = {
	    url: "<?php echo U('user/admin_save');?>",
	    type: 'post',
	    success: function(data) {
	    	data = eval("("+data+")")
	    	if(data.status=="true"){
	    		window.location.href="<?php echo U('user/admin_index');?>";
	    	}
	    } 
	};
});
function selectAll(obj){
    obj = obj+"[]";
    var allObj = document.getElementsByName(obj)
    for(var i = 0; i < allObj.length; i++)
       allObj[i].checked = true;
}

function cancalAll(obj){
    obj = obj+"[]";
    var allObj = document.getElementsByName(obj)
    for(var i = 0; i < allObj.length; i++)
       allObj[i].checked = false;
}
</script>