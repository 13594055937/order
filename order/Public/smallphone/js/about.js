$(function(){
	$(".nav li").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".boxs").eq(_index).show().siblings().hide();
	});

	$(".videoimg").click(function(){
		$(this).hide();
		$(".vjs-tech").attr("autoplay");
	})
})