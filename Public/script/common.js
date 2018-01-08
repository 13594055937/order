if (typeof jQuery == "undefined") {
    throw new Error("丢失jQuery库文件");
}

/*======================
 * common
 */
+ function($) {
    $.extend({
        // 初始化
        init: function() {
            // 缓存地址
            $.callUrl();

            // console 信息
            console.log("一张网页，要经历怎样的风雨，才能出现在你的面前？\n一位新人，要历经怎样的成长，才能立与技术之巅？\n探寻这里的秘密；\n体验这里的挑战；\n成为这里的主人；\n加入121店，分享你的价值；\n相信,因为多了个你，世界可以有所改变。\n");
            console.log("请将简历发送至 %c xxx@yl.com（ 邮件标题请以“姓名-应聘XX职位-来自console”命名）", "color:#fb6149");
            console.log("职位介绍：http://www.yl.com");
        },
        krLoading: function() {
            $('body').append('<div class="spinner-box clearfix"><div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>');
        },
        krLoadingClose: function() {
            $('.spinner-box').remove();
        },
        krAjax: function(type, url, data, call) {
            var datas = $.extend({

            }, data);
            $.krLoading();
            $.ajax({
                type: type,
                url: url,
                dataType: 'json',
                data: datas
            }).done(function(res) {
                $.krLoadingClose();
                call(res);
            }).fail(function() {
                $.krLoadingClose();
                $.krPopupTip('链接服务器失败');
            });
        },
        // 跨域
        jsonP: function(url, data, call) {
            data = $.extend({}, data);

            $.when(
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'jsonp',
                    data: data
                })
            ).done(function(res) {
                call(res);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $.krPopupTip('链接服务器失败:' + errorThrown);
            });
        },

        // 常用正则
        regCheck: {
            def: function(reg, str) {
                return reg.test(str);
            },
            userName: function(str) {
                var reg = /^[_A-Za-z0-9]{6,16}$/;
                return reg.test(str);
            },
            psw: function(str) {
                var reg = /^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/A-Za-z0-9]{6,16}$/;
                return reg.test(str);
            },
            email: function(str) {
                var reg = /^([a-zA-Z0-9]|[._])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
                return reg.test(str);
            },
            telNum: function(str) {
                var reg = /^((0\d{2,3}-\d{7,8})|(1[3|5|4|7|8][0-9]\d{8}))$/;
                return reg.test(str);
            },
            mobile: function(str) {
                var reg = /^(1[3|4|5|7|8][0-9]\d{8})$/;
                return reg.test(str);
            },
            verify: function(str) {
                var reg = /^\d{6}$/;
                return reg.test(str);
            },
            id: function(str) {
                var reg = /^\d{4}$/;
                return reg.test(str);
            },
            CN: function(str) {
                var reg = /^[\u4E00-\u9FA5]+$/;
                return reg.test(str);
            }
        },

        // 手机格式化
        mobileFormat: function(phoneNum) {
            phoneNum = phoneNum.substring(0, 3) + "****" + phoneNum.substring(7);
            return phoneNum;
        },
        // 浏览器尺寸改变函数
        screenResize: function(fn) {
            fn();
            $(window).on('resize', function() {
                fn();
            });
        },
        setHeight: function(obj, num, scale) {
            var w = parseInt($(window).width());
            $(obj).height(parseInt((w - num) * scale));
        }
    });

    $.extend({
        // 获取关键字值
        getUrlPara: function(key) {
            var paras = window.location.search;
            if (paras) {
                var items = paras.split('?')[1].split('&'),
                    len = items.length,
                    i = 0;
                obj = {};
                for (; i < len; i++) {
                    var item = items[i].split('='),
                        name = item[0],
                        value = item[1] ? item[1] : '';
                    obj[name] = value;
                }
                if (!key) {
                    return obj;
                } else {
                    return obj[key];
                }
            }
        },

        // 获取参数
        getUrlPath: function(ind) {
            var path = window.location.pathname;
            if (path) {
                var paras = path.split('/'),
                    len = paras.length,
                    i = 0,
                    res = [];
                for (; i < len; i++) {
                    if (paras[i]) {
                        res.push(paras[i]);
                    }
                }
                if (typeof ind == 'undefined') {
                    return res[res.length - 1];
                } else {
                    return res[ind];
                }
            } else {
                return '';
            }
        },
        callUrl: function() {
            var prevUrl = window.sessionStorage.getItem('prevUrl');
            var curUrl = window.sessionStorage.getItem('curUrl');
            var localUrl = window.location.href;
            if (!prevUrl) {
                window.sessionStorage.setItem('prevUrl', localUrl);
                window.sessionStorage.setItem('curUrl', localUrl);
            } else {
                if (curUrl !== localUrl) {
                    window.sessionStorage.setItem('prevUrl', curUrl);
                    window.sessionStorage.setItem('curUrl', localUrl);
                }
            }
        },
        goBack: function() {
            return window.sessionStorage.getItem('prevUrl');
        }
    });

    $.fn.extend({
        // 图片滚动加载
        lazyLoad: function() {
            var here = this;
            var winHeight = parseFloat($(window).height());

            function showImg() {
                $(here).each(function() {
                    var imgH = $(this).height(),
                        topVal = $(this).get(0).getBoundingClientRect().top;
                    if ((topVal < (winHeight - (imgH / 3))) && $(this).hasClass('lazy')) {
                        var _here = this;
                        var _img = new Image();
                        _img.src = $(_here).data('original');
                        $(_here).attr('data-original', 0);
                        $(_here).removeClass('lazy');
                        // _img.onload = function(){
                        $(_here).hide().attr('src', _img.src).show();
                        // }
                    }
                });
            }
            showImg();
            $(window).on('scroll', function() {
                showImg();
            });
        },
        statusChange: function(status) {
            if (!$(this).attr('data-default-text')) {
                $(this).attr('data-default-text', $(this).text());
            }
            if (status == 'loading') {
                $(this).text($(this).attr('data-loading-text'));
                $(this).attr('disabled', true).addClass('disabled');
            }
            if (status == 'reset') {
                $(this).text($(this).attr('data-default-text'));
                $(this).attr('disabled', false).removeClass('disabled');
            }
        }
    });
    $.init();
}(jQuery);


