function Index() {
    this.init();
}

Index.prototype = {
    init: function() {
        this.bindDOM();
    },
    bindDOM: function() {
        // 登录
        $('.button-step').on('click', function() {
            var self = $(this);
            if (true || self.hasClass('button-step-one')) {
                var data = {
                    telphone: $.trim($('#telphone').val()),
                    msgCode: $.trim($('#msgCode').val()),
                    invalidcode:$.trim($("#invalidcode").val()),
                    pwd: $.trim($('#pwd').val()),
                };

                // 帐号
                var reg = /^1[3458]\d{9}$/
                if (!reg.test($.trim(data.telphone)) || data.telphone=="") {
                    alert('手机号码格式有填错');
                    return false;
                }

                // 短信验证码
                if (!data.msgCode) {
                    alert('短信验证码有填错');
                    return false;
                }

                // 会员ID
                if (!data.pwd) {
                    alert('密码不能为空');
                    return false;
                }

                // 图形验证码
                if (!data.invalidcode) {
                    alert('图形验证码不能为空');
                    return false;
                }
                var url = $("#reg_url").html()
                var stepurl = $("#stepurl").html();
                $.ajax({
                  'url'  :url,
                  'data'   :{'yanzhengma':data.msgCode,'invalidcode':data.invalidcode,'pwd':data.pwd,'telphone':data.telphone,'forget':1},
                  'type'   :'post',
                  'success':function(msg){
                    if(msg!="success"){
                        alert(msg);
                        return false;
                    }else{
                        window.location.href=stepurl;
                    }
                  }
                })
            } else if (self.hasClass('button-step-two')) {
                var type = self.attr('data-type');
                var data = {
                    name: $.trim($('#telphone').val()),
                    msgCode: $.trim($('#msgCode').val()),
                    memId: $.trim($('#memId').val()),
                    setPsw: $.trim($('#setPsw').val()),
                    confirmPsw: $.trim($('#confirmPsw').val())
                };

                self.statusChange('loading');

                // 密码格式不正确
                if (!data.setPsw || !$.regCheck.psw(data.setPsw)) {
                    alert('密码格式不正确');
                    setTimeout(function() {
                        self.statusChange('reset');
                    }, 1000);
                    return false;
                }

                // 两次密码不一致
                if (!data.confirmPsw || data.confirmPsw != data.setPsw) {
                    alert('两次密码不一致');
                    setTimeout(function() {
                        self.statusChange('reset');
                    }, 1000);
                    return false;
                }
                var url = $("#reg_url").html()
                $.ajax({
                  'url'  :url,
                  'data'   :{'telphone':data.name,'is_register':1,"pwd":data.setPsw,"memid":data.memId,"yanzhengma":data.msgCode},
                  'type'   :'post',
                  'success':function(msg){
                    if(msg!="success"){
                        alert(msg);
                        self.statusChange('reset');
                        return false;
                    }else{
                        $('.reg-step-title').find('.step-title-' + type).addClass('current').siblings('.step-name').removeClass('current');
                        $('.step-cont-' + type).addClass('current').siblings('.reg-step-cont').removeClass('current');
                    }
                  }
                })
            }
        });
    }
}

$(document).ready(function(){
    var timeout;
    var count = 60; // 倒数十下
    var url = $("#reg_url").html();
    BtnCount = function() {
      console.log(11)
           // 启动按钮
        if (count == 0) {
            $('.get-msgCode').removeAttr("disabled");
            $('.get-msgCode').html("发送验证码");
            clearTimeout(timeout);           // 可取消由 setTimeout() 方法设置的 timeout
        }
        else {
            count--;
            $('.get-msgCode').html("剩余 "+count.toString()+" 秒");
            setTimeout(BtnCount, 1000);
        }
    };
    $('.get-msgCode').on('click', function() {
          var phone = $("#telphone").val();
          var reg = /^1[3458]\d{9}$/;
          if(!reg.test($.trim(phone)) || phone=="")
          {
           alert("手机号码格式不对！");
           return false;
          }
          $.post(url,{"phone":phone},function(msg){
		if(msg.length >6){
                    alert(msg);
                  }else{
                    count = 60;
                    $('.get-msgCode').attr("disabled", "true");
                    timeout = setTimeout(BtnCount, 1000);
                  }
	   })
	   return false;
          $.ajax({
              'url':    url  ,
              'data'   :{"phone":phone},
              'type'   :'post',
              'success':function(msg){
                  if(msg.length >6){
                    alert(msg);
                  }else{
                    count = 60;
                    $('.get-msgCode').attr("disabled", "true");
                    timeout = setTimeout(BtnCount, 1000);
                  }
              }
            })
        $('#msgCode').focus();
    });
    $("#codeimg").click(function(){
	$(this).attr("src","verify.html");
    })
    new Index();
});
