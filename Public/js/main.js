$(document).ready(function () {
         //  Initialize Backgound Stretcher
         var imgs = [  'public/images/bg1.jpg', 'public/images/bg2.jpg' , 'public/images/bg3.jpg' ];
         var img_list = $("#bg_img").html();
         if(img_list !=""){
         	var img_s = img_list.split("__");
         	imgs = "";
         	var len = img_s.length;
         	var abs_path = $("#absolute_path").html();
         	for(var i in img_s){
         		if(i != (len-1)){
		         	imgs = imgs + abs_path+"/Public/images/"+$.trim(img_s[i])
	         		if(i != (len-2)){
	         			imgs += ',';
	         		}
	         	}
         	}
         	console.log(imgs,abs_path)
         	imgs = imgs.split(",")
         }	 
         $('.bgStretcherWp').bgStretcher({
             imageWidth: 1920,
             imageHeight: 900,
             images: imgs,
             slideDirection: 'N',
             slideShowSpeed: 2500,
             transitionEffect: 'fade',
             sequenceMode: 'normal',
             buttonPrev: '#prev',
             buttonNext: '#next',
             pagination: '#nav',
             anchoring: 'left center',
             anchoringImg: 'left center'
         });
         //音乐播放器控制
		var myVid = $("#mp3bf")[0];
	    $('p.musicbtn').click(function(){
	        //防止冒泡
	        //event.stopPropagation();
	        if(myVid.paused) //如果当前是暂停状态
	        {	
				$('.musicbtn').text("点击关闭音乐");
	            myVid.play();//播放
				return;
	       }else{
	        //当前是播放状态
				$('.musicbtn').text("点击播放音乐");
				myVid.pause(); //暂停
			}
		});
 });
$(function(){
	$('.topnav a').eq(0).css({"background":"none"});
	$('.lefthide').addClass('animated fadeInLeft');
	$('.righthide').addClass('animated fadeInLeft');
	$('.p-cont h1').addClass('animated fadeInRightBig');
	$('.article-content').addClass('animated fadeInDown');//contact-content
	$('.p-cont p').hide();
	setTimeout(function(){$('.p-cont p').show(500).addClass('animated fadeInRight');},900);
	//setTimeout(function(){$('.article-content').addClass('animated fadeInDown');},800);
	$('.left-close').click(function(){
		F_LCont_Close();
	})
	
	//幻灯
	$('.banner-slide').bxSlider({
		auto:true
	});
	
	//计算tabs宽度
	//最新活动
	var tabstitleW = $('.tabs1').width();
	var tabsnum = $('.tabs1 li').length;
	var tabliW = tabstitleW / tabsnum;
	var navfirst = 0;
	$('.tabs1 li').each(function(i) {
		$(this).css({
			'left': navfirst,
			'width':tabliW + 27
			});
		navfirst = navfirst + tabliW -13;
	});
	
	//活动安排
	var tabstitleW = $('.tabs2').width();
	var tabsnum = $('.tabs2 li').length;
	var tabliW = tabstitleW / tabsnum;
	var navfirst = 0;
	$('.tabs2 li').each(function(i) {
		$(this).css({
			'left': navfirst,
			'width':tabliW + 27
			});
		navfirst = navfirst + tabliW - 13;
	});
	
	//最新分享
	var tabstitleW = $('.tabs3').width();
	var tabsnum = $('.tabs3 li').length;
	var tabliW = tabstitleW / tabsnum;
	var navfirst = 0;
	$('.tabs3 li').each(function(i) {
		$(this).css({
			'left': navfirst,
			'width':tabliW + 44
			});
		navfirst = navfirst + tabliW - 11;
	});
	
	//专家团队
	var tabstitleW = $('.tabs4').width();
	var tabsnum = $('.tabs4 li').length;
	var tabliW = tabstitleW / tabsnum;
	var navfirst = 0;
	$('.tabs4 li').each(function(i) {
		$(this).css({
			'left': navfirst,
			'width':tabliW + 26
			});
		navfirst = navfirst + tabliW - 13;
	});
	
	//同上独立页
	var tabstitleW = $('.tabs5').width();
	var tabsnum = $('.tabs5 li').length;
	var tabliW = tabstitleW / tabsnum;
	var navfirst = 0;
	$('.tabs5 li').each(function(i) {
		$(this).css({
			'left': navfirst,
			'width':tabliW + 20
			});
		navfirst = navfirst + tabliW - 20;
	});
	
	//最新活动 列表页
	var tabstitleW = $('.tabs6').width();
	var tabsnum = $('.tabs6 li').length;
	var tabliW = tabstitleW / tabsnum;
	var navfirst = 0;
	$('.tabs6 li').each(function(i) {
		$(this).css({
			'left': navfirst,
			'width':tabliW + 24
			});
		navfirst = navfirst + tabliW -12;
	});
})

function F_LCont_Close(){
	setTimeout(function(){$('.p-cont h1').removeClass('fadeInLeft').addClass('fadeOutLeft');},0);
	setTimeout(function(){$('.p-cont p').removeClass('fadeInLeft').addClass('fadeOutLeft');},300);
	setTimeout(function(){$('.righthide').removeClass('fadeInLeft').addClass('fadeOutLeft');},500);
}


jQuery(".picScroll-left").slide({mainCell:".bd",autoPage:true,effect:"left",autoPlay:true,vis:3,trigger:"click",easing:"swing"});
jQuery(".picScroll-left2").slide({mainCell:".bd",autoPage:true,effect:"left",autoPlay:true,vis:1,trigger:"click",easing:"swing"});
jQuery(".jk-left").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,vis:2,trigger:"click",easing:"swing"});



