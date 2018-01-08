require(['jquery', 'common'], function($, common) {
    common.common();

    // var data = [{
    //     area: '上海浦西分店',
    //     name: '好蟹汇阳澄湖大闸蟹上海浦西总部直销店 ',
    //     phone: "400-880-1517",
    //     address: "上海市普陀区武宁路1066号（近曹杨路路口）"
    // }, {
    //     area: "北京朝阳分店",
    //     name: '好蟹汇阳澄湖大闸蟹北京朝阳区直销店 ',
    //     phone: "400-880-1517",
    //     address: "北京市朝阳区西大望路甲27号（近南磨房路路口）"
    // }, {
    //     area: "天津和平分店",
    //     name: '好蟹汇阳澄湖大闸蟹天津直销店 ',
    //     phone: "400-880-1517",
    //     address: "天津市和平区气象台路57号"
    // }, {
    //     area: "成都武候分店",
    //     name: '好蟹汇阳澄湖大闸蟹成都直销店 ',
    //     phone: "400-880-1517",
    //     address: "成都市武候区武候大道双楠段金色花园60号附91号"
    // }, {
    //     area: "重庆江北分店",
    //     name: '好蟹汇阳澄湖大闸蟹重庆直销店 ',
    //     phone: "400-880-1517",
    //     address: "重庆市江北区洋河路2号同创国际（近北城天街路口）"
    // }, {
    //     area: "合肥庐阳分店",
    //     name: '好蟹汇阳澄湖大闸蟹合肥直销店 ',
    //     phone: "400-880-1517",
    //     address: "合肥市庐阳区桐城路与庐江路交叉口"
    // }, {
    //     area: "西安碑林分店",
    //     name: '好蟹汇阳澄湖大闸蟹西安直销店 ',
    //     phone: "400-880-1517",
    //     address: "西安市碑林区友谊西路127号"
    // }, {
    //     area: "广东佛山分店",
    //     name: '好蟹汇阳澄湖大闸蟹佛山直销店 ',
    //     phone: "400-880-1517",
    //     address: "广东佛山市顺德区大良桂峰路海悦新城79号铺"
    // }, {
    //     area: "深圳福田分店",
    //     name: '好蟹汇阳澄湖大闸蟹深圳直销店 ',
    //     phone: "400-880-1517",
    //     address: "深圳市福田区红岭中路2013-4号阳澄湖大闸蟹店铺"
    // }, {
    //     area: "福建厦门分店",
    //     name: '好蟹汇阳澄湖大闸蟹厦门直销店 ',
    //     phone: "400-880-1517",
    //     address: "福建省厦门市思明区湖明路123号"
    // }, {
    //     area: "济南市中分店",
    //     name: '好蟹汇阳澄湖大闸蟹济南直销店 ',
    //     phone: "400-880-1517",
    //     address: "济南市市中区泺源大街202号趵突泉南门斜对面"
    // }, {
    //     area: "武汉江汉分店",
    //     name: '好蟹汇阳澄湖大闸蟹武汉直销店 ',
    //     phone: "400-880-1517",
    //     address: "武汉市江汉区新华路21号-1（福星科技大厦对面）"
    // }, {
    //     area: "山西太原分店",
    //     name: '好蟹汇阳澄湖大闸蟹山西太原直销店 ',
    //     phone: "400-880-1517",
    //     address: "太原市小店区并州南路334号（近长风街）"
    // }];
    // var list = '';
    // $.each(data, function(index, item) {
    //     list += "<li>" + item['area'] + '</li>';
    // })
    // $('.branch .list').html(list);
    // 百度地图API功能
    var storeinfos = $($(".storeinfo")[0]);
    showmap();
    $(".storeinfo").click(function(){
	storeinfos = $(this);
	$("#branch").html("<h3>"+storeinfos.data("name")+"</h3><p>联系人："+storeinfos.data("contact_name")+"</p> <p>手机："+storeinfos.data("contact_tel")+"</p>"
           +" <p>400电话："+storeinfos.data("contact400")+"</p><p>固定电话:"+storeinfos.data("telphone")+"</p><p>地址:"+storeinfos.data("address")+"</p>");
	showmap();
    })
    function showmap(){
    var map = new BMap.Map('allmap');
    var poi = new BMap.Point(storeinfos.data("jingdu"), storeinfos.data("weidu"));
    map.centerAndZoom(poi, 16);
    map.enableScrollWheelZoom();

    var content = '<div style="margin:0;line-height:20px;padding:2px;">' +
        '<img src="/Public/images/'+storeinfos.data("store_img1")+'" alt="" style="float:right;zoom:1;overflow:hidden;width:100px;height:100px;margin-left:3px;"/>' +
	"<p>联系人："+storeinfos.data("contact_name")+"</p> <p>手机："+storeinfos.data("contact_tel")+"</p>"
           +" <p>400电话："+storeinfos.data("contact400")+
        '地址：'+storeinfos.data("address")+'<br/>固定电话：'+storeinfos.data("telphone")+'<br/>' +
        '</div>';

    //创建检索信息窗口对象
    var searchInfoWindow = null;
    searchInfoWindow = new BMapLib.SearchInfoWindow(map, content, {
        title: storeinfos.data("name"), //标题
        width: 290, //宽度
        height: 105, //高度
        panel: "panel", //检索结果面板
        enableAutoPan: true, //自动平移
        searchTypes: [
            BMAPLIB_TAB_SEARCH, //周边检索
            BMAPLIB_TAB_TO_HERE, //到这里去
            BMAPLIB_TAB_FROM_HERE //从这里出发
        ]
    });
    var marker = new BMap.Marker(poi); //创建marker对象
    marker.enableDragging(); //marker可拖拽
    searchInfoWindow.open(marker);
    map.addOverlay(marker); //在地图中添加marker
    //样式1
    var searchInfoWindow1 = new BMapLib.SearchInfoWindow(map, "信息框1内容", {
        title: "信息框1", //标题
        panel: "panel", //检索结果面板
        enableAutoPan: true, //自动平移
        searchTypes: [
            BMAPLIB_TAB_FROM_HERE, //从这里出发
            BMAPLIB_TAB_SEARCH //周边检索
        ]
    });
    }
    function openInfoWindow1() {
        searchInfoWindow1.open(new BMap.Point(storeinfos.data("jingdu"), storeinfos.data("weidu")));
    }

})
