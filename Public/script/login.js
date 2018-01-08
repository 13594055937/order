function Index() {
    this.init();
}

Index.prototype = {
    init: function() {
        this.bindDOM();
    },
    bindDOM: function() {
        // 登录
        $('.button-login').on('click', function(e) {
            var self = $(this);
            var data = {
                name: $.trim($('#userName').val()),
                psw: $('#userPsw').val()
            };

            self.statusChange('loading');
            // 帐号
            if (!data.name || !$.regCheck.mobile(data.name)) {
                $('.tips').html('<span class="icon-rl icon-alert"></span>手机号码格式有填错').slideDown();
                setTimeout(function() {
                    $('.tips').stop(true).slideUp().html('');
                    self.statusChange('reset');
                }, 1000);
                return false;
            }

            // 密码
            if (!data.psw) {
                $('.tips').html('<span class="icon-rl icon-alert"></span>密码不能为空').slideDown();
                setTimeout(function() {
                    $('.tips').stop(true).slideUp().html('');
                    self.statusChange('reset');
                }, 1000);
                return false;
            }

            if (data.psw && !$.regCheck.psw(data.psw)) {
                $('.tips').html('<span class="icon-rl icon-alert"></span>密码格式有填错').slideDown();
                setTimeout(function() {
                    $('.tips').stop(true).slideUp().html('');
                    self.statusChange('reset');
                }, 1000);
                return false;
            }
            var url = $("#login_url").html()
            var success_url = $("#success_url").html();
            $.ajax({
              'url'  :url,
              'data'   :{'telphone':data.name,"pwd":data.psw},
              'type'   :'post',
              'success':function(msg){
                if(msg!="success"){
                    alert(msg);
                    return false;
                }else{
                    window.location.href=success_url;
                }
              }
            })
            setTimeout(function() {
                self.statusChange('reset');
            }, 1000);

        });
        // 自动登录
        $('#autoLogin').on('change', function() {
            var self = $(this);
            if (self[0].checked == true) {
                $('.tips').html('<span class="icon-rl icon-alert"></span>公共场所不建议自动登录，以防帐号丢失').slideDown();
            } else {
                $('.tips').html('').stop(true).slideUp();
            }

        });
    },
    submitForm: function() {

    }
}

$(function() {
    new Index();
});
