/// <reference path="Cmn.js"/>
/// <reference path="jquery.js"/>

var CmnAjax = {
    //处理方法名，如果不是全路劲处理成全路劲
    MethodNameToUrl: function (methodName) {
        if (methodName.indexOf("/") > -1) { return methodName; }

        var _curUrl = window.location.href;
        if (_curUrl.indexOf("?") > -1) { _curUrl = _curUrl.substring(0, _curUrl.indexOf("?")); }
        if (_curUrl.indexOf("#") > -1) { _curUrl = _curUrl.substring(0, _curUrl.indexOf("#")); }

        return _curUrl + "/" + methodName;
    },
    //用Class填充单条数据    
    //containerSelector:控件容器选择器(和jquery的选择器一样，例如：.className或#controlID等)
    //methodName:WebMethod函数名
    //param:调用webmethod的参数，例如：{id:'1',name:'name'}(可为空或不传)
    //successFunc:成功时调用的函数，参数为jason格式的数据例如：{"data":[{"id":"1","name":"chi"},{"id":"2","name":"chi2"}]} (可以不传)
    //errorFunc:错误时调用的函数 (可以不传)
    FillDataByClass: function (containerSelector, methodName, param, successFunc, errorFunc) {
        methodName = this.MethodNameToUrl(methodName);

        $.ajax({
            type: "Post",
            url: methodName,
            data: param,
            contentType: "application/json;charset=uft-8",
            dataType: "json",
            success: function (json) {
                Cmn.FillDataByClass(containerSelector, eval("(" + json.d + ")"));
                if (successFunc) { successFunc(_retData); } //如果有成功函数就调用   

                return true;
            },
            error: function (httpRequest) {
                if (errorFunc) { errorFunc(); }
                Cmn.Log("FillDataByClass填充数据时Ajax报错！containerSelector:" + containerSelector + " methodName:" + methodName +
                    " param:" + param + "  错误详细信息：" + httpRequest.responseText);

                return false;
            }
        });
    },
    //填充数据    
    //containerSelector:控件容器选择器(和jquery的选择器一样，例如：.className或#controlID等)
    //methodName:WebMethod函数名
    //param:调用webmethod的参数，例如：{id:'1',name:'name'}(可为空或不传)
    //successFunc:成功时调用的函数，参数为jason格式的数据例如：{"data":[{"id":"1","name":"chi"},{"id":"2","name":"chi2"}]} (可以不传)
    //errorFunc:错误时调用的函数 (可以不传)
    FillData: function (containerSelector, methodName, param, successFunc, errorFunc) {
        methodName = this.MethodNameToUrl(methodName);

        $.ajax({
            type: "Post",
            url: methodName,
            data: param,
            contentType: "application/json;charset=uft-8",
            dataType: "json",
            success: function (json) {
                var _retData = eval("(" + json.d + ")");

                Cmn.FillData(containerSelector, _retData);
                if (successFunc) { successFunc(_retData); } //如果有成功函数就调用                

                return true;
            },
            error: function (httpRequest) {
                if (errorFunc) { errorFunc(); }
                Cmn.Log("FillData填充数据时Ajax报错！containerSelector:" + containerSelector + " methodName:" + methodName +
                    " param:" + param + "  错误详细信息：" + httpRequest.responseText);

                return false;
            }
        });
    },
    //存放DataPaging变量，翻页的时候用
    DataPagingList: new Array(),
    //填充数据带翻页控件
    //dataContainerSelector:数据控件容器选择器(和jquery的选择器一样，例如：.className或#controlID等)
    //methodName:后台WebMethod名，也可以是全路径
    //param:调用webmethod的参数，例如：{id:'1',name:'name'}(可为空或不传)    
    //pageContainerSelector:翻页控件容器选择器(和jquery的选择器一样，例如：.className或#controlID等)
    //pageSize:每页显示的记录条数
    DataPaging: function (dataContainerSelector, methodName, param, pageContainerSelector, pageSize) {
        methodName = CmnAjax.MethodNameToUrl(methodName);

        CmnAjax.DataPagingList[CmnAjax.DataPagingList.length] = this;
        var _dataPagingVarName = "CmnAjax.DataPagingList[" + (CmnAjax.DataPagingList.length - 1) + "]";

        function PagingFunction(pageNo) {
            methodName = Cmn.Func.DelParamFromUrl(methodName, "CurPage");
            methodName = Cmn.Func.AddParamToUrl(methodName, "CurPage=" + pageNo);
            methodName = Cmn.Func.DelParamFromUrl(methodName, "PageSize");
            methodName = Cmn.Func.AddParamToUrl(methodName, "PageSize=" + pageSize);

            $.ajax({
                type: "Post",
                url: methodName,
                data: param,
                contentType: "application/json;charset=uft-8",
                dataType: "json",
                success: function (json) {
                    var _retData = eval("(" + json.d + ")");

                    Cmn.FillData(dataContainerSelector, _retData);

                    return true;
                },
                error: function (httpRequest) {
                    Cmn.Log("DataPaging填充数据时Ajax报错！dataContainerSelector:" + dataContainerSelector + " methodName:" + methodName +
                        " param:" + param + " 错误详细信息：" + httpRequest.responseText);
                    return false;
                }
            });
        }

        this.Paging = new Cmn.Paging(pageContainerSelector, _dataPagingVarName + ".Paging", 21, pageSize, PagingFunction);

        //刷新数据和翻页控件
        this.Refresh = function () {
            methodName = Cmn.Func.DelParamFromUrl(methodName, "CurPage");
            methodName = Cmn.Func.AddParamToUrl(methodName, "CurPage=1");
            methodName = Cmn.Func.DelParamFromUrl(methodName, "PageSize");
            methodName = Cmn.Func.AddParamToUrl(methodName, "PageSize=" + pageSize);

            $.ajax({
                type: "Post",
                url: methodName,
                dataType: "json",
                data: param,
                contentType: "application/json;charset=uft-8",
                success: function (json) {
                    var _retData = eval("(" + json.d + ")");

                    eval(_dataPagingVarName + ".Paging.RecCount=" + _retData.RecCount + ";");
                    eval(_dataPagingVarName + ".Paging.ToPage(1);");

                    Cmn.FillData(dataContainerSelector, _retData);

                    return true;
                },
                error: function (httpRequest) {
                    Cmn.Log("DataPaging填充数据时Ajax报错！dataContainerSelector:" + dataContainerSelector + " methodName:" + methodName +
                        " param:" + param + "  错误详细信息：" + httpRequest.responseText);
                    return false;
                }
            });
        }

        this.Refresh();
    },
    //提交数据
    //containerSelector:控件容器选择器(和jquery的选择器一样，例如：.className或#controlID等)
    //methodName:服务器端Webmethod名，也可以是全路径
    //param:调用webmethod的参数，例如：{id:'1',name:'name'}(可为空或不传)
    //checkRegular:验证正则
    SubmitData: function (containerSelector, methodName, param, checkRegular, errDispSelector) {
        var _data = "";
        var _errMsg = "";
        var _radioLst = new Array(); //存放radio的name列表

        methodName = this.MethodNameToUrl(methodName);

        //获取容器中的input中的数据
        $(containerSelector).find("input[id][type!='button'][id!='__VIEWSTATE']").add(containerSelector + " textarea").add(containerSelector + " select").each(function () {
            var _name = $(this).attr("id").toString();

            if (_name != null && _name != undefined && _name.length > 3) {
                _name = _name.substring(3);

                if ($(this).attr("type") == "checkbox") { //是checkbox                    
                    if ($(this).attr("checked")) { _value = "1"; }
                    else { _value = "0"; }
                }
                else if ($(this).attr("type") == "radio") { //是radio
                    var _isExists = false;

                    for (var _i = 0; _i < _radioLst.length; _i++) {
                        if (_radioLst[_i] == $(this).attr("name")) { _isExists = true; break; }
                    }

                    if (!_isExists) { _radioLst.push($(this).attr("name")); }

                    return;
                }
                else { _value = $(this).val(); }

                //查看有没有Check如果有需要check
                if (checkRegular != null && checkRegular != undefined) {
                    if (checkRegular[_name] != null && checkRegular[_name] != undefined) {
                        var _checkRet = Cmn.CheckValid(checkRegular[_name], _value);

                        if (_checkRet != true) { //错误    
                            _errMsg += _checkRet;

                            return false;
                        }

                        //var _reg = new RegExp(checkRegular[_name].Regular);

                        //if (!_reg.test(_value)) { alert(checkRegular[_name].ErrMsg); return false; }
                    }
                }

                if (_data.length > 1) { _data += ","; }
                _data += "'" + _name + "':'" + Cmn.Func.FormatJsonData(_value) + "'";
            }
        });

        //如果没有错误，把错误显示框清空
        if (_errMsg != "") {
            if (errDispSelector) { $(errDispSelector).html(_errMsg); }
            else { alert(_errMsg); }

            return false;
        }
        else { if (errDispSelector) { $(errDispSelector).html(""); } }

        //加radio的值
        for (var _i = 0; _i < _radioLst.length; _i++) {
            if (_data.length > 1) { _data += ","; }
            _data += "'" + _radioLst[_i].substring(3) + "':'" + $("input[name='" + _radioLst[_i] + "']:checked").val() + "'";
        }

        if (_data == "") { return false; }

        //在data中加入param
        if (param && param != "") {
            param = Cmn.Func.Trim(param);
            param = param.substring(1);
            param = param.substring(0, param.length - 1);
            _data = _data + "," + param;
        }
    
        var _retVal = $.ajax({ type: "Post", "url": methodName, dataType: "json", contentType: "application/json;charset=uft-8",
            data: "{" + _data + "}", async: false
        });

        var _retText = "";

        try { _retText = eval("(" + _retVal.responseText + ")").d; }
        catch (err) {
            Cmn.Log("SubmitData ajax调用错误！ methodName:" + methodName + "  param：" + param + "  错误明细：" + _retVal.responseText);
            return "";
        }

        return _retText;
    },
    //获取远程数据(阻塞方式)
    //methodName:服务器端Webmethod名，也可以是全路径
    //param:调用webmethod的参数，例如：{id:'1',name:'name'}(可为空或不传)
    GetData: function (methodName, param) {
        methodName = this.MethodNameToUrl(methodName);

        var _retVal = $.ajax({ type: "Post", "url": methodName, dataType: "json", contentType: "application/json;charset=uft-8",
            data: param, async: false
        });

        var _retText = "";

        try { _retText = eval("(" + _retVal.responseText + ")").d; }
        catch (err) {
            Cmn.Log("GetData ajax调用错误！ methodName:" + methodName + "  param：" + param + "  错误明细：" + _retVal.responseText);
            return "";
        }

        return _retText;
    },
    //发送数据(非阻塞方式)       
    //methodName:WebMethod函数名
    //param:调用webmethod的参数，例如：{id:'1',name:'name'}(可为空或不传)
    //successFunc:成功时调用的函数，参数为jason格式的数据例如：{"data":[{"id":"1","name":"chi"},{"id":"2","name":"chi2"}]} (可以不传)
    //errorFunc:错误时调用的函数 (可以不传)
    PostData: function (methodName, param, successFunc, errorFunc) {
        methodName = this.MethodNameToUrl(methodName);

        $.ajax({
            type: "Post",
            url: methodName,
            data: param,
            contentType: "application/json;charset=uft-8",
            dataType: "json",
            success: function (json) {
                var _retData = eval("(" + json.d + ")");
                if (successFunc) { successFunc(_retData); } //如果有成功函数就调用                

                return true;
            },
            error: function (httpRequest) {
                if (errorFunc) { errorFunc(); }
                Cmn.Log("FillData填充数据时Ajax报错！ methodName:" + methodName +
                    " param:" + param + "  错误详细信息：" + httpRequest.responseText);

                return false;
            }
        });
    },
    //获取某一个字段的值
    GetField: function (fieldName, tableName, condition, orderBy) {
        if (!condition) { condition = ""; }
        else { condition = condition.replace(new RegExp("'", "g"), "\\'"); }

        if (!orderBy) { orderBy = ""; }
        else { orderBy = orderBy.replace(new RegExp("'", "g"), "\\'"); }

        var _retVal = $.ajax({ type: "Post", "url": "/CmnAjax/CmnAjax.aspx/GetField", dataType: "json",
            contentType: "application/json;charset=uft-8", async: false,
            data: "{fieldName:'" + fieldName + "',tableName:'" + tableName + "',condition:'" + condition + "',orderBy:'" + orderBy + "'}"
        });

        var _retText = "";

        try { _retText = eval("(" + _retVal.responseText + ")").d; }
        catch (err) {
            Cmn.Log("GetField ajax调用错误！ fieldName:" + fieldName + "  tableName：" + tableName + "  错误明细：" + _retVal.responseText);
            return "";
        }

        return _retText;
    }
};