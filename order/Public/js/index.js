require(['jquery', 'swiper.min', 'common'], function($, swiper, common) {
	common.common();
	var swiper1 = new Swiper('.swiper-container1', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        slidesPerView: 1,
        paginationClickable: true,
        spaceBetween: 30,
        loop: true,
        autoplay : 8000,
    });

	$(".prev,.next").hover(function() {
		$(this).stop(true, false).fadeTo("show", 0.9);
	}, function() {
		$(this).stop(true, false).fadeTo("show", 0.4);
	});

	//
	if($(".c-link-2").length > 0){
		$(window).scroll(function(event) {
			var wTop = $(window).scrollTop();
			var cTop = $(".c-link-2").offset().top;

			var vHeight = $(window).height();
			if (wTop > cTop - vHeight) {
				$(".c-link-2").find("ul").addClass("amation");
			}

			if ($(".delicacy")) {
				var dTop = $(".delicacy").offset().top;
				if (wTop > dTop - vHeight) {
					$(".delicacy").find("ul").addClass("amation");
				}
			}
		});
	}
		
	$(".l-bottom a").hover(function(){
		//$(".imgbox").show().addClass("animated fadeIn");
		var a = $(this).data("title");
		var id = $(this).attr("id");
		//$(".price b").text(a);
		$("#box_"+id).show().addClass("animated fadeIn").html(a);	
	},function(){
		$(".imgbox").hide();
		$("img >img").show();
	});

	$(".b3 span").click(function() {
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		if (_index == 0) {
			$(".gift").addClass("right").show().siblings().hide();
		} else if (_index == 1) {
			$(".giftbox").addClass("right").show().siblings().hide();
		} else {
			window.location.href = 'index.html';
		}

	});
	$(".playPause").click(function() {
		playPause();
	});
	var myVideo = document.getElementById("video1");
	//视频播放暂停
	function playPause() {
		if (myVideo.paused) {
			myVideo.play();
			$(".playPause").css('display', 'none');
		} else {
			myVideo.pause();
			$(".playPause").css('display', 'block');
		}
	}



	var n = 0;
	var imgLength = $(".b-img a").length;
	var ctWidth = imgLength * 100;
	var itemWidth = 1 / imgLength * 100;
	$(".b-img").width(ctWidth + "%");
	$(".b-img a").width(itemWidth + "%");
	$(".b-list").width(imgLength * 30);
	if (imgLength > 1) {
		for (var i = 0; i < imgLength; i++) {
			var listSpan = $("<span></span>")
			$(".b-list").append(listSpan);
		}
	}
	$(".b-list span:eq(0)").addClass("spcss").siblings("span").removeClass("spcss");
	$(".bar-right em").click(function() {
		if (n == imgLength - 1) {
			var ctPosit = (n + 1) * 100;
			$(".banner").append($(".b-img").clone());
			$(".b-img:last").css("left", "100%");
			$(".b-img:first").animate({
				"left": "-" + ctPosit + "%"
			}, 1000);
			$(".b-img:last").animate({
				"left": "0"
			}, 1000);
			var setTime0 = setTimeout(function() {
				$(".banner .b-img:first").remove();
			}, 1000);
			n = 0;
			$(".b-list span:eq(" + n + ")").addClass("spcss").siblings("span").removeClass("spcss");
		} else {
			n++;
			var ctPosit = n * 100;
			$(".b-img").animate({
				"left": "-" + ctPosit + "%"
			}, 1000);
			$(".b-list span:eq(" + n + ")").addClass("spcss").siblings("span").removeClass("spcss");
		}
	})
	$(".bar-left em").click(function() {
		if (n == 0) {
			var stPosit = imgLength * 100;
			var etPosit = (imgLength - 1) * 100;
			$(".banner").prepend($(".b-img").clone());
			$(".b-img:first").css("left", "-" + stPosit + "%");
			$(".b-img:last").animate({
				"left": "100%"
			}, 1000);
			$(".b-img:first").animate({
				"left": "-" + etPosit + "%"
			}, 1000);
			var setTime0 = setTimeout(function() {
				$(".banner .b-img:last").remove();
			}, 1000);
			n = imgLength - 1;
			$(".b-list span:eq(" + n + ")").addClass("spcss").siblings("span").removeClass("spcss");
		} else {
			n--;
			var ctPosit = n * 100;
			$(".b-img").animate({
				"left": "-" + ctPosit + "%"
			}, 1000);
			$(".b-list span:eq(" + n + ")").addClass("spcss").siblings("span").removeClass("spcss");
		}
	})
	$(".b-list span").click(function() {
		var lsIndex = $(this).index();
		n = lsIndex;
		var ctPosit = n * 100;
		$(".b-img").animate({
			"left": "-" + ctPosit + "%"
		}, 1000);
		$(this).addClass("spcss").siblings("span").removeClass("spcss");
	})

	function rollEnvent() {
		if (n == imgLength - 1) {
			var ctPosit = (n + 1) * 100;
			$(".banner").append($(".b-img").clone());
			$(".b-img:last").css("left", "100%");
			$(".b-img:first").animate({
				"left": "-" + ctPosit + "%"
			}, 1000);
			$(".b-img:last").animate({
				"left": "0"
			}, 1000);
			var setTime0 = setTimeout(function() {
				$(".banner .b-img:first").remove();
			}, 1000);
			n = 0;
			$(".b-list span:eq(0)").addClass("spcss").siblings("span").removeClass("spcss");
		} else {
			n++;
			var ctPosit = n * 100;
			$(".b-img").animate({
				"left": "-" + ctPosit + "%"
			}, 1000);
			$(".b-list span:eq(" + n + ")").addClass("spcss").siblings("span").removeClass("spcss");
		}
	}
	var slidesetInterval = setInterval(rollEnvent, 4000);
	$(".banner").hover(function() {
		clearInterval(slidesetInterval);
	}, function() {
		slidesetInterval = setInterval(rollEnvent, 4000);
	});
	$(".bar-left").mouseover(function() {
		$(this).css("background", "url(images/arr-bg.png)");
		$(this).find("em").addClass("emcss");
	}).mouseleave(function() {
		$(this).css("background", "none");
		$(this).find("em").removeClass("emcss");
	})
	$(".bar-right").mouseover(function() {
		$(this).css("background", "url(images/arr-bg.png)");
		$(this).find("em").addClass("emcss");
	}).mouseleave(function() {
		$(this).css("background", "none");
		$(this).find("em").removeClass("emcss");
	})
});
