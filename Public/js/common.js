define(['jquery'], function($) {


	function common() {
		$(".st-head i").click(function() {
			$(this).toggleClass('close').parent().next('.st-body').slideToggle();
		});
		$('.st-close b').click(function() {
			$(".st-head i").trigger("click");
		});
		$('.st-body li').hover(function() {
			var _this = $(this);
			_this.find('.box').fadeIn('fast');
		}, function() {
			$(this).find('.box').fadeOut('fast');
		});
		//tab切换
		function resetTabs(obj) {
			$(obj).parent().parent().next("div").children("div").hide();
			$(obj).parent().parent().find("a").removeClass("current");
		}

		//tab
		loadTab();

		function loadTab() {
			$(".content > div").hide();
			$(".tabs").each(function() {
				$(this).find("li:first a").addClass("current");
			});
			$(".content").each(function() {
				$(this).find("div:first").fadeIn();
			});
			$(".tabs a").on("click", function(e) {
				e.preventDefault();
				if ($(this).attr("class") == "current") {
					return;
				} else {
					resetTabs(this);
					$(this).addClass("current");
					$($(this).attr("name")).fadeIn();
				}
			});
		}
		
		$('.reply .item').click(function() {
	        var str = $(this).parent().find('.callback');
	        if (parseInt(str.css('left')) > 0) {
	          str.animate({
	            'left': "-247px"
	          });
	        } else {
	          str.animate({
	            'left': "76px"
	          });
	        }
	    });
	};　　　　
	return {　　　　　
		common: common
	};

	　　
});