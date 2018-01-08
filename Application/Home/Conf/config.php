<?php
return array(
        'URL_MODEL' => '2',
	//'配置项'=>'配置值'
	'DB_TYPE' => 'mysql',
	'DB_HOST'=>'127.0.0.1',
	'DB_DSN' => '',
	'DB_NAME' =>  'gorder',
	'DB_USER'=>'root',
	'DB_PWD'=>'zwyf123456', 
	'DB_PREFIX' => '', // 数据库表前缀
	'DB_CHARSET' => 'utf8', // 数据库编码默认采用utf8
	'DB_FIELDS_CACHE' => false, // 启用字段缓存
	'TMPL_CACHE_ON' => false,//禁止模板编译缓存
    'HTML_CACHE_ON' => false,
    'pageCount' => 40,
    'DEFAULT_FILTER'=>'',
    'APP_GROUP_LIST' => 'Home',
    'init_pwd'=>'123456',
    'alipay_config'=>array(
	    'partner' =>'2088121074489804',   //这里是你在成功申请支付宝接口后获取到的PID；
	    'key'=>'naprxue7dkpnkuzlv4sxdvo45yz4qme4',//这里是你在成功申请支付宝接口后获取到的Key
	    'sign_type'=>strtoupper('MD5'),
	    'input_charset'=> strtolower('utf-8'),
	    'cacert'=> B_PATH.'cacert.pem',
	    'transport'=> 'http',
	    ),
	  //以上配置项，是从接口包中alipay.config.php 文件中复制过来，进行配置；
	    
	'alipay'   =>array(
	//这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
	'seller_email'=>'961013282@qq.com',
	//这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
	'notify_url'=>'http://order.zwyunfu.com/Home/Pay/notifyurl.html', 
	//这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
	'return_url'=>'http://order.zwyunfu.com/Home/Pay/returnurl.html',
	//支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
	'successpage'=>'user/mem_line',   
	//支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
	'errorpage'=>'user/mem_line',
	),
	//微信配置
	'appId' => 'wxe307c79749afa9fa',
	'appSecret' => '27e1ca69b47418435e8dc67183a13259',
	'payKey' => 'dh123456dh123456dh123456dh123456',
	'sendName' => '东方硅谷',
	'mchId'=>"1274384101",
	'name' => '微信红包测试',
	'wishing' => '猴年领红包啦',
	'remark' => '么有备注',
);
