$(document).ready(function() {
    //Map(data);
   // Bind(data);
});

function Map(data) {
    /*
     * 配置Raphael生成svg的属性
     */
    $("#map").html("");
    Raphael.getColor.reset();
    var R = Raphael("map", 650, 500); //大小与矢量图形文件图形对应；

    var current = null;

    var textAttr = {
        "fill": "#000",
        "font-size": "12px",
        "cursor": "pointer"
    };

    //调用绘制地图方法
    paintMap(R);

    var ToolTip = $('#ToolTip');
    ToolTip.html('地图成功绘制！请选择省市').delay(1500).fadeOut('slow');
    $('body').append("<div id='tiplayer' style='display:none'></div>");
    var tiplayer = $('#tiplayer');
    for (var state in china) {
        //分省区域着色
        china[state]['path'].color = Raphael.getColor(0.9);
        china[state]['path'].transform("t30,0");

        (function(st, state) {
            //***获取当前图形的中心坐标
            var xx = st.getBBox().x + (st.getBBox().width / 2);
            var yy = st.getBBox().y + (st.getBBox().height / 2);

            //***修改部分地图文字偏移坐标
            switch (china[state]['name']) {
                case "江苏":
                    xx += 5;
                    yy -= 10;
                    break;
                case "河北":
                    xx -= 10;
                    yy += 20;
                    break;
                case "天津":
                    xx += 20;
                    yy += 10;
                    break;
                case "上海":
                    xx += 20;
                    break;
                case "广东":
                    yy -= 10;
                    break;
                case "澳门":
                    yy += 10;
                    break;
                case "香港":
                    xx += 20;
                    yy += 5;
                    break;
                case "甘肃":
                    xx -= 40;
                    yy -= 30;
                    break;
                case "陕西":
                    xx += 5;
                    yy += 20;
                    break;
                case "内蒙古":
                    xx -= 15;
                    yy += 65;
                    break;
                default:
            }


            //***写入地名,并加点击事件,部分区域太小，增加对文字的点击事件
            china[state]['text'] = R.text(xx, yy, china[state]['name']).attr(textAttr).click(function(e) {
                clickMap();
            });

            //图形的点击事件
            $(st[0]).click(function(e) {
                clickMap();
            });
            //鼠标样式
            $(st[0]).css('cursor', 'pointer');



            function clickMap() {
                var tcolor = "#ccccca";

                $('#topList>div').hide();
                var $sl = $("#topList").find("[title='" + china[state]['name'] + "']");

                if ($sl.length > 0) {
                    $sl.show().find('p').show();
                } else {
                    $('#topList>div').show().find('p').hide();
                }
                if (current == state)
                    return;
                //重置上次点击的图形


                if (current) {
                    var strHtml = '';
                    $("#topList>div").each(function() {
                        strHtml += $(this).attr('title');
                    });
                    if (strHtml.indexOf(current) >= 0) {
                        tcolor = '#5be57e';
                    } else {
                        tcolor = "#ccccca";
                    }
                }
                current && china[current]['path'].animate({
                    transform: "t30,0",
                    fill: tcolor,
                    stroke: "#fff"
                }, 2000, "elastic");

                current = state; //将当前值赋给变量
                //对本次点击

                china[state]['path'].animate({
                    transform: "t30,0 s1.03 1.03",
                    fill: "#b60005",
                    stroke: "#b60005"
                }, 1200, "elastic");
                st.toFront(); //向上
                R.safari();

                china[current]['text'].toFront(); //***向上

                if (china[current] === undefined) return;

                $("#topList").find("[title='" + china[current]['name'] + "']").click();
                $("#topList").find("[title='" + china[current]['name'] + "']").trigger('click');
            }
        })

        (china[state]['path'], state);
    }
}
var data1 = [{
    "AreaName": "四川",
    "ShopName": "成都市第一家",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "上海",
    "ShopName": "上海浦西总部直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "北京",
    "ShopName": "北京朝阳区直销店 ",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "天津",
    "ShopName": "大闸蟹天津直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "四川",
    "ShopName": "大闸蟹成都直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "重庆",
    "ShopName": "好蟹汇重庆直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "安徽",
    "ShopName": "好蟹汇合肥直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "陕西",
    "ShopName": "陕西西安直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "广东",
    "ShopName": "广东佛山直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "广东",
    "ShopName": "好蟹汇深圳直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "福建",
    "ShopName": "好蟹汇厦门直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "山东",
    "ShopName": "好蟹汇济南直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "湖北",
    "ShopName": "好蟹汇武汉直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "山西",
    "ShopName": "好蟹汇山西太原直销店",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}, {
    "AreaName": "北京",
    "ShopName": "北京市第一家",
    "TelPone": "021456785555",
    "Fax": "02114545451",
    "Adress": "上海市闵行区虹梅南路向阳路"
}];

//***绑定数据
function Bind(data) {

    var args = $("#args *").serialize();
    var html = '';
    $.each(data, function(i, item) {
        html += "<div style='line-height:20px;margin-top:10px;cursor:pointer;font-weight:bolder;' title='" + china[item.AreaName]['name'] + "'><h3>" + china[item.AreaName]['name'] + "店:" + item['ShopName'] + "</h3><p>联系人:"+item['link']+"</p><p>手机:"+item['TelPone']+"</p><p>400电话：" + item['tel400'] + "</p><p>固定电话：" + item['guding'] + "</p><p>地址：" + item['Adress'] + "</p></div>"

        var anim = Raphael.animation({
            fill: "#5be57e",
            stroke: "#eee"
        }, 1000);
        //*** anim.delay(i * 500)增加显示延时，就是让排序省份一个一个显示，但是在IE9以下没效果，因为IE会假死，知道全部显示完
        china[item.AreaName]['path'].stop().animate(anim.delay(i * 500));
        china[item.AreaName]['isClick'] = true;
    });
    //将省区排行增加到页面，并增加点击事件查询城市排行
    $("#topList").html(html).find("div").click(function() {

        var title = $(this).attr("title");
        $(this).siblings().removeAttr("select").end().attr("select", "");

    });

}
