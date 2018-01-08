$(function(){
	$(".nav li").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".list").eq(_index).show().siblings().hide();
	});
	$(".list li").click(function(){
		window.location="newsdetail.html";
	})
})