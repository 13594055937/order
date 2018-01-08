// JavaScript Document
$(document).ready(function () {
    var _url = (window.location) + "";
    _url = _url.toString().toLocaleLowerCase();
   
    if (_url.indexOf("index") >= 0) {    //单独处理首页     
        //$(".main").css({"min-height":745,"*min-height":690});
        //return;
    }

    var L_height = $(".main_left").height();
    var R_height = $(".main_right_ny").height();
    if ((L_height - R_height) >= 0) {
        $(".main").css("min-height", L_height + 20);
    } else {
        $(".main").css("min-height", R_height + 20);
    }
        

});