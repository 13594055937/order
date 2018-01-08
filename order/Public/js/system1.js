	common();
	// 地址联动
	_init_area();
	var showArea = function() {
			$('#show').innerHTML = "<h3>省" + $('#s_province').value + " - 市" +
				$('#s_city').value + " - 县/区" +
				$('#s_county').value + "</h3>"
		}

	 // $('#datetimepicker').datetimepicker({
  //       format: 'yyyy-mm-dd hh:ii'
  //   });

	$("#btn1").click(function() {
		var card_no = $.trim($("#card_no").val());
        var card_pwd = $("#card_pwd").val();
        var yzm = $.trim($("#yanzhengma").val());
        if(card_no == "" || card_pwd ==""){
          alert("卡号、密码不能为空!");
          return false;
        }
        $.post("getCardMessage.html",{"card_no":card_no,"card_pwd":card_pwd,"yzm":yzm},function(data){
          data = JSON.parse(data);
          if(data.status == 1){
          	alert(data.message);
          	return false;
          }
	
	  if(data.status == 2){
                alert("您的卡号异常,请联系客服电话400-1383-777");
                return false;
          }
          $("#xie_card_id").val(data.data.id);
          $(".tihuo_id").html(data.data.id);
          $(".card_name").html(data.data.card_name);
          $(".guige").html(data.data.guige);
		  $("#tihuo_box2").show().siblings().hide();
        });
	});
	$("#goback").click(function(){
		$("#tihuo_box3").hide();
		$("#tihuo_box2").show().siblings().hide();
	})
	$("#btn2").click(function() {
		$("#tihuo_box3").show().siblings().hide();
	});
	$("#btn3").click(function() {
		//申请提货
		var username = $.trim($("#username").val());
        var telphone = $("#telphone").val();
        var other_telphone = $.trim($("#other_telphone").val());
        var s_province = $.trim($("#s_province").val());
        var s_city = $("#s_city").val();
        var s_county = $.trim($("#s_county").val());
        var address = $.trim($("#address2").val());
        var note = $.trim($("#note").val());
        var fahuo_time = $.trim($("#fahuo_time").val());
        var xie_card_id = $.trim($("#xie_card_id").val())
	var reg = /^1[3458]\d{9}$/
        if (!reg.test($.trim(telphone))) {
            alert('手机号码格式有填错');
            return false
        }

        if(username == "" || telphone ==""  || s_province == "" || s_city=="" || s_county=="" || fahuo_time==""){
          alert("收货人信息必填项不能为空");
          return false;
        }
        $.post("saveApply.html",{"username":username,"telphone":telphone,"other_telphone":other_telphone,
        	"s_province":s_province,"s_city":s_city,"s_county":s_county,"address":address,"note":note,"fahuo_time":fahuo_time,"xie_card_id":xie_card_id},function(data){
          data = JSON.parse(data);
          if(data.status == 1){
          	alert(data.message);
          	return false;
          }
          $("#shouhuo_message").html("<li>收货人："+data.data.username+"	</li><li>电话："+data.data.telphone+"</li><li>备用电话："+data.data.other_telphone+"</li><li>申请时间："+data.data.apply_time+"</li><li>期望发货日期："+data.data.fahuo_time+"</li>");
		  $("#tihuo_box4").show().siblings().hide();
        });
	});
	var array = []
	$("#btn4").click(function() {
		$("#is_subscribe").val(1);
		$.post("getAjaxStoreInfo.html",{},function(data){
          data = JSON.parse(data);
          array = data;
          var _html='';
			var _radio ='';
			var _li='';
			$.each(array,function(index,i){
				_html = "<label>" + i +"</label>";
				_radio = '<input type="radio" name="address"/>';
				_li += '<li>'+_radio+_html +'</li>';
			})
			$("#lists").append(_li);

			$("#lists li input").click(function(){
				var _val = $(this).siblings('label').html();
				$("#address").find('input').val(_val);
				$("#lists").hide();
			})
        });
		$("#tihuo_box5").show().siblings().hide();
	});
	$("#btn6").click(function() {
		//申请提货
		var username = $.trim($("#zt_username").val());
        var telphone = $("#zt_telphone").val();
        var send_store_name = $.trim($("#zt_store_name").val());
        var fahuo_time = $.trim($("#zt_fahuo_time").val());
        var xie_card_id = $.trim($("#xie_card_id").val())
	var reg = /^1[3458]\d{9}$/
        if (!reg.test($.trim(telphone))) {
            alert('手机号码格式有填错');
            return false
        }

		$.post("saveApply.html",{"sendType":"自提","username":username,"telphone":telphone,"send_store_name":send_store_name,"fahuo_time":fahuo_time,"xie_card_id":xie_card_id,"is_subscribe":1},function(data){
          data = JSON.parse(data);
          if(data.status == 1){
          	alert(data.message);
          	return false;
          }
		  $(".box6").show().addClass("animation");
        });
	});
	$("#btn7").click(function() {
		window.location.href = "/index.php";
		//$(".box2").show().siblings().hide();
	});
	$("#address").click(function(){
		$("#lists").show().addClass("animation");
	});
	$("#code").click(function(){
		$("#codeimg").attr("src","verify.html");
	})
	
