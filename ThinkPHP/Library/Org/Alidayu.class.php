<?php
header("Content-Type:text/html;charset=UTF-8");
/**
 * 阿里大鱼短信模块
 */
class Alidayu {

  /**
   * [createSign 签名函数]
   * @param  Array  $paramArr [参数数组]]
   * @param  String $secret   [secret]
   * @return String           [返回签名]
   */
  function createSign ($paramArr, $secret) {
      $sign = $secret;
      ksort($paramArr);
      foreach ($paramArr as $key => $val) {
        if ($key != '' && $val != '') {
          $sign .= $key.$val;
        }
      }
      $sign.=$secret;
      $sign = strtoupper(md5($sign));
      return $sign;
  }

  /**
   * [createStrParam 组参函数]
   * @param  Array $paramArr [参数数组]
   * @return String          [返回Url]
   */
  function createStrParam ($paramArr) {
       $strParam = '';
       foreach ($paramArr as $key => $val) {
       if ($key != '' && $val != '') {
               $strParam .= $key.'='.urlencode($val).'&';
           }
       }
       return $strParam;
  }
  /**
   * [getSms 获取短信接口]
   * @param  String $mobile    [手机号码] 13568369572
   * @param  String $sms_param [短信变量] "{'code':'" . mt_rand(1000, 9999) . "','product':'你好啊'}"
   * @return [type]            [description]
   */
  function getSms($mobile, $sms_param){
    $config = C('Alidayu');
    // print_r($config);
    $paramArr = array(
      'app_key' => $config['APP_KEY'],
      'session_key' => '',
      // API接口名称
      'method' => (empty($config['method'])) ? 'alibaba.aliqin.fc.sms.num.send' : $config['method'],
      // 返回格式
      'format' => (empty($config['format'])) ? 'json' : $config['format'],
      // 版本号
      'v' => (empty($config['v'])) ? '2.0' : $config['v'],
      // sign加密方式
      'sign_method'=> (empty($config['sign_method'])) ? 'md5' : $config['sign_method'],
      // 时间
      'timestamp' => date('Y-m-d H:i:s'),
      'fields' => 'nick,type,user_id',
      // 短信类型
      'sms_type' => 'normal',
      // 短信签名
      'sms_free_sign_name' => (empty($config['sms_free_sign_name'])) ? '象象学车' : $config['sms_free_sign_name'],
      // 短信内容替换
      'sms_param' => $sms_param,
      // 手机号牌
      'rec_num' => $mobile,
      // 短信模板ID
      'sms_template_code' => (empty($config['sms_template_code'])) ? 'SMS_2175787' : $config['sms_template_code'],
    );
    //生成签名
    $sign = $this -> createSign($paramArr, $config['APP_SECRET']);
    //组织参数
    $strParam = $this -> createStrParam($paramArr);
    $strParam .= 'sign='.$sign;
    // 访问服务
    $url = 'http://gw.api.taobao.com/router/rest?'.$strParam; //正式环境调用地址
    // 获取返回
    $result = file_get_contents($url);
    $arr = json_decode($result, true);
    return $arr;
  }

}
