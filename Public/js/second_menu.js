// JavaScript Document
/// <reference path="jquery-1.7.js"/>
$(document).ready(function () {
    var second_menu = $("#second_menu .nav_scond_menu");
    second_menu.each(function (i) {
        $(this).attr("index", i);
    });
    var menu_li = $(".nav ul li:gt(0)");
    menu_li.each(function (i) {
        $(this).attr("index", i);
    });
    menu_li.hover(function (index) {
        second_menu.hide();
        $(".nav_second_menu_bg").hide();

        var _thisLeft = $(this).offset().left;
        if (_thisLeft > 800) {
            _thisLeft = _thisLeft - 220;
        }

        $(second_menu.eq($(this).attr("index"))).css({ "left": _thisLeft, "top": $(this).offset().top + 40 }).show();
        $(".nav_second_menu_bg").show();

    }, function () {
        //setTimeout('second_menu.hide(); $(".nav_second_menu_bg").hide();', 500);
    });
    second_menu.mouseover(function () {
        $(this).show();
        $(".nav_second_menu_bg").show();
    });
    second_menu.mouseout(function () {
        $(this).hide();
        $(".nav_second_menu_bg").hide();
    });

    //fj
    $(".nav").bind("mouseleave", function () {
        second_menu.hide();
        $(".nav_second_menu_bg").hide();
        //$(".nav_second_menu_bg").animate({ "height": "26px" }, 300).animate({ "height": "0px" }, 300); 
    });
});