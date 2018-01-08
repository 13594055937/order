$(function() {
  var dom = document.getElementById("map");
  var myChart = echarts.init(dom);
  var app = {};
  option = null;
  var data = [{
    name: '青岛',
    value: '好蟹汇青岛总部',
    adress: "我是青岛大妈黄渤"
  }, {
    name: '上海',
    value: "好蟹汇上海总部",
    adress: "上海市闵行区顾戴路3009号祥鹿大厦1007室"
  }, {
    name: '攀枝花',
    value: 25,
    adress: "我是青岛大妈黄渤"
  }, {
    name: '泸州',
    value: 57,
    adress: "我是青岛大妈黄渤"
  }, {
    name: '克拉玛依',
    value: 72,
    adress: "我是青岛大妈黄渤"
  }];

  var geoCoordMap = {
    '青岛': [120.33, 36.07],
    '上海': [121.48, 31.22],
    '攀枝花': [101.718637, 26.582347],
    '泸州': [105.39, 28.91],
    '克拉玛依': [84.77, 45.59]

  };

  function convertData(data) {
    var res = [];
    for (var i = 0; i < data.length; i++) {
      var geoCoord = geoCoordMap[data[i].name];
      if (geoCoord) {
        res.push({
          name: data[i].name,
          value: geoCoord.concat('<h3>' + data[i].value + '</h3><p>' + data[i].adress + '</p>')
        });
      }
    }
    return res;
  };

  function randomValue() {
    return Math.round(Math.random() * 1000);
  }



  option = {
    tooltip: {
      trigger: 'item',
      position: 'top',
      formatter: function(params, ticket, callback) {
        var $pna = params.name;
        var res = '';

        for (var i = 0; i < data.length; i++) {
          if (data[i].name == $pna) {
            res = '<div class="tooltip"><h3>' + data[i].value + '</h3><p>' + data[i].adress + '</p></div>'; //设置自定义数据的模板，这里的模板是图片
            break;
          }
        }
        return res
      },
    },
    geo: {
      map: 'china',
      roam: true,
      label: {
        normal: {
          show: true,
          textStyle: {
            color: 'rgba(0,0,0,0.4)'
          }
        }
      },
      itemStyle: {
        normal: {
          borderColor: '#eee',
          label: {
            show: true
          },
          color: '#cccccc' //刚才说的图例颜色设置  
        },
        emphasis: {
          areaColor: null,
          shadowOffsetX: 0,
          shadowOffsetY: 0,
          shadowBlur: 20,
          borderWidth: 0,
          shadowColor: 'rgba(0, 0, 0, 0.5)',
          label: {
            show: false
          },
          color: '#b00005'
        }
      }
    },
    series: [{
      type: 'scatter',
      coordinateSystem: 'geo',
      data: convertData(data),
      symbolSize: [15, 23],
      symbol: 'path://M805.888 344.448C805.888 189.568 665.792 64 510.08 64S218.112 189.568 218.112 344.448c0 143.616 288.704 487.488 288.704 487.488S805.888 492.864 805.888 344.448L805.888 344.448zM417.024 354.368c0-52.992 43.904-96 97.984-96 54.144 0 97.984 42.944 97.984 96s-43.904 96-97.984 96C460.928 450.304 417.024 407.36 417.024 354.368L417.024 354.368zM608.832 840.32c0.832 2.496 1.28 5.12 1.28 7.68 0 26.496-43.904 48-97.984 48-54.08 0-97.984-21.504-97.984-48 0-2.624 0.448-5.184 1.28-7.68C356.096 851.328 316.16 872.128 316.16 896c0 35.392 87.744 64 195.968 64s195.968-28.608 195.968-64C708.032 872.128 668.096 851.328 608.832 840.32L608.832 840.32zM608.832 840.32',
      label: {
        normal: {
          formatter: '{b}',
          position: 'right',
          show: false
        },
        emphasis: {
          show: true
        }
      },
      itemStyle: {
        normal: {
          color: '#5d5d5d'
        }
      }
    }]
  };;
  if (option && typeof option === "object") {
    myChart.setOption(option, true);
  }
});