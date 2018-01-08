<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE' => 'mysql',
	'DB_HOST'=>'localhost',
	'DB_DSN' => '',
	// 'DB_NAME' =>  'gorder',
	// 'DB_USER'=>'gorder',
	// 'DB_PWD'=>'Gorder1709',
	'DB_NAME' =>  'city',
	'DB_USER'=>'root',
	'DB_PWD'=>'',
	'DB_PREFIX' => '', // 数据库表前缀
	'DB_CHARSET' => 'utf8', // 数据库编码默认采用utf8
	'DB_FIELDS_CACHE' => false, // 启用字段缓存
	'TMPL_CACHE_ON' => false,//禁止模板编译缓存
    'HTML_CACHE_ON' => false,
    'pageCount' => 5,
    'DEFAULT_FILTER'=>'',
    'admin_pageCount'=>30,
    'init_pwd'=>'123456'
);
