require(['jquery', 'swiper.min', 'common'], function($, swiper, common) {
	common.common();
	$(".l-fixed li").click(function() {
		var id = $(this).attr("data-target");
		$(this).addClass("current").siblings().removeClass("current");
		$("html,body").animate({
			scrollTop: $('#' + id).offset().top
		}, 500);
	});

	$('.tabb1 .tab-head ul li').click(function() {
		var index = $(this).index();
		$(this).addClass('current').siblings().removeClass('current');
		// $('.content1 .featureContainer').eq(index).fadeIn().siblings().hide();
		$('.content1 .item').animate({
			top: -(index * 431) + "px"
		});
	});

	// $('.tabb1 .tab-head ul li').click(function() {
	// 	var index = $(this).index();
	// 	if (index == 1) {
	// 		$('.content1 .item').animate({
	// 			top: "-118px"
	// 		});
	// 	} else {
	// 		$('.content1 .item').animate({
	// 			top: "0"
	// 		});
	// 	}
	// });
	$(".sc-top").click(function() {
		$("body,html").animate({
			scrollTop: 0
		}, 1000);
		return false;
	});

	var swiper2 = new Swiper('.tab2_1 .swiper-container', {
		nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev',
		slidesPerView: 4,
		// centeredSlides: true,
		paginationClickable: true,
		spaceBetween: 14,
		loop: true
	});

	var swiper3 = new Swiper('.tab2_2 .swiper-container', {
		nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev',
		slidesPerView: 4,
		// centeredSlides: true,
		paginationClickable: true,
		spaceBetween: 14,
		loop: true
	});

	$(".l-fixed").hide();
	$(window).scroll(function(event) {
		var wTop = $(window).scrollTop();
		var cTop = $(".c-link-2").offset().top;
		var bTop = $(".box-1").offset().top;

		var vHeight = $(window).height();
		if (wTop > cTop - vHeight / 3) {
			$(".c-link-2").find("ul").addClass("amation");
		}
		if (bTop > cTop - vHeight) {
			$(".box-1").find(".img-2").addClass("animation");
		}

		var wTop = $(window).scrollTop();
		var iTop = $('#item1').offset().top;
		var vHeight = $(window).height();
		if (wTop > iTop - vHeight) {
			$(".l-fixed").fadeIn();
		} else {
			$(".l-fixed").fadeOut();
		}

		var items = $("body").find(".item");
		var menu = $("#menu");
		var top = $(document).scrollTop();
		var currentId = ""; //滚动条现在所在位置的item id
		items.each(function() {
			var m = $(this);
			//注意：m.offset().top代表每一个item的顶部位置
			if (top > m.offset().top - 10) {
				currentId = m.attr("id");
			} else {
				return false;
			}
		});
		var currentLink = menu.find(".current");
		if (currentId && currentLink.attr("data-target") != currentId) {
			currentLink.removeClass("current");
			menu.find("[data-target=" + currentId + "]").addClass("current");
		}
	});

	$(".b3 span").click(function() {
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		if (_index == 0) {
			$(".gift").addClass("right").show().siblings().hide();
		} else if (_index == 1) {
			$(".giftbox").addClass("right").show().siblings().hide();
		} else {
			window.open('custom.html');
		}

	})
	var myVideo = document.getElementById("video1");
	$(".video-1 .img-2").click(function() {
		$(".showvideo").fadeIn();
		myVideo.play();
	});
	$(".showvideo .mask").click(function() {
		$(".showvideo").fadeOut();
		myVideo.pause();
	})
})