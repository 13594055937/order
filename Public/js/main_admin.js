// JavaScript Document
$(function(){
	/**
	 * left menu change
	 */
	$(".left dl dt").click(function(){
		$(this).siblings("dd").show();
		$(this).parent().siblings('dl').children('dd').hide();
	});
	//删除信息
	$(".del-one").click(function(){
		var url = $(this).attr('data');
		var objRemove = $(this).closest('tr');
		jConfirm('是否删除该信息！','提示',function(del){
			if(del == true){
				$("body").mask('提交中。。。');
				if(url == ""){
					alert("未知错误，请刷新重试！");
					return false;
				}
				$.getJSON(url,function(msg){
					$("body").unmask();
				   	alert(msg.msg);
				     if(msg.status=="true"){
				     	objRemove.remove();
				     }
			   });
			}
		});
		return false;
	});
});

           