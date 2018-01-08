/// <reference path="jquery.js"/>
//DataJason示列 var DataJason = {"data":[{"id":"1","name":"chi"},{"id":"2","name":"chi2"}]}

Cmn_PostLogErrorCount = 0; // 发送日志到服务器的错误次数，防止浪费性能

var Cmn = {
    //配置参数
    Cfg: {
        FillDataClassPrefix: "dat", //根据Class填充数据时的Class前缀
        Paging: { //翻页控件中的Class或ID的定义，"."开头为Class,"#"开头为控件ID
            First: ".pagFirst",
            Pre: ".pagPre",
            Next: ".pagNext",
            Last: ".pagLast",
            Num: ".pagNum",
            More: ".pagMore"
        },
        LineSplitFotTemplate: "#s#" //填充数据保存行模板时的行分隔符
    },
    //html缓存,存放页面上所有需要填充的容器中的html代码
    FillHtmlCache: {},
    //信息提示（框架中用到的弹出框）
    alert: function (msg) {
       // alert(msg);
    },
    //写日志
    Log: function (msg) {
        try { console.log(msg); }
        catch (e) {
            // not support console method (ex: IE)  
        }

        if (Cmn_PostLogErrorCount <= 3) { //连续超过3次就不发了
            //发送日志到服务器
            $.ajax({
                type: "Post",
                url: "/CmnAjax/CmnAjax.aspx/WriteLog",
                data: "{msg:\"" + msg.replace(new RegExp("\"", "g"), "\\\"") + "\"}",
                contentType: "application/json;charset=uft-8",
                dataType: "json",
                success: function (json) {
                    //alert("成功!" + json.d);
                    if (json.d == "success") { Cmn_PostLogErrorCount = 0; }

                    return true;
                },
                error: function (httpRequest) {
                    alert(httpRequest.responseText);
                    Cmn_PostLogErrorCount = Cmn_PostLogErrorCount + 1; //错误次数加1

                    return false;
                }
            });
        }
    },
    //用Class填充单条数据
    //containerSelector:控件容器选择器(和jquery的选择器一样，例如：.className或#controlID等)
    //dataJson: Json格式的数据集
    FillDataByClass: function (containerSelector, dataJson) {
        //填充数据
        if (dataJson.data.length > 0) {
            for (var _key in dataJson.data[0]) {
                $(containerSelector + " ." + Cmn.Cfg.FillDataClassPrefix + _key).html(dataJson.data[0][_key]);
            }
        }

        return 1;
    },
    //填充数据    
    //containerSelector:控件容器选择器(和jquery的选择器一样，例如：.className或#controlID等)
    //dataJson: Json格式的数据集
    FillData: function (containerSelector, dataJson) {
        var _html = "";

        //如果不在缓存列表中就加入缓存列表
        _html = this.FillHtmlCache[containerSelector];

        if (_html == null || _html == undefined) { //如果列表中还没有            
            _html = ""; //this.Func.GetOuterHtml($("#" + containerID));

            //获取行模板
            $(containerSelector).each(function () {
                var _tmpHtml = Cmn.Func.GetOuterHtml($(this));
                if (_tmpHtml != "") {
                    if (_html != "") { _html += Cmn.Cfg.LineSplitFotTemplate; }
                    _html += _tmpHtml;
                }
            });

            this.FillHtmlCache[containerSelector] = _html;
        }


        if (_html == null || _html == undefined) {
            $(containerSelector).hide(); //没有数据的时候需要隐藏
            return -1;
        }

        var _retVal = "";
        var _tmpStr = "";

        //填充数据
        var _htmlList = _html.split(Cmn.Cfg.LineSplitFotTemplate);
        var _loc = 0; //第几个模板行


        if (dataJson.data) { //有data节点
            if (dataJson.data.length < 1) {
                $(containerSelector).hide(); //没有数据的时候需要隐藏
                return -1;
            }

            for (var _i = 0; _i < dataJson.data.length; _i++) {
                _tmpStr = _htmlList[_loc];

                _loc = _loc + 1;
                if (_loc >= _htmlList.length) { _loc = 0; }

                for (var _key in dataJson.data[_i]) {
                    _tmpStr = _tmpStr.replace(new RegExp("{" + _key + "}", "g"), dataJson.data[_i][_key]);
                }

                _retVal += _tmpStr;
            }
        }
        else { //没有data节点
            _tmpStr = _htmlList[_loc];

            for (var _key in dataJson) {
                _tmpStr = _tmpStr.replace(new RegExp("{" + _key + "}", "g"), dataJson[_key]);
            }

            _retVal += _tmpStr;
        }

        //输出到页面
        var _hasSetContent = false;
        $(containerSelector).each(function () {
            if (_hasSetContent) { $(this).replaceWith(""); }
            else { $(this).replaceWith(_retVal); _hasSetContent = true; }
        });

        //        $("#" + containerID).first().attr("id", "cmn_" + containerID);

        //        var _tmpContainer = $("#" + containerID);

        //        while (_tmpContainer.html() != null) {
        //            _tmpContainer.replaceWith("");
        //            _tmpContainer = $("#" + containerID);
        //        }

        //        $("#cmn_" + containerID).replaceWith(_retVal);

        return 1;
    },
    //翻页控件
    //containerSelector:控件容器选择器(和jquery的选择器一样，例如：.className或#controlID等)
    //pagingVarName:翻页控件变量的名称
    //recCount:记录总数量
    //pageSize:每页的记录数
    //pagingFunction:每次翻页的时候触发的函数
    Paging: function (containerSelector, pagingVarName, recCount, pageSize, pagingFunction) {
        this.containerSelector = containerSelector;
        this.RecCount = parseInt(recCount);
        this.PageSize = parseInt(pageSize);
        this.PagingFunction = pagingFunction;
        this.ShowNumCount = 5;
        this.MoreHtml = ""; //保存more的html代码
        this.SmartHideButton = true; //是否智能隐藏首页最后一页等按钮
        this.ActiveClass = ""; //数字当前页状态class

        this.ToPage = function (pageNo) {
            //设置页码
            function SetPageNo(numHtml, pageNo) {
                numHtml = numHtml.replace('onclick="onclick"', 'onclick="' + pagingVarName + '.ToPage(' + pageNo + ')"');
                numHtml = numHtml.replace("{num}", pageNo);
                return numHtml;
            };

            var _container = $(containerSelector);

            var _PageCount = parseInt((this.RecCount + this.PageSize - 1) / this.PageSize);
            if (_PageCount == 0) { _PageCount = 1; } //必须至少有一页

            _container.find(Cmn.Cfg.Paging.First).attr("onclick", pagingVarName + ".ToPage(1)");
            _container.find(Cmn.Cfg.Paging.Pre).attr("onclick", pagingVarName + ".ToPage(" + (pageNo - 1 < 1 ? 1 : pageNo - 1) + ")");
            _container.find(Cmn.Cfg.Paging.Next).attr("onclick", pagingVarName + ".ToPage(" + (pageNo + 1 > _PageCount ? _PageCount : pageNo + 1) + ")");
            _container.find(Cmn.Cfg.Paging.Last).attr("onclick", pagingVarName + ".ToPage(" + _PageCount + ")");

            _container.find(Cmn.Cfg.Paging.First).show();
            _container.find(Cmn.Cfg.Paging.Pre).show();
            _container.find(Cmn.Cfg.Paging.Next).show();
            _container.find(Cmn.Cfg.Paging.Last).show();

            if (this.SmartHideButton) {
                if (pageNo == 1) {
                    _container.find(Cmn.Cfg.Paging.First).hide();
                    _container.find(Cmn.Cfg.Paging.Pre).hide();
                }
                if (pageNo == _PageCount) {
                    _container.find(Cmn.Cfg.Paging.Next).hide();
                    _container.find(Cmn.Cfg.Paging.Last).hide();
                }
            }

            //加页码按钮
            //生成数字
            _container.find(Cmn.Cfg.Paging.Num).first().attr("onclick", "onclick"); //先设置，方便后面替换
            _container.find(Cmn.Cfg.Paging.Num).first().text(_container.find(Cmn.Cfg.Paging.Num).first().text().replace(/\d+/, "{num}"));
            if (this.ActiveClass != "") { _container.find(Cmn.Cfg.Paging.Num).first().removeClass(this.ActiveClass); }
            var _numHtml = Cmn.Func.GetOuterHtml(_container.find(Cmn.Cfg.Paging.Num).first());
            var _numHtmlActive = _numHtml;
            if (this.ActiveClass != "") {
                _container.find(Cmn.Cfg.Paging.Num).first().addClass(this.ActiveClass);
                _numHtmlActive = Cmn.Func.GetOuterHtml(_container.find(Cmn.Cfg.Paging.Num).first());
            }

            //删除数字,保留一个
            _container.find(Cmn.Cfg.Paging.Num).first().attr("id", "pagNum_Temp");
            _container.find(Cmn.Cfg.Paging.Num + "[id!=pagNum_Temp]").replaceWith("");

            if (this.MoreHtml == "") {
                _container.find(Cmn.Cfg.Paging.More).first().attr("onclick", "onclick"); //先设置，方便后面替换
                this.MoreHtml = Cmn.Func.GetOuterHtml(_container.find(Cmn.Cfg.Paging.More).first());
            }

            _container.find(Cmn.Cfg.Paging.More).replaceWith("");

            var _numLst = "";

            //先计算从哪一页开始加
            var _pageNo = pageNo - parseInt((this.ShowNumCount - 1) / 2) -
                ((_PageCount - pageNo) >= parseInt((this.ShowNumCount - 1) / 2) ? 0 : parseInt((this.ShowNumCount - 1) / 2) - (_PageCount - pageNo));

            if (_pageNo < 1) { _pageNo = 1; }

            if (_pageNo > 1) { _numLst += SetPageNo(this.MoreHtml, _pageNo - 1); } //增加数字列表前面的More

            var _i;
            for (_i = _pageNo; _i < _pageNo + this.ShowNumCount && _i <= _PageCount; _i++) {
                if (_i == pageNo) { _numLst += SetPageNo(_numHtmlActive, _i); }
                else { _numLst += SetPageNo(_numHtml, _i); }
            }

            if (_i - 1 < _PageCount) { _numLst += SetPageNo(this.MoreHtml, _i); } //增加数字列表后面的More


            _container.find("#pagNum_Temp").replaceWith(_numLst);

            if (pagingFunction) { pagingFunction(pageNo); }
        }

        this.ToPage(1);
    },
    Func: {
        //获取元素自身的html代码
        GetOuterHtml: function (obj) {
            return $('<div></div>').append(obj.clone()).html();
        },
        //往AjaxData中增加参数
        AddParamToAjaxData: function (ajaxData, key, value) {
            if (ajaxData == null || ajaxData == undefined || ajaxData == "") { ajaxData = "{}"; }

            if (ajaxData.replace(" ", "").length > 2) {
                return ajaxData.replace("}", ",'" + key + "':'" + value + "'}");
            }
            else { return ajaxData.replace("}", "'" + key + "':'" + value + "'}"); }
        },
        //Url中增加参数
        AddParamToUrl: function (url, param) {
            if (url.indexOf("?") >= 0) { url += "&" + param; }
            else { url += "?" + param; }

            return url;
        },
        //从Url中删除某个参数
        DelParamFromUrl: function (url, paramName) {
            var _retVal = "";
            var _tmpStr;
            var _loc = url.indexOf(paramName + "=");

            if (_loc < 0) {
                _loc = url.indexOf(paramName + " ");

                if (_loc < 0) { return url; }
            }

            _retVal = url.substring(0, _loc).replace(/(^\s*)|(\s*$)/g, '');
            _tmpStr = url.substring(_loc);

            if (_tmpStr.indexOf("&") >= 0) { //后面还有参数
                _retVal += _tmpStr.substring(_tmpStr.indexOf("&") + 1);
            }
            else {  //后面没有参数
                if (_retVal.length > 0) {
                    if (_retVal.charAt(_retVal.length - 1) == '?' || _retVal.charAt(_retVal.length - 1) == '&') {
                        _retVal = _retVal.substring(0, _retVal.length - 1);
                    }
                }
            }

            return _retVal;
        },
        //去除字符串前后的空格
        Trim: function (str) {
            return str.replace(/(^\s*)|(\s*$)/g, '');
        },
        //过滤json数据中的逗号和斜杠等
        FormatJsonData: function (str) {
            str = str.replace(new RegExp("'", "g"), "\\'");
            str = str.replace(new RegExp("\\\\", "g"), "\\\\");
            return str;
        }
    },
    //生成一个新的验证
    NewCheckInfo: function (regular, errMsg, requiredErrMsg) {
        return { 'Regular': regular, 'ErrMsg': errMsg, 'RequiredErrMsg': requiredErrMsg };
    },
    //验证是否有效，用CheckInfo中的正则等，验证inputTxt是否合法
    CheckValid: function (checkInfo, inputTxt) {
        if (checkInfo.RequiredErrMsg && checkInfo.RequiredErrMsg != "") { //说明是必填项
            if (Cmn.Func.Trim(inputTxt) == "") { return checkInfo.RequiredErrMsg; }
        }

        var _reg = new RegExp(checkInfo.Regular.Regular);

        if (!_reg.test(inputTxt)) {
            if (checkInfo.ErrMsg && checkInfo.ErrMsg != "") {  return checkInfo.ErrMsg; }
            else { return checkInfo.Regular.ErrMsg; }
        }

        return true;
    },
    //常用正则
    Regular: {
        "Email": { Regular: "^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$", ErrMsg: "请输入正确的Email地址！" },
        "MobilePhone": { Regular: "^1[0-9]{10}$", ErrMsg: "请输入正确的手机号码！" }
    }
};






