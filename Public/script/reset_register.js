function Index() {
    this.init();
}

Index.prototype = {
    init: function() {
        this.bindDOM();
    },
    bindDOM: function() {
        $('.reg-step-cont .button-step').on('click', function() {
            var self = $(this);
            if (self.hasClass('button-step-one')) {
                var type = self.attr('data-type');
                var data = {
                    name: $.trim($('#userMobile').val()),
                    code: $.trim($('#code').val()),
                    memId: $.trim($('#memId').val())
                };
                self.statusChange('loading');

                // 帐号
                if (!data.name || !$.regCheck.mobile(data.name)) {
                    alert('手机号码格式有填错');
                    setTimeout(function() {
                        self.statusChange('reset');
                    }, 1000);
                    return false;
                }

                // 验证码
                if (!data.code || !$.regCheck.id(data.code)) {
                    alert('验证码有填错');
                    setTimeout(function() {
                        self.statusChange('reset');
                    }, 1000);
                    return false;
                }

                $('.reg-step-title').find('.step-title-' + type).addClass('current').siblings('.step-name').removeClass('current');
                $('.step-cont-' + type).addClass('current').siblings('.reg-step-cont').removeClass('current');
            } else if (self.hasClass('button-step-two')) {
                var type = self.attr('data-type');
                var data = {
                    msgCode: $.trim($('#msgCode').val())
                };
                self.statusChange('loading');

                // 短信验证码
                if (!data.msgCode || !$.regCheck.verify(data.msgCode)) {
                    alert('短信验证码有填错');
                    setTimeout(function() {
                        self.statusChange('reset');
                    }, 1000);
                    return false;
                }

                $('.reg-step-title').find('.step-title-' + type).addClass('current').siblings('.step-name').removeClass('current');
                $('.step-cont-' + type).addClass('current').siblings('.reg-step-cont').removeClass('current');
            } else if (self.hasClass('button-step-three')) {
                var type = self.attr('data-type');
                var data = {
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
                $('.reg-step-title').find('.step-title-' + type).addClass('current').siblings('.step-name').removeClass('current');
                $('.step-cont-' + type).addClass('current').siblings('.reg-step-cont').removeClass('current');
            }
        });

        $('.get-msgCode').on('click', function() {
            alert('获取验证码');
            $('#msgCode').focus();
        });
    }
}

$(function() {
    new Index();
});