/* ==================
 * krPopup
 *
 */
+ function($) {
    $.krPopup = function(options) {
        var defaults = {
                skin: 'krPopup',
                width: 260,
                titleShow: false,
                titleTxt: '提示',
                content: '<div class="only-txt">欢迎来到野驴</div>',
                mask: true,
                zIndex: 1000,
                closeShow: false,
                closeCall: function() {
                    $.krPopupClose(opts.skin);
                },
                buttons: [
                    ['取消', 'button button-md button-full button-cancel', function() {
                        $.krPopupClose();
                    }],
                    ['确定', 'button button-md button-full button-confirm', function() {
                        $.krPopupClose();
                    }]
                ]
            },
            opts = $.extend({}, defaults, options),
            str = '';
        // 判断蒙层是否显示
        if (opts.mask) {
            str += '<div id="krPopupMask" style="z-index:' + opts.zIndex + '"></div>';
        }

        // 弹层
        str += '<div class="krPopup" id="' + opts.skin + '" style="z-index:' + (opts.zIndex + 1) + ';">';

        // 关闭按钮
        if (opts.closeShow) {
            str += '<span class="popup-close">&times;</span>';
        }

        // 标题
        if (opts.titleShow) {
            str += '<div class="popup-hd"><h3>' + opts.titleTxt + '</h3></div>';
        }
        // 主体
        str += '<div class="popup-bd">' + opts.content + '</div>';
        if (opts.buttons && opts.buttons.length) {
            str += '<div class="popup-buttons-box clearfix">';
            for (var i = 0; i < opts.buttons.length; i++) {
                if (opts.buttons.length == 1) {
                    if (opts.buttons[i][0] && opts.buttons[i][1]) {
                        str += '<span class="col-12 g-tac"><span class="' + opts.buttons[i][1] + '">' + opts.buttons[i][0] + '</span></span>';
                    } else {
                        $.krPopupTip('请联系客服');
                    }
                }
                if (opts.buttons.length == 2) {
                    if (opts.buttons[i][0] && opts.buttons[i][1]) {
                        str += '<span class="col-6 g-tac"><span class="' + opts.buttons[i][1] + '">' + opts.buttons[i][0] + '</span></span>';
                    } else {
                        $.krPopupTip('请联系客服');
                    }
                }
            }
            str += '</div>';
        }
        str += '</div>';
        $(str).appendTo('body');

        // 针对弹层的位置进行处理
        var $top = parseInt($('.krPopup').height() / 2);
        $('.krPopup').css({
            marginTop: -$top + 'px'
        });

        // 关闭弹层
        $('.krPopup').on('click', '.popup-close', function() {
            if (typeof opts.closeCall === 'function') {
                opts.closeCall();
            }
        });
        $('.krPopup').on('click', '.popup-buttons-box .g-tac', function() {
            var ind = $(this).index();
            if (typeof opts.buttons[ind][2] === 'function') {
                opts.buttons[ind][2]();
            }
        });

        return this;
    };
    $.krPopupClose = function(id) {
        $('#krPopupMask').remove();
        $('.krPopup').remove();
    };
    $.krPopupHide = function(id) {
        $('#krPopupMask').fadeOut();
        $('.krPopup').fadeOut();
    };
    $.krPopupShow = function(id) {
        $('#krPopupMask').fadeIn();
        $('.krPopup').fadeOut();
    };
    $.krPopupTip = function(msg, closeShow, fn) {
        $.krPopup({
            titleShow: false,
            closeShow: closeShow ? closeShow : false,
            buttons: [],
            content: '<div class="popup-tip">' + (msg ? msg : 'Error') + '</div>'
        });
        $('.krPopup .popup-close').css({
            'color': '#fff',
            'font-size': '18px',
            'line-height': '38px'
        });
        var timer = setTimeout(function() {
            $('#krPopup').fadeOut(200, function() {
                $.krPopupClose('krPopup');
                clearTimeout(timer);
                if (typeof fn === 'function') {
                    fn();
                }
            });
        }, 1500);
    };
}(jQuery);
