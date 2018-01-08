$(function(){
	$("#close").click(function(){
		$(".popbox").hide();
		$(".textboxs").show();
	});
	$("#address").click(function(){
		$("#lists").show().addClass("animation");
	});
	var array = ['上海市普陀区武宁路1066号（近曹杨路路口）','北京市朝阳区西大望路甲27号（近南磨房路路口）',  '天津市和平区气象台路57号','成都市武候区武候大道双楠段金色花园60号附91号','重庆市江北区洋河路2号同创国际近北城天街路口','合肥市庐阳区桐城路与庐江路交叉口' ,'西安市碑林区友谊西路127号','广东佛山市顺德区大良桂峰路海悦新城79号铺 ' , '深圳市福田区红岭中路2013-4号阳澄湖大闸蟹店铺','福建省厦门市思明区湖明路123号','济南市市中区泺源大街202号趵突泉南门斜对面' ,  '武汉市江汉区新华路21号-1（福星科技大厦对面）' ,'太原市小店区并州南路334号（近长风街）']
	var _html='';
	var _radio ='';
	var _li='';
	$.each(array,function(index,i){
		_html = "<span>" + i +"</span>";
		_radio = '<input type="radio" name="address"/>';
		_li += '<li>'+_radio+_html +'</li>';
	})
	$("#lists").append(_li);

	$("#lists li").click(function(){
		var _val = $(this).find('span').html();
		$(this).find("input").attr("checked",true);
		$("#address").text(_val);
		$("#lists").hide();
	});

	$("#btn3").click(function(){
		$(".box6").show();
		$(".poptext").addClass("animation");
	})
})