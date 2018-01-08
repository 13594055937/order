/*http://www.51240.com/jsmin/     压缩网址*/
$(document).ready(function (){
    $("input:text,input:password,textarea").focus(function(){$(this).css("background","#cbfefa")}).blur(function(){$(this).css("background","#FFF").css("border","1px solid #D5D5D5")})
 
    //鼠标移开右上角注册区域的时候，实现自动隐藏效果
    $(".zc_show_box").bind("mouseleave",function(event){
    var e=window.event || event;     
    if(document.all){
        if(!e.toElement) return;
    }else if(!e.relatedTarget) {return;}
    
    $(this).hide(200);
    $("#isShowBox").attr("value",0);
    
    });
    
});
    
  
//参数说明----docu:对象,ajaxDataMethod:ajax页面方法,fillSpan:填充的span,errStr:错误Str处理,(注:此方法只接受一个参数的传递)
function changeEvent(docu,params,ajaxDataMethod,fillSpan,errStr,vl){
    $("#"+fillSpan).empty(); 
    $.ajax({     
    type: "Post",     
    url: "../ajaxPage/ajaxData.aspx/"+ajaxDataMethod,  //ajaxDataMethod是请求页面的方法名   
    //方法传参的写法一定要对，CityID为形参的名字      
    data: "{'"+params+"':'"+docu.attr("value")+"','vl':'"+vl+"'}",  //params要和请求页面方法名的参数名一致 ,多个参数以逗号区分:{'str':'我是','str2':'XXX'}
    contentType: "application/json; charset=utf-8",     
    dataType: "json",     
    success: function(json) {  
            //alert (json .d);         
           $(json.d).appendTo("#"+fillSpan);           
           //$("#"+fillSpan).html(json .d);
          //alert(json.d+"sucess");     
    },     
    error: function(err) {    
      //$("#sel").empty()； 
        $("#"+fillSpan).empty();  
        $(errStr).appendTo("#"+fillSpan);  
        //alert (err.toString());  
    }     
});        
}    
        

 



//主菜单
$(function() {
    $(".menu").children("p").each(function(index, obj) {
        $(obj).children("em").hide(); //隐藏所有子菜单
    
        $(obj).children(".lv1").hover(function() {          
            $(this).parent().children("em").show();
        });

        $(obj).mouseleave(function() {
            //隐藏菜单
            $(this).children("em").hide();
        });
    });
}); 


function validate_phone(phone)
{
     //var pattern = new RegExp(/^[0-9-+]+$/);

    //var patrn=/^0*(13|15)\d{9}$/;  //只验证手机号码

    //var isMobile=/^(?:13\d|15\d)\d{5}(\d{3}|\*{3})$/;   
   var isPhone=/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;
    //return (!((!isMobile.test(phone)) && 
    //            ())
    //        );
    
    if(checkmobile(phone)){return true;}
    else if(isPhone.test(phone)){return true;}
  return false ; 
 }



//手机号码
function checkmobile(tel)
{
    var myreg1 = /^1[358]\d{9}$/;
    var myreg2 = /^(0212[56789]{1}\d{6})$/;
    var myreg3 = /^(0108[013679]{1}\d{6})$/;
    var myreg4 = /^(0106[019]{1}\d{6})$/;
    if(tel=="")
    {
     return false;
    }
    if(!myreg1.test(tel)&&!myreg2.test(tel)&&!myreg3.test(tel)&&!myreg4.test(tel))
    {
      return false;
    } 

    return true;
}

function isEmail(strEmail) {
    return isValidMail(strEmail);
}
 

function isValidMail(sText) {
    var reMail =/^(?:[a-zA-Z0-9]+[_\-\+\.]?)*[a-zA-Z0-9]+@(?:([a-zA-Z0-9]+[_\-]?)*[a-zA-Z0-9]+\.)+([a-zA-Z]{2,})+$/;
    return (reMail.test(sText));
}


 function random(count){
            var str="";
            for(i=0;i<parseInt(count);i++){
                str=str+Math.floor(Math.random()*10);            
            }
            return str;
        }


function SendMessage(mobile,random,docu){
 
    $.ajax({     
    type: "Post",     
    url: "/ajaxPage/ajaxData.aspx/SendMessage",  //ajaxDataMethod是请求页面的方法名   
    //方法传参的写法一定要对，CityID为形参的名字      
    data: "{'mobile':'"+mobile+"','random':'"+random+"'}",  //params要和请求页面方法名的参数名一致 ,多个参数以逗号区分:{'str':'我是','str2':'XXX'}
    contentType: "application/json; charset=utf-8",     
    dataType: "json",     
    success: function(json) {                
          if(json.d=="0，发送成功（1）"){
            docu.attr("value","重新发送");
          }else {
            alert (json .d);             
          }
          //$("#"+fillSpan).html(json .d);
          //alert(json.d+"sucess");     
    },     
    error: function(err) {    
      //$("#sel").empty()； 
      // alert (err.toString());
      //alert (err.toString());  
    }     
});
 
}

function SendSms(mobile,docuHid,docuBtn){
    if($.trim(mobile)==""){ alert ("请先填写手机号码!");;return;}
     docuHid.attr("value","");
      var numRandom=random(6);
    if(!validate_phone(mobile)){
            $(".zc_Error").html("* 请填写正确的手机号码,例如:13415764179!"); return false; 
        }
    docuBtn.attr("disabled",true);
     $.ajax({     
    type: "Post",     
    url: "/ajaxPage/ajaxData.aspx/SendMessage",  //ajaxDataMethod是请求页面的方法名   
    //方法传参的写法一定要对，CityID为形参的名字      
    data: "{'mobile':'"+mobile+"','random':'"+numRandom+"'}",  //params要和请求页面方法名的参数名一致 ,多个参数以逗号区分:{'str':'我是','str2':'XXX'}
    contentType: "application/json; charset=utf-8",     
    dataType: "json",     
    success: function(json) {  
    var s=new Array();
    var str=json.d;
    s=str.split('，'); //0，发送成功（1）  
    if(s[0]=="0")
        {
            docuBtn.attr("value","重新发送");
            time(docuBtn,docuHid);
            docuHid.attr("value",numRandom);
          }else {
            docuBtn.attr("disabled",false);
            alert (json .d);             
          }
          //$("#"+fillSpan).html(json .d);
          //alert(json.d+"sucess");     
    },     
    error: function(err) {    
      //$("#sel").empty()； 
      // alert (err.toString());
      //alert (err.toString());  
    }     
});
 
}    
   var wait=60;
	function time(o,docuHid) {if (wait == 0) {//				o.removeAttribute("disabled");			
//				o.value="免费获取验证码";
                o.attr("disabled",false);
                o.attr("value","获取短信验证码");
                //docuHid.attr("value","");
				wait = 60;} else {//				o.setAttribute("disabled", true);
//				o.value="重新发送(" + wait + ")";
				//o.attr("disabled",true);
                o.attr("value","重新发送(" + wait + ")");
				wait--;
				setTimeout(function() {
					time(o)
				},
				1000)
			}
		}
//document.getElementById("btn").onclick=function(){time(this);} 
    
