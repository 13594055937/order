(function(g){
    var siteUrl = g.siteUrl || location.hostname;
    
    // 初始化
    init();
    
    function init(){
        // 显示微信安全支付的字样
        if(location.href.indexOf('showwxpaytitle=1') == -1) {
          location.href = location.href.indexOf('?') == -1 ? location.href + '?showwxpaytitle=1' : location.href + '&showwxpaytitle=1';
          return;
        }
        $(function(){
          confirmPayBtn();
        });
    }
    
    // 支付方式
    var playMethod = 'weixin';
     
    // 发起支付
    var confirmPayBtn = function(){
		$(".selectprice").click(function(){
			$("#real_price").val($(this).attr("value"));
		})
        $('.weixinPayBtn').click(function(){
            if (playMethod == 'weixin') {
            	if(!isWeixin()) {
                    alert("请在微信端进行支付");
                    return false;
                }
                              
                var thisBtn = $(this);
                //获取订单id
				var real_price = $("#other_price").val();
                var price = real_price == "" ? $("#real_price").attr("value") : real_price;
				price = parseInt(price);
                //设置微信安全支付目录
                var safe_pay_dir = $("#redirect_url").val();
                //拼接请求地址
                var pay_url = safe_pay_dir+'?price='+price;
                
                if(!price) {
                    alert("没有参数信息");
                   return;
                }
                
                if(thisBtn.data('disabled') == 'yes') {
                    return;
                }
                thisBtn.data('disabled', 'yes');
               // 获取订单json数据
                $.getJSON(pay_url, function(json){
                	//return;
                    if(json.status != 'ok') {
                        thisBtn.data('disabled', 'no');
                        alert(json.error_msg);
						return;
                    } else {
                      WeixinJSBridge.invoke('getBrandWCPayRequest', json.wxconf, function(res){
                        thisBtn.data('disabled', 'no');
                        if(res.err_msg == 'get_brand_wcpay_request:cancel') {
                          alert("您已取消了此次支付");
                          return;
                        } else if(res.err_msg == 'get_brand_wcpay_request:fail') {
						  alert(JSON.stringify(res))
                          alert("支付失败，请重新尝试");
                          return;
                        } else if(res.err_msg == 'get_brand_wcpay_request:ok') {
                          alert("支付成功！");
                        } else {
                          alert("未知错误"+res.error_msg);
						  return;
                        }
                      });
                   }
              }).error(function(){
                    //alert("网络错误，请刷新页面重试11");
                    thisBtn.data('disabled', 'no');
              });

            } 
        })
    }
    function isWeixin(){
       return /MicroMessenger/.test(navigator.userAgent);
    }    

})(window);