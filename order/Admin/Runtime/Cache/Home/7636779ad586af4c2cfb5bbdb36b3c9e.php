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
            


<!--top end-->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <span>用户管理</span>
            </h1>
        </div>
    </div>
    <div class="row">

        <div style="text-align:left" class="col-lg-12 m-b-xs">
            <form class="form-inline" role="form" method="post">
                <input type="hidden" id="out_g_order_action_url" value="<?php echo U('user/daochu');?>">
                <input type="hidden" id="search_g_order_action_url" value="<?php echo U('user/admin_index');?>">
                <div>
                    <div class="form-group ">用户编号:&nbsp;<input type="text" name="usercode" value="<?php echo ($post["usercode"]); ?>" class="form-control" placeholder="单行输入"></div>
                </div>
                <div>
                    <div class="form-group">用户姓名:&nbsp;<input type="text" name="username" value="<?php echo ($post["username"]); ?>" class="form-control" placeholder="客户名称" name="customer_name"></div>
                </div>
                <div class="form-group ">用户组:&nbsp;&nbsp;&nbsp;&nbsp;
                    <select name="usertype" class="form-control">
                        <option value="">全部</option>
                        <?php if(is_array($usertype_list)): foreach($usertype_list as $k=>$usertype): ?><option value="<?php echo ($k); ?>" <?php if(!empty($post['usertype']) && ($k == $post['usertype'])): ?>selected<?php endif; ?> ><?php echo ($usertype); ?></option><?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="form-group"><button class="btn btn-sm" id="add_g_order"><a href="<?php echo U('user/admin_add');?>">新增</a></button></div>
                <div class="form-group"><button class="btn btn-sm btn-primary" id="search_g_order">查询</button></div>
                <div class="form-group"><button class="btn btn-sm btn-primary" id="out_g_order">导出</button></div>
            </form>
        </div>
        
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                         <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <tr align="center">
                                <td>用户id</td>
                                <td>用户编号</td>
                                <td>用户姓名</td>
                                <td>联系方式</td>
                                <td>关联微信号</td>
                                <td>最新登录时间</td>
                                <td>操作</td>
                            </tr>
                            <?php if(is_array($list)): foreach($list as $k=>$data): ?><tr align="center">
                                    <td><?php echo ($data["id"]); ?></td>
                                    <td><?php echo ($data["usercode"]); ?></td>
                                    <td><?php echo ($data["username"]); ?></td>
                                    <td><?php echo ($data["mobile"]); ?></td>
                                    <td><?php echo ($data["openid"]); ?></td>
                                    <td><?php echo date("Y-m-d H:i:s",$data['latestlogin']); ?></td>
                                    <td>
                                        <a href="<?php echo U('user/admin_add',array('id'=>$data['id']));?>">编辑</a>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal" id="edit_pwd_g_order" data-id="<?php echo ($data["id"]); ?>">修改密码</button>
                                        <a class="del-one" href="javascript:;" data="<?php echo U('user/admin_del',array('id'=>$data['id']));?>">删除</a>
                                    </td>
                                </tr><?php endforeach; endif; ?>
                        </table>
                    </div>
                    <div class="row"><?php echo ($page); ?></div>
                </div>
            </div>
        </div>
        <!-- 模态框（Modal） -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            修改密码
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="user_id" value="">
                        <div class="form-group">原始密码：<input type="password" id="old_userpwd"/></div>
                        <div class="form-group">新密码：&nbsp;&nbsp;&nbsp;<input type="password" id="userpwd"/></div>
                        <input type="hidden" id="editpwdurl" value="<?php echo U('user/editpwd');?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="closeid">关闭
                        </button>
                        <button type="button" class="btn btn-primary" id="confirm_edit">
                            提交更改
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
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
<script>
    $(function() {
        $('#myModal').on('show.bs.modal', function () { //subscribe to show method
            var id = $(event.target).data('id'); //get the id from tr
            $("#user_id").val(id);
        });
    });
    $("#confirm_edit").click(function(){
        var id = $("#user_id").val();
        var old_userpwd = $("#old_userpwd").val();
        var userpwd = $("#userpwd").val();
        if(id=="" || $.trim(old_userpwd) =="" || $.trim(userpwd) =="" ){
            alert("所填信息有误");return false;
        }
        var url = $("#editpwdurl").val();
        $.ajax({
            'url'  :url,
            'data'   :{'id':id,'old_userpwd':old_userpwd,'userpwd':userpwd},
            'type'   :'post',
            'success':function(msg){
                if(msg == "成功"){
                    alert("修改成功");
                    $("#closeid").click();
                }else{
                    alert(msg)
                }
            }
        })
    })
</script>