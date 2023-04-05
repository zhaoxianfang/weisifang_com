!function (s) {
    "use strict";
    /**
     * 说明：依赖Jquery,layer
     * @type {Object}
     */
    var my = {
        init: function () {
            my.form();
            my.backToTop();
            // my.noticeTool();
            my.tips();
            my.stop_jump();
        },
        // layer.open里面的js方法会挂载在fn上,例如 my.fn.test() ,有时弹出层里面dom调用 页面的js里面的function会报undefined ，就可以使用此方法了
        fn: {},
        // layer.open里面的dom会挂载在obj上 ，例如body.find('input').val('Hi，我是从父页来的');
        obj: {},
        _data: {
            layerLastIndex: 0, // layer最后一次open 的index 值
        },
        getData: function (key) {
            return my._data["my_" + key] || window.sessionStorage.getItem("my_" + key);
        },
        setData: function (key, value) {
            window.sessionStorage.setItem("my_" + key, value);
            return my._data["my_" + key] = value;
        },
        // 设置当前 的 layer弹出层 页面最大化
        setCurrentPageFull: function () {
            try {
                setTimeout(function () {
                    my.getData("layerLastIndex") && layer.full(my.getData("layerLastIndex"));
                }, 300);
            } catch (error) {
            }
        },
        form: function () {
            var form;
            if (arguments.length > 0) {
                form = [];
                for (i = 0; i < arguments.length; i ++) {
                    form[i] = arguments[i];
                }
            } else {
                //排除table插件 commonsearch的表单 和排除 .unbind-form 的表单
                form = $("form").not(".form-commonsearch, .unbind-form");
            }
            $(form).unbind();
            if (form) {
                var form_length = form.length;
                //有1到多个form
                for (var index = 0; index < form_length; index ++) {
                    //解绑
                    $(form[index]).unbind();
                    $(form[index]).find("[type=submit]").unbind();
                    //设置禁止ajax提交的标识 符号 forbid_ajax
                    if (typeof ($(form[index]).attr("forbid_ajax")) !== "undefined") {
                        if (($(form[index]).attr("forbid_ajax")) !== "false") {
                            continue; //跳出 不进行绑定
                        }
                    }
                    var _hasRun = false;

                    function _submit_event(_this, e) {
                        e.preventDefault();
                        if (_hasRun === true) {
                            return false;
                        }
                        _hasRun = true;
                        $(_this).find(":submit").attr("disabled", "disabled");
                        $(_this).find("button[type=submit], input[type=submit]").prop("disabled", "disabled");
                        setTimeout(function () {
                            _hasRun = false;
                            $(_this).find(":submit").attr("disabled", false);
                            $(_this).find("button[type=submit], input[type=submit]").prop("disabled", false);
                        }, 1000);
                        var this_form = $(_this).parents("form").length > 0 ? $(_this).parents("form") : form;
                        //取消普通form 事件
                        if (my.unbind_form) {
                            return false;
                        }
                        return my.formSubmit($(this_form), e);
                    }

                    //绑定事件
                    $(form[index]).find("[type=submit]").click(function (e) {
                        e.preventDefault();
                        var _this = this;
                        return _submit_event(_this, e);
                    });
                    $(form[index]).on("submit", function (e) {
                        e.preventDefault();
                        var _this = this;
                        return _submit_event(_this, e);
                    });
                }
            }
            return false;
        },
        /**
         * form 提交
         * @Author   ZhaoXianFang
         * @DateTime 2018-06-19
         * @param    {[type]}     form [description]
         * @return   {[type]}          [description]
         */
        formSubmit: function (form, e) {
            e && e.preventDefault();
            // console.log('form',form)
            var form_url = form.attr("action");
            form_url = form_url ? form_url : location.href;
            //表单提交前的操作
            if (typeof (form_before) === "function") {
                if (form_before(e) === false) {
                    e.preventDefault();
                    return false;
                }
            }
            var formdata = my.getFormData(form);
            //验证数据
            var validate = my.formValidate(form);
            if (validate === false) {
                return false;
            }
            var index = layer.msg("请稍后……", 0);
            my.ajax(form_url, formdata, function (succ) {
                //返回数据 非200：失败 ；200：成功
                if (succ.code === 200) {
                    layer.msg(succ.message);
                } else {
                    //内容,标题
                    if (succ.code === 500 && !succ.message) {
                        layer.msg("系统异常:请与管理员联系");
                    } else {
                        layer.msg(succ.message);
                    }
                }
                //表单提交后的操作
                if (typeof (form_after) === "function") {
                    if (form_after(succ) === false) {
                        e && e.preventDefault();
                        return false;
                    }
                }
                succ.wait = succ.wait ? succ.wait : 3;
                //关闭弹出层
                try {
                    if (typeof succ.close !== "undefined" && succ.close === 1) {
                        setTimeout(function () {
                            //关闭弹出层
                            layer.close(index);
                            parent.layer.close(my.getData("layerLastIndex")); //关闭弹出层
                        }, succ.wait * 1000);
                    }
                } catch (err) {
                    return false;
                }
                if ( !my.isEmpty(succ.url)) {
                    setInterval(function () {
                        //跳转
                        window.location.href = succ.url;
                    }, succ.wait * 1000);
                }
            }, function (errorMsg, err) {
                if (typeof (form_after) === "function") {
                    form_after(err);
                }
                return false;
            });
            // return true;
        },
        getFormData: function (form) {
            var formDataObj = new FormData();
            var upFile = false; //是否上传文件
            $(form).find(":file").each(function (i, v) {
                if (my.isEmpty($(v).attr("name")) === false) {
                    upFile = true;
                    formDataObj.append($(v).attr("name"), my.isEmpty($(v)[0].files[0]) ? "" : $(v)[0].files[0]);
                }
            });
            if (upFile) {
                var formdata = form.serializeArray();
                $.each(formdata, function () {
                    formDataObj.append(this.name, this.value);
                });
                return formDataObj;
            } else {
                // return form.serializeArray();
                return form.not(":file").serialize();
            }
        },
        /**
         * [formValidate 表单验证]
         * @Author   ZhaoXianFang
         * @DateTime 2018-06-27
         * @param    {[type]}     formEle     [form id 或者 form内的 元素id或者class或者选择器 ，为空则默认form]
         * @param    {Boolean}    isFormEle [是不是form id 默认true]
         * @return   {[type]}               [description]
         *    说明：data-rule 包含 required 关键字表示必须验证，不包含表示不为空时候验证
         *
         *   data-rule="required"
         *   data-rule="required|mobile"
         *   data-rule="mobile"
         *   data-rule="length(1~5)" //验证长度可缺省一个参数
         *   data-rule="remote(check/mobile)" // 远程url到'check/mobile'地址 验证当前填入的值; 接口请求参数{元素name:value};接口返回必须包含{msg:'提示信息',check:true|false}
         *
         */
        formValidate: function (formEle, isFormEle) {
            formEle = formEle ? formEle : "form";
            isFormEle = (isFormEle === undefined) ? true : isFormEle;
            var form;
            if (isFormEle) {
                form = $(formEle);
            } else {
                form = $(formEle).parents("form");
            }
            //移除样式
            $("").removeClass("is-invalid"); //bs4
            // e.preventDefault();
            var issuccess = true;
            $(form).find("input,textarea,select").each(function (i, v) {
                var ele_type = $(this).attr("type");
                if (ele_type === "radio" || ele_type === "checkbox") {
                    var checked_val = $("input[name=\"" + $(this).attr("name") + "\"]:checked").val();
                    if (my.regExpFormValidate(checked_val, $(this).data("rule"), $(this)) === false) {
                        //添加 错误样式
                        $(this).addClass("is-invalid"); // bs4
                        my.backToDom($(this)); // 滚动到元素dom 节点对象
                        issuccess = false;
                        layer.msg("请勾选表单选项");
                        return false; //跳出循环
                    } else {
                        issuccess = true;
                        return true;
                    }
                }
                if (ele_type === "file") {
                    try {
                        $(this).get(0).files[0].size;
                        issuccess = true;
                        return true;
                    } catch (e) {
                        //开始验证
                        var reg_exp_str = $(this).data("rule");
                        if ( !my.isEmpty(reg_exp_str)) {
                            var checkArr = reg_exp_str.split("|");
                            if (($.inArray("required", checkArr) > - 1) && my.isEmpty($(this).data("value"))) {
                                //添加 错误样式
                                $(this).addClass("is-invalid");
                                my.backToDom($(this)); // 滚动到元素dom 节点对象
                                issuccess = false;
                                layer.msg("请上传指定的文件");
                                return false; //跳出循环
                            }
                            if (my.regExpFormValidate($(this).data("value"), $(this).data("rule"), $(this)) === false) {
                                //添加 错误样式
                                $(this).addClass("is-invalid");
                                my.backToDom($(this)); // 滚动到元素dom 节点对象
                                issuccess = false;
                                layer.msg("文件验证失败");
                                return false; //跳出循环
                            }
                        }
                        issuccess = true;
                        return true;
                    }
                }
                // SELECT,INPUT,TEXTAREA
                // 移除错误样式
                $(this).removeClass("is-invalid");
                if ($(this).data("rule") !== undefined && !$(this).is(":hidden") && !my.isEmpty($(this).data("rule"))) {
                    if (my.regExpFormValidate($(this).val(), $(this).data("rule"), $(this)) === false) {
                        //添加 错误样式
                        $(this).addClass("is-invalid");
                        my.backToDom($(this)); // 滚动到元素dom 节点对象
                        issuccess = false;
                        layer.msg("请按照指定格式填写表单");
                        return false; //跳出循环
                    }
                }
                if ($(this).attr("required") !== undefined && my.isEmpty($(this).val()) && !$(this).is(":hidden")) {
                    //添加 错误样式
                    $(this).addClass("is-invalid");
                    my.backToDom($(this)); // 滚动到元素dom 节点对象
                    issuccess = false;
                    layer.msg("表单未填写完整");
                    return false; //跳出循环
                }
            });
            return issuccess;
        },
        //找到页面中包含指定对象的form表单
        get_current_form_obj: function (ele) {
            var _obj = null;
            $("form").each(function (i) {
                var obj = $(this).find(ele);
                if (obj.length === 1) {
                    _obj = $(this);
                }
            });
            return _obj;
        },
        //查询Url参数
        query: function (name, url) {
            if ( !url) {
                url = window.location.href;
            }
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&/]" + name + "([=/]([^&#/?]*)|&|#|$)"),
                results = regex.exec(url);
            if ( !results) {
                return null;
            }
            if ( !results[2]) {
                return "";
            }
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        },
        //修复URL
        fixurl: function (url) {
            if (url.substr(0, 1) !== "/") {
                var r = new RegExp("^(?:[a-z]+:)?//", "i");
                if ( !r.test(url)) {
                    // url = Config.moduleurl + "/" + url;
                    url = "/" + url;
                }
            }
            // else if (url.substr(0, 8) === "/addons/") {
            //     url = Config.__PUBLIC__.replace(/(\/*$)/g, "") + url;
            // }
            return url;
        },
        /**
         * layer iframe窗 打开一个弹出窗口
         * @Author   ZhaoXianFang
         * @DateTime 2018-05-31
         * @param    {[type]}     url   [弹出层的地址]
         * @param    {[type]}     title [弹出层标题]
         * @param    {[type]}     options   [设置参数]
         * @return   {[type]}           [description]
         */
        open: function (url, title, options) {
            // param 参数
            // param = param ? param.name + "=" + param.value : '';
            title = options && options.title ? options.title : (title ? title : "信息");
            url = my.fixurl(url);
            url = url + (url.indexOf("?") > - 1 ? "&" : "?") + "dialog=1";
            // url = param ? url + (url.indexOf("?") > -1 ? "&" : "?") + param : url;
            //
            options = options ? options : {};
            // full 全屏
            options.area === "full" && (options.area = ["100%", "100%"]);
            // var area = Fast.config.openArea != undefined ? Fast.config.openArea : [$(window).width() > 800 ? '800px' : '95%', $(window).height() > 600 ? '600px' : '95%'];
            var area = my.window.width() > 893 ? ["893px", "600px"] : (my.window.width() > 480 ? [my.window.width() * 0.9 + "px", "95%"] : "auto"); //弹出层大小
            //默认layer open 参数配置
            var defaultOptions = {
                type: 2,
                title: "信息", //标题
                // skin: 'layui-layer-lan', //样式类名 深蓝
                shadeClose: false, //是否点击遮罩关闭
                shade: [0.8, "#393D49"], //遮罩 关闭设置 false
                maxmin: true, //开启最大化最小化按钮
                area: area, //弹出层大小
                // area: ['100%', '100%'], //弹出层大小
                moveOut: true, //是否允许拖拽到窗口外
                offset: "auto", //弹出层坐标（位置） auto(默认，垂直水平居中)  t（顶部）r(右边缘)b(底部)l(左边)lt(左上角)lb(左下角)rt(右上)rb(右下)
                content: url,
                zIndex: layer.zIndex,
                closeRefresh: false, //关键弹出层时候是否是否刷新页面，默认true
                closeCallback: false //关键弹出层时候回调
            };
            //在页面中 设置 open 的窗口大小
            if (typeof (set_open_area) === "function") {
                // 返回的数据是数组
                var setAreaVal = set_open_area();
                if ((setAreaVal !== undefined) && (setAreaVal instanceof Array)) {
                    options["area"] = setAreaVal;
                }
            }
            options.title = title;
            // options = my.mergeJSON(defaultOptions, options);
            options = $.extend({}, defaultOptions, options);
            options = $.extend({
                // type: 2,
                // title: title,
                // shadeClose: true,
                // shade: false,
                // maxmin: true,
                // moveOut: true,
                // area: area,
                // content: url,
                // zIndex: layer.zIndex,
                success: function (layero, index) {
                    try {
                        var bodyDom = layer.getChildFrame("body", index);
                        var iframeWin = window[layero.find("iframe")[0]["name"]]; //得到iframe页的窗口对象，执行iframe页的方法：iframeWin.method();
                        my.fn = iframeWin; // 把iframe的方法挂载在my.fn上
                        my.obj = $(bodyDom); // 把iframe的DOM挂载在my.obj上
                    } catch (err) {
                    }
                    var that = this;
                    //存储callback事件
                    $(layero).data("callback", that.callback);
                    //$(layero).removeClass("layui-layer-border");
                    layer.setTop(layero);
                    try {
                        var frame = layer.getChildFrame("html", index);
                        var layerfooter = frame.find(".layer-footer");
                        my.layerfooter(layero, index, that);
                        //绑定事件
                        if (layerfooter.length > 0) {
                            // 监听窗口内的元素及属性变化
                            // Firefox和Chrome早期版本中带有前缀
                            var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
                            if (MutationObserver) {
                                // 选择目标节点
                                var target = layerfooter[0];
                                // 创建观察者对象
                                var observer = new MutationObserver(function (mutations) {
                                    my.layerfooter(layero, index, that);
                                    mutations.forEach(function (mutation) {
                                    });
                                });
                                // 配置观察选项:
                                var config = {
                                    attributes: true,
                                    childList: true,
                                    characterData: true,
                                    subtree: true
                                };
                                // 传入目标节点和观察选项
                                observer.observe(target, config);
                                // 随后,你还可以停止观察
                                // observer.disconnect();
                            }
                        }
                    } catch (e) {
                    }
                    if ($(layero).height() > $(window).height()) {
                        //当弹出窗口大于浏览器可视高度时,重定位
                        layer.style(index, {
                            top: 0,
                            height: $(window).height()
                        });
                    }
                },
                end: function (index) {
                    if (typeof (options.closeCallback) === "function") {
                        options.closeCallback();
                    }
                    // 支持用户选择是否刷新
                    if (typeof (close_layer) == "function") {
                        if (close_layer(index) === false) {
                            return false;
                        }
                    }
                    if (options.closeRefresh) {
                        //刷新页面
                        my.load(null, false);
                    }
                }
            }, options ? options : {});
            // if ($(window).width() < 480 || (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream && top.$(".tab-pane.active").length > 0)) {
            //     options.area = [top.$(".tab-pane.active").width() + "px", top.$(".tab-pane.active").height() + "px"];
            //     options.offset = [top.$(".tab-pane.active").scrollTop() + "px", "0px"];
            // }
            if ($(window).width() < 480 || (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream && top.$(".tab-pane.active").length > 0)) {
                var winWidth = my.window.width() - 35;
                var winHeight = my.window.height() * 0.9;
                options.area = [winWidth + "px", winHeight + "px"];
                options.offset = "auto";
            }
            var myLayerLastIndex = layer.open(options);
            my.setData("layerLastIndex", myLayerLastIndex);
            return myLayerLastIndex;
        },
        load: function (url, setpage = true) {
            url = url ? url : window.location.href;
            window.location.href = url;
        },
        //关闭窗口并回传数据
        close: function (data) {
            var index = parent.layer.getFrameIndex(window.name);
            var callback = parent.$("#layui-layer" + index).data("callback");
            //再执行关闭
            parent.layer.close(index);
            //再调用回传函数
            if (typeof callback === "function") {
                callback.call(undefined, data);
            }
        },
        layerfooter: function (layero, index, that) {
            var frame = layer.getChildFrame("html", index);
            var layerfooter = frame.find(".layer-footer");
            if (layerfooter.length > 0) {
                $(".layui-layer-footer", layero).remove();
                var footer = $("<div />").addClass("layui-layer-btn layui-layer-footer");
                footer.html(layerfooter.html());
                if ($(".row", footer).length === 0) {
                    $(">", footer).wrapAll("<div class='row'></div>");
                }
                footer.insertAfter(layero.find(".layui-layer-content"));
                //绑定事件
                footer.on("click", ".btn", function () {
                    if ($(this).hasClass("disabled") || $(this).parent().hasClass("disabled")) {
                        return;
                    }
                    var index = footer.find(".btn").index(this);
                    $(".btn:eq(" + index + ")", layerfooter).trigger("click");
                });
                footer.on("click", "[type=submit]", function () {
                    //方法一 ajax 提交
                    var form = $(".btn:eq(" + $(this).index() + ")", layerfooter)[0].form;
                    $(form).find(":submit").click();
                    //方法二 form 提交
                    // if ($(this).hasClass("disabled") || $(this).parent().hasClass("disabled")) {
                    //     return;
                    // }
                    // $(".btn:eq(" + $(this).index() + ")", layerfooter).trigger("click");
                });
                var titHeight = layero.find(".layui-layer-title").outerHeight() || 0;
                var btnHeight = layero.find(".layui-layer-btn").outerHeight() || 0;
                //重设iframe高度
                $("iframe", layero).height(layero.height() - titHeight - btnHeight);
            }
            //修复iOS下弹出窗口的高度和iOS下iframe无法滚动的BUG
            if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream) {
                var titHeight = layero.find(".layui-layer-title").outerHeight() || 0;
                var btnHeight = layero.find(".layui-layer-btn").outerHeight() || 0;
                $("iframe", layero).parent().css("height", layero.height() - titHeight - btnHeight);
                $("iframe", layero).css("height", "100%");
            }
        },
        inArray: function (str, array) {
            // return array.indexOf(str);
            return array.includes(str);
        },
        /**
         * 正则验证表单 配合my.formValidate使用
         * @Author   ZhaoXianFang
         * @DateTime 2018-10-24
         * @param    {[type]}     reg_exp_str [规则]
         * @param    {[type]}     value       [值]
         * @param    {[type]}     ele         [元素节点]
         * @return   {[type]}                 [description]
         */
        regExpFormValidate: function (value, reg_exp_str, ele = "") {
            if (my.isEmpty(reg_exp_str)) {
                return true;
            }
            var regExp = {
                "email": "^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$",
                "url": "[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(/.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+/.?",
                "mobile": "^[1][3,4,5,7,8,9][0-9]{9}$",
                "id_card": "^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$",
                "id_card_15": "^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}$",
                "strong_pwd_two": "^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-._]).{8,}$", //强密码 (密码中必须包含大小写字母、数字、特称字符，至少8位)
                "strong_pwd": "^(?![a-zA-Z]+$)(?![A-Z0-9]+$)(?![A-Z\\W_!@#$%^&*`~()-+=]+$)(?![a-z0-9]+$)(?![a-z\\W_!@#$%^&*`~()-+=]+$)(?![0-9\\W_!@#$%^&*`~()-+=]+$)[a-zA-Z0-9\\W_!@#$%^&*`~()-+=]{8,}$", //强密码 (至少8位(数字、大写字母、小写字母、特殊字符 至少四选三))
                "password": "(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9]){8,}", //密码 至少8位 必须包含数字和大小写字母
                "date": "^\d{4}-\d{1,2}-\d{1,2}", //日期
                "zh_cn": "^[\u4e00-\u9fa5]{0,}$", //汉字
                "en_num": "^[A-Za-z0-9]+$", //英文和数字
                "en_num_underline": "^[A-Za-z0-9_.\/\]+$", //英文和数字
                "en": "^[A-Za-z]+$", //英文`
                "en_underline": "^[a-zA-Z_]{1,}$", //字母或下划线
                "cn_en_num": "^[\u4E00-\u9FA5A-Za-z0-9]+$", //中文、英文、数字
                "cn_en_num_underline": "^[\u4E00-\u9FA5A-Za-z0-9_]+$", //中文、英文、数字、下划线
                "number": "^[0-9]*$", //数字
                "ip": "^(d{1,2}|1dd|2[0-4]d|25[0-5]).(d{1,2}|1dd|2[0-4]d|25[0-5]).(d{1,2}|1dd|2[0-4]d|25[0-5]).(d{1,2}|1dd|2[0-4]d|25[0-5])$", //IP
            };
            //开始验证
            var checkArr = reg_exp_str.split("|");
            if (my.isEmpty(value)) {
                if ($.inArray("required", checkArr) > - 1) {
                    return false; //必须验证 且不通过
                } else {
                    return true; //为空不验证
                }
            }
            var result = false; //验证结果
            $.each(checkArr, function (i, rule_item) { //遍历二维数组
                if (my.isEmpty(rule_item)) {
                    return true; //跳过这个
                }
                if (typeof (regExp[rule_item]) != "undefined") {
                    //有规则的正则验证
                    var regExpObj = new RegExp(regExp[rule_item]);
                    if (regExpObj.test(value) === false) {
                        result = false;
                        return false; //跳出
                    } else {
                        result = true;
                    }
                } else {
                    if ("required" == rule_item) {
                        result = true;
                        return true;
                    }
                    //验证json格式的数据
                    if ("json" == rule_item) {
                        var temp_json = value.split(/[\s\n]/).join("");
                        if (my.isJsonString(temp_json)) {
                            result = true;
                            return true;
                        } else {
                            result = false;
                            return false; //跳出
                        }
                    }
                    //非常规正则验证
                    var bracket = rule_item.match(/\(([^)]*)\)/); //正则或者小括号里面的规则
                    if ( !bracket) {
                        //不是包含小括号的特殊验证规则
                        result = false;
                        return false;
                    }
                    var rule_type = rule_item.replace(bracket[0], ""); //小括号外的规则
                    var rule_append = bracket[1]; //小括号内的规则
                    switch (rule_type) {
                        case "remote": //调用url验证
                            var check_data = {};
                            check_data[$(ele).attr("name")] = value;
                            //验证时候追加附加数据
                            var add_data = $(ele).data("join");
                            if (add_data) {
                                var tempJson = {};
                                if (my.isJsonString(add_data)) {
                                    //json字符串
                                    tempJson = JSON.parse(add_data);
                                } else {
                                    if (my.isJSON(add_data)) {
                                        tempJson = add_data;
                                    } else {
                                        //方法
                                        try {
                                            tempJson = window[add_data](add_data);
                                        } catch (e) {
                                        }
                                    }
                                }
                                my.mergeJSON(check_data, tempJson);
                            }
                            my.ajax(rule_append, check_data, function (res) {
                                if (typeof res == "string") {
                                    res = JSON.parse(res);
                                }
                                if (res.check == true || res.check == "true") {
                                    //通过
                                    result = true;
                                    return true;
                                } else {
                                    //不通过
                                    layer.msg(res.message);
                                    result = false;
                                    return false;
                                }
                            }, function (err) {
                            });
                            break;
                        case "length":
                            var len_arr = rule_append.split("~");
                            if (len_arr[0]) {
                                if (value.length < len_arr[0]) {
                                    result = false;
                                    return false;
                                }
                            }
                            if (len_arr[1]) {
                                if (value.length > len_arr[1]) {
                                    result = false;
                                    return false;
                                }
                            }
                            break;
                        default:
                    }
                }
            });
            return result;
        },
        /**
         * [getdivform 获取某个div或者form 内的表单]
         * @Author   ZhaoXianFang
         * @DateTime 2018-07-10
         * @param    {[type]}     ele [被操作的form或者div 的class 或者id]
         * @return   {[type]}         [description]
         */
        getdivform: function (ele, return_null = false) {
            var json_data = {};
            $(ele).find("input,textarea,select").each(function (index, elenode) {
                if (return_null) {
                    if ($(elenode).attr("type") == "radio" || $(elenode).attr("type") == "checkbox") {
                        json_data[$(elenode).attr("name")] = $("input[name='" + $(elenode).attr("name") + "']:checked").val();
                    } else {
                        json_data[$(elenode).attr("name")] = $(elenode).val();
                    }
                } else {
                    if ($(elenode).attr("name") !== undefined && !my.isEmpty($(elenode).attr("name")) && !my.isEmpty($(elenode).val())) {
                        if ($(elenode).attr("type") == "radio" || $(elenode).attr("type") == "checkbox") {
                            json_data[$(elenode).attr("name")] = $("input[name='" + $(elenode).attr("name") + "']:checked").val();
                        } else {
                            json_data[$(elenode).attr("name")] = $(elenode).val();
                        }
                    }
                }
                //     return false; //跳出循环
            });
            return json_data;
        },
        /**
         * ajax 请求
         * @Author   ZhaoXianFang
         * @DateTime 2018-06-14
         * @param    {[type]}     url        [请求地址]
         * @param    {[type]}     data       [请求数据]
         * @param    {[type]}     successFun [成功回调]
         * @param    {[type]}     errorFun   [失败回调]
         * @return   {[type]}                [description]
         */
        ajax: function (url, data, successFun, errorFun, reqtype) {
            reqtype = reqtype ? (reqtype.toUpperCase() == "GET" ? "GET" : "POST") : "POST";
            var index = layer.msg("正在处理中……", 0);
            var contentTypeVal = "application/x-www-form-urlencoded"; // 是否包含文件提交
            var processDataVal = true;
            if (data.__proto__.hasOwnProperty("append")) {
                contentTypeVal = false;
                processDataVal = false;
            }
            //设置表单headers字段
            var headerJson = {};
            var hradParam = $("#form_headers_ajax").serializeArray();
            if (hradParam) {
                for (var i = 0; i < hradParam.length; i ++) {
                    headerJson[hradParam[i].name] = hradParam[i].value;
                }
            }
            $.ajax({
                async: true,
                cache: false,
                url: url,
                type: reqtype,
                timeout: 30000, // 设置超时时间30秒
                contentType: contentTypeVal, //禁止false 表示ajax设置编码方式
                processData: processDataVal, //false禁止ajax将数据类型转换为字符串
                headers: headerJson, // 设置 headers
                data: data,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    layer.close(index);
                    /*弹出jqXHR对象的信息*/
                    var errorRes = my.delhtmltag(jqXHR.responseText);
                    try {
                        if (typeof (errorRes) == "string") { // json 解析
                            errorRes = JSON.parse(errorRes);
                        }
                    } catch (err) {
                    }
                    if (typeof errorRes.message != "undefined") {
                        layer.msg(errorRes.message);
                    } else {
                        layer.msg(errorRes);
                    }

                    // errorFun && errorFun(jqXHR, textStatus, errorThrown);
                    errorFun && errorFun(errorRes.message, errorRes);
                },
                success: function (succ) {
                    layer.close(index);
                    //内容,标题
                    if (succ.code > 299) {
                        // var _errMessage = '系统异常:请与管理员联系';
                        // if (!succ.message) {
                        //     layer.msg(_errMessage);
                        // } else {
                        //     _errMessage = succ.message;
                        //     layer.msg(succ.message);
                        // }
                        //errorFun && errorFun(_errMessage);
                    }
                    successFun && successFun(succ);
                }
            });
        },
        /**
         * 去除html 标签
         * @Author   ZhaoXianFang
         * @DateTime 2018-06-19
         * @param    {[type]}     argument [description]
         * @return   {[type]}              [description]
         */
        delhtmltag: function (str) {
            try {
                str = str ? str.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, "") : ""; //忽略大小写的正则
                str = str ? str.replace(/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi, "") : ""; //忽略大小写的正则
                // str =  str ? str.replace(/<\/?.+?>/g, "") : '';
                str = str ? $(str).text() : "";
            } catch (err) {
            }
            return str;
        },
        //判断字符是否为空的方法
        isEmpty: function (obj) {
            if (typeof obj == "undefined" || obj == null || obj == "" || obj.length == 0 || ( !/[^(^\s*)|(\s*$)]/.test(obj)) || (my.getType(obj) == "Object" && Object.keys(obj).length == 0) || (my.getType(obj) == "string" && ["{}", "[]"].includes(obj))) {
                return true;
            } else {
                return false;
            }
        },
        /**
         * 是否为json
         * @Author   ZhaoXianFang
         * @DateTime 2018-05-31
         * @param    {[type]}     target [被检测的对象]
         * @return   {Boolean}           [description]
         */
        isJSON: function (target) {
            return typeof target == "object" && target.constructor == Object;
        },
        /**
         * 是否为json字符串
         * @Author   ZhaoXianFang
         * @DateTime 2019-04-03
         * @param    {[type]}     str [description]
         * @return   {Boolean}        [description]
         */
        isJsonString: function (str) {
            if (typeof str == "string") {
                try {
                    var obj = JSON.parse(str);
                    if (typeof obj == "object" && obj) {
                        return true;
                    }
                } catch (e) {
                }
            }
            return false;
        },
        /**
         * JSON合并
         * @Author   ZhaoXianFang
         * @DateTime 2018-05-31
         * @param    {[type]}     json1
         * @param    {[type]}     json2
         * @param    {[type]}     cover [是否修改 json1 的结构]
         * @return   {[type]}           [description]
         */
        mergeJSON: function (json1, json2, cover = true) {
            if (cover) {
                return $.extend(true, json1, json2);
            } else {
                return $.extend({}, json1, json2);
            }
        },
        // 返回到指定dom节点对象
        backToDom: function (domEle) {
            $("html , body").animate({
                scrollTop: $(domEle).offset().top
            }, 800); // 滚动到元素dom 节点对象
        },
        window: {
            width: function () {
                return $(window).width(); //浏览器时下窗口可视区域宽度
            },
            height: function () {
                return $(window).height(); //浏览器时下窗口可视区域高度
            },
        },
        /**
         * json转url参数 推荐使用
         * @Author   ZhaoXianFang
         * @DateTime 2018-10-25
         * @param    {[type]}     param  [json对象 将要转为URL参数字符串的对象 ]
         * @param    {[type]}     key    [URL参数字符串的前缀 ]
         * @param    {[type]}     encode [true/false 是否进行URL编码,默认为true ]
         * @return   {[type]}            [description]
         * var obj={name:'tom','class':{className:'class1'},classMates:[{name:'lily'}]};
         * console.log(my.jsonToUrl(obj));  //output: &name=tom&class.className=class1&classMates[0].name=lily
         * console.log(my.jsonToUrl(obj,'stu'));  //output: &stu.name=tom&stu.class.className=class1&stu.classMates[0].name=lily
         */
        jsonToUrl: function (param, key, encode) {
            if (param == null) return "";
            var paramStr = "";
            var t = typeof (param);
            if (t == "string" || t == "number" || t == "boolean") {
                paramStr += key + "=" + ((encode == null || encode) ? encodeURIComponent(param) : param);
            } else {
                for (var i in param) {
                    var k = key == null ? i : key + (param instanceof Array ? "[" + i + "]" : "." + i);
                    paramStr += ((paramStr == "") ? "?" : "&") + my.jsonToUrl(param[i], k, encode);
                }
            }
            return paramStr;
        },
        //获取url中的参数
        urlQuery: function (name, location, get_last) {
            get_last = get_last ? get_last : true; //是否获取地址里面最后一次出现的参数值
            var url = location ? location : window.location.href;
            var splitIndex = url.indexOf("?") + 1;
            var paramStr = url.substr(splitIndex, url.length);
            var arr = paramStr.split("&");
            var lastVal; //最后一次出现的值 在 get_last 为 true 时候生效
            var allParamData = {};
            for (var i = 0; i < arr.length; i ++) {
                var kv = arr[i].split("=");
                if (name) {
                    if (kv[0] == name) {
                        if (get_last) {
                            lastVal = kv[1];
                        } else {
                            return kv[1];
                        }
                    }
                } else {
                    if (lastVal == undefined) {
                        lastVal = {};
                    }
                    //所有
                    lastVal[kv[0]] = kv[1];
                }
            }
            return lastVal;
        },
        // 设置路由参数
        setUrlParam: function (name, value, location) {
            var url = location ? location : window.location.href;
            var splitIndex = url.indexOf("?") + 1;
            var paramStr = url.substr(splitIndex, url.length);
            var url_param = {}; //用来存储 url 参数 用于去重
            var newUrl = url.substr(0, splitIndex);
            // - if exist , replace
            var arr = paramStr.split("&");
            for (var i = 0; i < arr.length; i ++) {
                var kv = arr[i].split("=");
                if (kv[0] == name) {
                    //记录下 参数
                    if (url_param[name] == undefined) {
                        url_param[name] = value;
                        newUrl += kv[0] + "=" + value;
                    }
                } else {
                    if (kv[1] != undefined) {
                        newUrl += kv[0] + "=" + kv[1];
                    }
                }
                if (i != arr.length - 1) {
                    newUrl += "&";
                }
            }
            // - if new, add
            if (newUrl.indexOf(name) < 0) {
                newUrl += splitIndex == 0 ? "?" + name + "=" + value : "&" + name + "=" + value;
            }
            // return newUrl;
            return ((newUrl == name + "=") || (newUrl == "?" + name + "=") || (newUrl == "&" + name + "=")) ? url : newUrl;
        },
        //返回到顶部
        backToTop: function (elementName, toTopVal) {
            try {
                if (typeof elementName == "undefined") {
                    $("body").append("<div class=\"my-backtotop\">&#8679;</div>");
                    elementName = $(".my-backtotop");
                    $(elementName).css({
                        "position": "fixed",
                        "bottom": "40px",
                        "right": "40px",
                        "z-index": "9999",
                        "width": "33px",
                        "height": "33px",
                        "text-align": "center",
                        "text-decoration": "none",
                        "line-height": "32px",
                        "background-color": "rgba(0, 0, 0, 0.75)",
                        "font-size": "21px",
                        "color": "#00abff",
                        "border-radius": "50%",
                        "opacity": 0.4
                    });
                    $(elementName).hover(function () {
                        $(this).css({
                            opacity: 0.8
                        });
                    });
                }

                function Debouncer(callback) {
                    this.callback = callback;
                    this.ticking = false;
                }

                Debouncer.prototype = {
                    update: function () {
                        this.callback && this.callback();
                        this.ticking = false;
                    },
                    requestTick: function () {
                        if ( !this.ticking) {
                            requestAnimationFrame(this.rafCallback || (this.rafCallback = $.proxy(this.update, this)));
                        }
                        this.ticking = true;
                    },
                    handleEvent: function () {
                        this.requestTick();
                    }
                };
                var $el = $(elementName),
                    offset = 100;
                var debouncer = new Debouncer(function () {
                    if ($(window).scrollTop() > offset) {
                        $el.fadeIn();
                    } else {
                        $el.fadeOut();
                    }
                });
                window.addEventListener("scroll", debouncer, false);
                debouncer.handleEvent();
                $el.on("click", function () {
                    $("html, body").animate({
                        scrollTop: 0
                    }, 700);
                });
            } catch (err) {
                console.log("err", err);
            }
        },
        /**
         * [debounce 请求函数防抖]
         * 在事件被触发n秒后再执行回调函数，如果在这n秒内又被触发，则重新计时。
         * @Author   ZhaoXianFang
         * @DateTime 2020-07-06
         * @param    {[type]}     fun   [触发的函数]
         * @param    {[type]}     delay [延时时间]
         * demo : 1、my.debounce(ajax, 500)(param)
         *        2、let debounceReq = my.debounce(ajax, 500);   debounceReq(param);
         * @return   {[type]}           [description]
         */
        debounce: function (fun, delay = 500) {
            return function (args) {
                //获取函数的作用域和变量
                let that = this;
                let _args = args;
                //每次事件被触发，都会清除当前的timeer，然后重写设置超时调用
                clearTimeout(fun.id);
                fun.id = setTimeout(function () {
                    fun.call(that, _args);
                }, delay);
            };
        },
        /**
         * [throttle 请求函数节流]
         * 规定一个单位时间，在这个单位时间内，只能有一次触发事件的回调函数执行，如果在同一个单位时间内某事件被触发多次，只有一次能生效。
         * @Author   ZhaoXianFang
         * @DateTime 2020-07-06
         * @param    {[type]}     fun   [触发的函数]
         * @param    {[type]}     delay [延时时间]
         * demo : 1、my.throttle(ajax, 500)(param)
         *        2、let throttleReq = my.throttle(ajax, 500);   throttleReq(param);
         * @return   {[type]}           [description]
         */
        throttle: function (fun, delay = 1000) {
            let last, deferTimer;
            return function (args) {
                let that = this;
                let _args = arguments;
                let now = + new Date();
                if (last && now < last + delay) {
                    clearTimeout(deferTimer);
                    deferTimer = setTimeout(function () {
                        last = now;
                        fun.apply(that, _args);
                    }, delay);
                } else {
                    last = now;
                    fun.apply(that, _args);
                }
            };
        },
        /**
         * 对url 进行编码与解码
         * @Author   ZhaoXianFang
         * @DateTime 2018-07-09
         * @param    {[type]}     url_str [需要操作的url 字符串]
         * @param    {String}     type    [en:编码；de:解码]
         * @return   {[type]}             [description]
         */
        urlEnCode: function (url_str, type = "de") {
            if ( !url_str) {
                return "";
            }
            if (type && type.toUpperCase() == "DE") {
                //解码
                return decodeURIComponent(url_str);
            } else {
                //编码
                return encodeURIComponent(url_str);
            }
        },
        /**
         * 实体转字符串
         * @Author   ZhaoXianFang
         * @DateTime 2018-12-17
         * @param    {[type]}     str [description]
         * @return   {[type]}         [description]
         */
        escape2Html: function (str) {
            if (my.isEmpty(str)) {
                return str;
            }
            var arrEntities = {
                "lt": "<",
                "gt": ">",
                "nbsp": " ",
                "amp": "&",
                "quot": "\""
            };
            return str.replace(/&(lt|gt|nbsp|amp|quot);/ig, function (all, t) {
                return arrEntities[t];
            });
        },
        //笔记
        noticeTool: function (elementName) {
            try {
                if (typeof elementName == "undefined") {
                    $("body").append("<div class=\"my-notice-tool\">笔</div>");
                    elementName = $(".my-notice-tool");
                    $(elementName).css({
                        "position": "fixed",
                        "bottom": "80px",
                        "right": "40px",
                        "z-index": "9999",
                        "width": "33px",
                        "height": "33px",
                        "text-align": "center",
                        "text-decoration": "none",
                        "line-height": "32px",
                        "background-color": "rgba(0, 0, 0, 0.75)",
                        "font-size": "21px",
                        "color": "#00abff",
                        "border-radius": "50%",
                        "display": "black",
                        "opacity": 0.8
                    });
                }
                var $el = $(elementName);
                $el.on("click", function () {
                    let display = $(".my-notice-tool-content").css("display");
                    if (display == "none") {
                        $(".my-notice-tool-content").show();
                    } else {
                        $(".my-notice-tool-content").hide();
                    }
                });
                // ===========================================
                $("body").append("<div class=\"my-notice-tool-content\"><textarea name=\"\" class=\"my-notice-tool-textarea\" rows=\"3\" cols=\"20\" placeholder=\"小小笔记...\"></textarea></div>");
                $(".my-notice-tool-content").append("<style>.my-notice-tool-textarea{width:270px;height:95px;resize: none;padding:8px;font-size: 12px;}</style>");
                let toolName = $(".my-notice-tool-content");
                $(toolName).css({
                    "position": "fixed",
                    "bottom": "80px",
                    "right": "80px",
                    "z-index": "9999",
                    "background": "#449d44",
                    "display": "none",
                    "border": " 2px solid #449d44"
                });
                $(".my-notice-tool-content").append("<style>.my-notice-tool-content::after{}</style>");
                // 监听数据
                $(".my-notice-tool-textarea").bind("input propertychange", function () {
                    let noticeToolVal = $(".my-notice-tool-textarea").val();
                    if (noticeToolVal.length > 0) {
                        sessionStorage.setItem("my_notice_tool_textarea_value", noticeToolVal);
                    }
                });
                $(".my-notice-tool-textarea").val(sessionStorage.getItem("my_notice_tool_textarea_value"));
            } catch (err) {
                console.log("err", err);
            }
        },
        /**
         * 设置 title 的 tips 气泡
         * @Author   ZhaoXianFang
         * @DateTime 2019-10-25
         * my.tips()
         * 使用 : 在需要的地方设置 data-tips 属性 支持 top|上, bottom|下, left|左, right|右,track|跟随 默认为跟随
         * demo: data-tips | data-tips="top" |  data-tips="bottom" | data-tips="left" | data-tips="right" | data-tips="track"
         */
        tips: function () {
            var tipElement = null;
            $("[data-tips]").unbind();
            // 如果鼠标悬停时候一直在 hover 和移除 hover 事件上徘徊，请检查你元素 的padding 或者 margin 样式
            $("[data-tips]").hover(function () {
                $(this).attr("title", "");
                tipElement = $("#mytip");
                // 创建显示对象
                if ($(tipElement).length < 1) {
                    tipElement = $("<div id=\"mytip\"></div>");
                    $("body").append(tipElement);
                }
                // 允许的类型
                let allowType = ["top", "bottom", "left", "right", "track"];
                // 获取内容
                let tip = $(this).data("tips");
                let tip_type = $(this)["0"].dataset.tips;
                if (tip == "") return;
                if (tip.indexOf("#") === 0) {
                    tip = $(tip).html();
                } else {
                    tip = tip.replace(/\\n/, "<br>");
                    tip = tip.replace(/\n/, "<br>");
                }
                tipElement.html(tip);
                var arrowClass = allowType.indexOf(tip_type) != - 1 ? tip_type : "track";
                // 是否鼠标跟随
                if (arrowClass == "track") {
                    tipElement.addClass("track");
                } else {
                    tipElement.removeClass("track");
                    tipElement.removeClass("top").removeClass("bottom").removeClass("left").removeClass("right");
                    tipElement.addClass(arrowClass);
                    var top = 0,
                        left = 0;
                    if (arrowClass == "top") {
                        top = $(this).offset().top - tipElement.outerHeight() - 5;
                        left = $(this).offset().left + $(this).width() / 2 - tipElement.outerWidth() / 2;
                    } else if (arrowClass == "bottom") {
                        top = $(this).offset().top + $(this).height() + 5;
                        left = $(this).offset().left + $(this).width() / 2 - tipElement.outerWidth() / 2;
                    } else if (arrowClass == "left") {
                        top = $(this).offset().top + $(this).height() / 2 - tipElement.outerHeight() / 2;
                        left = $(this).offset().left - tipElement.outerWidth() - 5;
                    } else if (arrowClass == "right") {
                        top = $(this).offset().top + $(this).height() / 2 - tipElement.outerHeight() / 2;
                        left = $(this).offset().left + $(this).width() + 5;
                    }
                    tipElement.css({
                        "top": Math.round(top) + "px",
                        "left": Math.round(left) + "px",
                    });
                    tipElement.stop(false, true).show(500);
                }
            }, function () {
                // 隐藏
                tipElement && tipElement.stop(false, true).hide(300);
            }).mousemove(function (e) {
                // 跟随鼠标移动
                if (typeof tipElement.hasClass == undefined || !tipElement.hasClass("track")) return;
                e = e || window.event;
                var x = e.pageX || e.clientX + document.body.scroolLeft;
                var y = e.pageY || e.clientY + document.body.scrollTop;
                var top = y + 10;
                var left = x + 5;
                tipElement.css("top", top + "px");
                tipElement.css("left", left + "px");
                tipElement.stop(false, true).show(500);
            }).each(function () {
                // 获取显示内容，并移除title
                $(this).data("tips", $(this).attr("title"));
                // $(this).attr('title', '');
            });
        },
        /**
         * 阻止 a 链接的默认跳转，使用ajax 代替
         * @Author    ZhaoXianFang
         * @DateTime  2020-08-16
         * @copyright [copyright]
         * @license   [license]
         * @version   [version]
         * @return    {[type]}     [description]
         * @demo      <a ></a>
         */
        stop_jump: function () {
            //编辑文章时阻止a标签跳转
            $("a.stop_jump").click(function (e) {
                //如果提供了事件对象，则这是一个非IE浏览器
                if (e && e.preventDefault) {
                    //阻止默认浏览器动作(W3C)
                    e.preventDefault();
                } else {
                    //IE中阻止函数器默认动作的方式
                    window.event.returnValue = false;
                }
                // console.log('阻止跳转',$(this).attr('href'))
                var href = $(this).attr("href");
                if (href == "#" || href == "javascript:;") {
                    return false;
                }
                my.ajax(href, {}, function (res) {
                    if (typeof res == "string") {
                        res = JSON.parse(res);
                    }
                    // console.log(res)
                    if (res.code == 200 || res.code == 0) {
                        layer.msg(res.message);
                    } else {
                        layer.msg(res.message);
                    }
                    res.wait = res.wait ? res.wait : 3;
                    if (typeof (res.url) != "undefined") {
                        setInterval(function () {
                            //跳转
                            window.location.href = res.url;
                        }, res.wait * 1000);
                    }
                }, function (err) {
                });
                return false;
            });
        },
        /**
         * 上传图片前进行预览
         * multiple: 是否是多文件上传 默认否 true|false
         * 调用: my.multiple_img_preview('type=file的元素','预览box的元素','是否多选','自定义上传回调')
         * 调用: my.multiple_img_preview('#file','#preview',true,function(file) { ... })
         * demo : <input multiple="" class="file_input" id='file' type="file" data-name='row[img][]' data-value-field='id' />
         *        <div class="preview" id="preview"></div>
         * 特别说明：如果设置了 回调方法 callback,需要 异步返回 {is_done:true|false【是否处理完成】 ,url:'','msg':'提示信息'}，返回格式为 { is_done:true, url:res.data.data.pre_url, msg:'上传成功', name:file.name,'和 data-value-field 定义的字段' }
         * 设置input 的 data-name 属性
         */
        multiple_img_preview: function (inputELe, previewBoxEle, multiple = false, callback) {
            let inputFile = document.querySelector(inputELe),
                wrapper = document.querySelector(previewBoxEle);
            var inputName = $(inputFile).data("name") || "file[]";
            // 用来自定义 上传的form 表单中 input 里面 value 使用的 回调字段名称,没有定义时候 使用 预览图片的 url 字段，该字段仅适用于 有callback 回调时候使用
            var inputValueField = $(inputFile).data("value-field") || "url";
            console.log("inputValueField", inputValueField);
            // 添加预览样式
            var $previewStyle = $("<style type=\"text/css\">.my-preview-waterfall{width:150px;float:left;position:relative;padding:.3em;margin:0 .125em 1em;-moz-page-break-inside:avoid;-webkit-column-break-inside:avoid;break-inside:avoid;background:white;-moz-box-shadow:0 1px 3px 0 rgba(0,0,0,0.12),0 1px 2px 0 rgba(0,0,0,0.24);-webkit-box-shadow:0 1px 3px 0 rgba(0,0,0,0.12),0 1px 2px 0 rgba(0,0,0,0.24);box-shadow:0 1px 3px 0 rgba(0,0,0,0.12),0 1px 2px 0 rgba(0,0,0,0.24)}.my-preview-waterfall img{width:100%;height:100px!important;padding-bottom:.3em;border-bottom:1px solid #ccc}.my-preview-waterfall .my-file-name{font-size:10px;overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical}.my-preview-waterfall .my-close{font-size:14px;top:8px;position:absolute;right:8px;background:red;color:#fff;padding:5px;border-radius:3px;line-height:10px;cursor:pointer}</style>");
            $($("head")[0]).append($previewStyle);
            var preview = function (files, use_callback = false) {
                if (use_callback == true) {
                    // console.log('000111',files,files[inputValueField])
                    // 设置回调时候是单个文件传入
                    var imgStr = "<div class=\"my-preview-waterfall\"><div class=\"my-close\">删除</div><img src=\"" + files["url"] + "\" alt=\"\"><span class=\"my-file-name\" title=\"" + files.name + "\">" + files.name + "</span><input type=\"text\" name=\"" + inputName + "\" value=\"" + files[inputValueField] + "\" style=\"display:none;\" /></div>";
                    $(wrapper).append(imgStr);
                    $(".my-close").click(function () {
                        $(this).parent().remove();
                    });
                } else {
                    Object.keys(files).forEach(function (file) {
                        let fileReader = new FileReader();
                        fileReader.readAsDataURL(files[file]);
                        fileReader.onload = function (evt) {
                            var objUrl = null;
                            // 下面函数执行的效果是一样的，只是需要针对不同的浏览器执行不同的 js 函数而已
                            if (window.createObjectURL != undefined) { // basic
                                objUrl = window.createObjectURL(files[file]);
                            } else if (window.URL != undefined) { // mozilla(firefox)
                                objUrl = window.URL.createObjectURL(files[file]);
                            } else if (window.webkitURL != undefined) { // webkit or chrome
                                objUrl = window.webkitURL.createObjectURL(files[file]);
                            }
                            var imgStr = "<div class=\"my-preview-waterfall\"><div class=\"my-close\">删除</div><img src=\"" + objUrl + "\" alt=\"\"><span class=\"my-file-name\" title=\"" + files[file].name + "\">" + files[file].name + "</span><input type=\"text\" name=\"" + inputName + "\" value=\"" + evt.target.result + "\" style=\"display:none;\" /></div>";
                            $(wrapper).append(imgStr);
                            $(".my-close").click(function () {
                                $(this).parent().remove();
                            });
                        };
                    });
                }
            };
            $(inputELe).change(function (e) {
                // console.log($(inputELe).attr('multiple'))
                let multiple = $(inputELe).attr("multiple");
                // console.log(e)
                // console.log($(this))
                let files = e.target.files;
                if ( !multiple) {
                    // 单图模式
                    $(wrapper).empty();
                }
                // console.log('multiple', multiple)
                // console.log('typeof callback', typeof callback)
                if (typeof callback == "function") {
                    Object.keys(files).forEach(function (file) {
                        callback(files[file]).then(res => {
                            // console.log('my 回调',res)
                            if (res.is_done === true) {
                                // 上传成功，加载预览 图
                                res["name"] = my.isEmpty(res["name"]) ? (files[file].name || "未命名") : res["name"];
                                preview(res, true);
                            } else {
                                layer.msg(files[file].name + ":" + (my.isEmpty(res["msg"]) ? "上传失败" : res["msg"]));
                            }
                        }).catch(error => {
                            layer.msg(files[file].name + " 上传失败");
                            console.log("error", error);
                        });
                    });
                } else {
                    // 未设置自定义上传操作，直接进入 预览
                    preview(files);
                }
                // console.log(files)
                // my.isEmpty(callback) || preview(files)
                // $(inputFile).val("");
                // var fileTemp = $(inputELe)
                // fileTemp.after(fileTemp.clone().val(""));
                // fileTemp.remove();
            });
            $(".my-close").click(function () {
                $(this).parent().remove();
            });
            // inputFile.onchange = function (e) {
            //     let files = e.target.files;
            //     if(!multiple){
            //         // 单图模式
            //         $(wrapper).empty()
            //     }
            //     console.log('multiple',multiple)
            //     // console.log(files)
            //     preview(files)
            //     // $(inputFile).val("");
            //     var fileTemp = $(inputELe)
            //     fileTemp.after(fileTemp.clone().val(""));
            //     fileTemp.remove();
            // };
        },
        /**
         * 监听input[type=file]元素 选择文件后的本地可预览地址
         * @param inputFileEle 被监听对象元素的 class或者id
         * @param successFun 如果监听到文件以后返回 src:本地可预览地址、ele 被监听元素对象
         *
         * @demo my.listenFileChangeURL('#app_cover',function (src,ele) {
         *      console.log(src,ele)
         *  });
         */
        listenFileChangeURL: function (inputFileEle, successFun) {
            $(inputFileEle).bind("input propertychange", function (ele) {
                var url = window.URL || window.webkitURL || window.mozURL;
                var file = ele.target.files[0];
                var file_src = url.length > 0 ? url.createObjectURL(file) : ($(inputFileEle).val() || ele.target.result);
                successFun && successFun(file_src, ele.target);
            });
        },
        /**
         * 获取对象的长度
         * @param args
         * @returns
         */
        getObjLength: function (obj) {
            if (my.isJson(obj)) {
                return Object.keys(obj).length;
            }
            switch (my.getType(obj)) {
                case "Array":
                    return obj["length"];
                case "Object":
                    return Object.keys(obj).length;
                default:
                    return 0;
            }
        },
        /**
         * 判断一个对象的类型
         * @param args
         * @returns
         */
        getType: function (data) {
            let type = typeof data;
            if (type !== "object") {
                return type;
            }
            return Object.prototype.toString.call(data).replace(/^\[object (\S+)\]$/, "$1");
        },
        // 关闭 flavr 弹出层
        closeFlavr: function (flavrEle) {
            try {
                flavrEle.close();
            } catch (err) {
            }
        },
        // 关闭所有 flavr 弹出层
        closeAllFlavr: function () {
            $("body").find(".flavr-container.shown").remove();
        },
        /**
         * 时间 转10位时间戳
         * @param dateString 例如 2020-01-01
         */
        timestamp: function (dateString = "") {
            const time = dateString ? parseInt((new Date(dateString)).getTime().toString().substr(0, 10)) : parseInt((new Date()).getTime().toString().substr(0, 10));
            return time;
        },
        /**
         * 时间格式化 10位时间戳 转时间格式 Y-m-d H:i
         * 10位的 时间戳转时间格式
         */
        timestampToDate: function (timestamp, format = "y-m-d") {
            var date_str = timestamp ? parseInt(timestamp.toString().substr(0, 10)) : parseInt((new Date()).getTime().toString().substr(0, 10));
            var date = new Date(date_str * 1 * 1000);
            format = format ? format : "y-m-d";
            var year = date.getFullYear().toString();
            var month = date.getMonth() * 1 + 1;
            var day = date.getDate();
            var hour = date.getHours();
            var minute = date.getMinutes();
            var second = date.getSeconds();
            var resutl_time = format;
            if (format.indexOf("y") > - 1) {
                resutl_time = resutl_time.replace("y", year);
            }
            if (format.indexOf("m") > - 1) {
                resutl_time = resutl_time.replace("m", (month > 9 ? month : "0" + month).toString());
            }
            if (format.indexOf("d") > - 1) {
                resutl_time = resutl_time.replace("d", (day > 9 ? day : "0" + day).toString());
            }
            if (format.indexOf("h") > - 1) {
                resutl_time = resutl_time.replace("h", (hour > 9 ? hour : "0" + hour).toString());
            }
            if (format.indexOf("i") > - 1) {
                resutl_time = resutl_time.replace("i", (minute > 9 ? minute : "0" + minute).toString());
            }
            if (format.indexOf("s") > - 1) {
                resutl_time = resutl_time.replace("s", (second > 9 ? second : "0" + second).toString());
            }
            return resutl_time;
        },
        //打开全屏方法
        openFullscreen: function (element) {
            try {
                if (element.requestFullscreen) {
                    element.requestFullscreen();
                } else if (element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                } else if (element.msRequestFullscreen) {
                    element.msRequestFullscreen();
                } else if (element.webkitRequestFullscreen) {
                    element.webkitRequestFullScreen();
                }
            } catch (error) {
            }
        },
        //退出全屏方法
        exitFullScreen: function () {
            try {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExiFullscreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            } catch (error) {
            }
        },
        //dataURL 图片 转file 文件流
        dataURLtoFile: function (dataurl, filename) {
            var arr = dataurl.split(","),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]),
                n = bstr.length,
                u8arr = new Uint8Array(n);
            while (n --) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, {
                type: mime
            });
        },
        // 获取指定锚点的值
        getAnchorPoint: function (name) {
            var getTab = window.location.hash;
            var args = getTab.substring(1).split("&");
            for (var i = 0, len = args.length; i < len; i ++) {
                var hashItem = args[i];
                var item = hashItem.split("=");
                if (item["0"] == name) {
                    return item["1"] || "";
                }
            }
            return "";
        },
        /**
         * json 字符串格式化
         * @Author   zhaoXianFang
         * @DateTime 2021-12-31
         * @param    {[type]}     JSONstr [未格式化的JSON字符串]
         * @return   {[type]}             [description]
         */
        formatJson: function (jsonObj) {
            // 正则表达式匹配规则变量
            var reg = null;
            // 转换后的字符串变量
            var formatted = "";
            // 换行缩进位数
            var pad = 0;
            // 一个tab对应空格位数
            var PADDING = "    ";
            // json对象转换为字符串变量
            // 转换后的jsonObj受体对象
            var jsonString = null;
            // 判断传入的jsonObj对象是不是字符串，如果是字符串需要先转换为对象，再转换为字符串，这样做是为了保证转换后的字符串为双引号
            if (Object.prototype.toString.call(jsonObj) !== "[object String]") {
                try {
                    jsonString = JSON.stringify(jsonObj);
                } catch (error) {
                    // 转换失败错误信息
                    return "json数据格式有误:" + error;
                }
            } else {
                try {
                    jsonObj = jsonObj.replace(/(\')/g, "\"");
                    jsonString = JSON.stringify(JSON.parse(jsonObj));
                } catch (error) {
                    // 转换失败错误信息
                    return "json数据格式有误:" + error;
                }
            }
            if ( !jsonString) {
                return jsonString;
            }
            // 存储需要特殊处理的字符串段
            var _index = [];
            // 存储需要特殊处理的“再数组中的开始位置变量索引
            var _indexStart = null;
            // 存储需要特殊处理的“再数组中的结束位置变量索引
            var _indexEnd = null;
            // 将jsonString字符串内容通过\r\n符分割成数组
            var jsonArray = [];
            // 正则匹配到{,}符号则在两边添加回车换行
            jsonString = jsonString.replace(/([\{\}])/g, "\r\n$1\r\n");
            // 正则匹配到[,]符号则在两边添加回车换行
            jsonString = jsonString.replace(/([\[\]])/g, "\r\n$1\r\n");
            // 正则匹配到,符号则在两边添加回车换行
            jsonString = jsonString.replace(/(\,)/g, "$1\r\n");
            // 正则匹配到要超过一行的换行需要改为一行
            jsonString = jsonString.replace(/(\r\n\r\n)/g, "\r\n");
            // 正则匹配到单独处于一行的,符号时需要去掉换行，将,置于同行
            jsonString = jsonString.replace(/\r\n\,/g, ",");
            // 特殊处理双引号中的内容
            jsonArray = jsonString.split("\r\n");
            jsonArray.forEach(function (node, index) {
                // 获取当前字符串段中"的数量
                var num = node.match(/\"/g) ? node.match(/\"/g).length : 0;
                // 判断num是否为奇数来确定是否需要特殊处理
                if (num % 2 && !_indexStart) {
                    _indexStart = index;
                }
                if (num % 2 && _indexStart && _indexStart != index) {
                    _indexEnd = index;
                }
                // 将需要特殊处理的字符串段的其实位置和结束位置信息存入，并对应重置开始时和结束变量
                if (_indexStart && _indexEnd) {
                    _index.push({
                        start: _indexStart,
                        end: _indexEnd
                    });
                    _indexStart = null;
                    _indexEnd = null;
                }
            });
            // 开始处理双引号中的内容，将多余的"去除
            _index.reverse().forEach(function (item, index) {
                var newArray = jsonArray.slice(item.start, item.end + 1);
                jsonArray.splice(item.start, item.end + 1 - item.start, newArray.join(""));
            });
            // 奖处理后的数组通过\r\n连接符重组为字符串
            jsonString = jsonArray.join("\r\n");
            // 将匹配到:后为回车换行加大括号替换为冒号加大括号
            jsonString = jsonString.replace(/\:\r\n\{/g, ":{");
            // 将匹配到:后为回车换行加中括号替换为冒号加中括号
            jsonString = jsonString.replace(/\:\r\n\[/g, ":[");
            // 将上述转换后的字符串再次以\r\n分割成数组
            jsonArray = jsonString.split("\r\n");
            // 将转换完成的字符串根据PADDING值来组合成最终的形态
            jsonArray.forEach(function (item, index) {
                var i = 0;
                // 表示缩进的位数，以tab作为计数单位
                var indent = 0;
                // 表示缩进的位数，以空格作为计数单位
                var padding = "";
                if (item.match(/\{$/) || item.match(/\[$/)) {
                    // 匹配到以{和[结尾的时候indent加1
                    indent += 1;
                } else if (item.match(/\}$/) || item.match(/\]$/) || item.match(/\},$/) || item.match(/\],$/)) {
                    // 匹配到以}和]结尾的时候indent减1
                    if (pad !== 0) {
                        pad -= 1;
                    }
                } else {
                    indent = 0;
                }
                for (i = 0; i < pad; i ++) {
                    padding += PADDING;
                }
                formatted += padding + item + "\r\n";
                pad += indent;
            });
            // 返回的数据需要去除两边的空格
            return formatted.trim();
        },
        // 将 url 转换成 json对象
        urlToObject: function urlToObject(url) {
            if (my.isEmpty(url)) return {};
            var urlObject = {};
            var urlString;
            var urlArray;
            if (/\?/.test(url)) {
                urlString = url.substring(url.indexOf("?") + 1);
            }
            try {
                urlArray = urlString.split("&");
            } catch (error) {
                urlArray = new Array(url);
            }
            for (var i = 0, len = urlArray.length; i < len; i ++) {
                var urlItem = urlArray[i];
                var item = urlItem.split("=");
                urlObject[item[0]] = item[1];
            }
            return urlObject;
        },
        // 将json对象转url
        objectToUrl: function (param, key) {
            var paramStr = "";
            if (param instanceof String || param instanceof Number || param instanceof Boolean) {
                paramStr += "&" + key + "=" + encodeURIComponent(param);
            } else {
                $.each(param, function (i) {
                    var k = key == null ? i : key + (param instanceof Array ? "[" + i + "]" : "." + i);
                    paramStr += "&" + parseParam(this, k);
                });
            }
            return paramStr.substr(1);
        },
        /**
         * js 休眠函数
         *  @param timerOrFun 毫秒(number)[休眠时间：定时多少毫秒后结束] 或者 回调函数（fun）【仅当此回调函数返回true时才结束】
         * @param timeoutTimer 如果第一个参数是回调函数时，如果过了timeoutTimer 秒都没有返回true则timeoutTimer后结束休眠
         * @returns {boolean}
         */
        sleep: function (timerOrFun, timeoutTimer = 10) {
            var start = new Date().getTime();
            while (true) {
                if (typeof (timerOrFun) == "function") {
                    if (timerOrFun() === true) {
                        break;
                    }
                    // 如果 timeoutTimer 秒后未返回则直接结束
                    if (new Date().getTime() - start > timeoutTimer * 1000) {
                        break;
                    }
                }
                if (typeof (timerOrFun) == "number") {
                    if (new Date().getTime() - start > timerOrFun) {
                        break;
                    }
                }
            }
            return true;
        },
        // 简易版深度克隆
        clone: function (data) {
            var obj;
            if (Object.prototype.toString.call(data) === "[object Array]") {
                obj = [];
            } else if (Object.prototype.toString.call(data) === "[object Object]") {
                obj = {};
            } else {
                // 不再具有下一层次
                return data;
            }
            if (Object.prototype.toString.call(data) === "[object Array]") {
                for (var i = 0, len = data.length; i < len; i ++) {
                    obj.push(my.clone(data[i]));
                }
            } else if (Object.prototype.toString.call(data) === "[object Object]") {
                for (var key in data) {
                    obj[key] = my.clone(data[key]);
                }
            }
            return obj;
        },
        /**
         * 监听数据变化并回调
         */
        observer: function (obj, callback) {
            var args = arguments;
            if (typeof (callback) != "function") {
                throw new Error("最后一个参数应该是回调函数，用于返回监听数据变化");
            }
            // 超过2个参数说明是子调用，子调用里面就不调用 callback
            var can_call_callback = args.length <= 2;

            if ( !["[object Object]", "[object Array]"].includes(Object.prototype.toString.call(obj))) {
                var lisenObj = {};//变量数据存储
                // 监听某个变量变化
                Object.defineProperty(window, [obj], {
                    set: function (val) {
                        let _old_data = lisenObj[obj] || undefined;
                        lisenObj[obj] = val;
                        can_call_callback && callback && callback(val, _old_data, obj);
                    },
                    get: function () {
                        return lisenObj[obj];
                    }
                });
            } else {
                // 处理数组
                function overrideArrayProto(array, callback) {
                    // 保存原始 Array 原型
                    var originalProto = Array.prototype, // 通过 Object.create 方法创建一个对象，该对象的原型是Array.prototype
                        overrideProto = Object.create(Array.prototype), result;
                    // 遍历要重写的数组方法
                    ["push", "pop", "shift", "unshift", "short", "reverse", "splice"].forEach((method) => {
                            Object.defineProperty(overrideProto, method, {
                                value: function () {
                                    var oldVal = this.slice();
                                    //调用原始原型上的方法
                                    result = originalProto[method].apply(this, arguments);
                                    //继续监听新数组
                                    my.observer(this, callback);
                                    callback && callback(this, oldVal, callback);
                                    return result;
                                }
                            });
                        }
                    );
                    // 最后 让该数组实例的 __proto__ 属性指向 假的原型 overrideProto
                    array.__proto__ = overrideProto;
                }

                // 判断两个对象的值是否相等
                function isObjectValueEqual(a, b) {
                    var aProps = Object.getOwnPropertyNames(a);
                    var bProps = Object.getOwnPropertyNames(b);
                    if (aProps.length !== bProps.length) {
                        return false;
                    }
                    for (var i = 0; i < aProps.length; i ++) {
                        var propName = aProps[i], propA = a[propName], propB = b[propName];
                        if ( !b.hasOwnProperty(propName)) return false;
                        if ((propA instanceof Object)) {
                            if ( !isObjectValueEqual(propA, propB)) {
                                return false;
                            }
                        } else if (propA !== propB) {
                            return false;
                        }
                    }
                    return true;
                }

                if (Object.prototype.toString.call(obj) === "[object Array]") {
                    overrideArrayProto(obj);
                }
                // var oldObj = Object.assign({}, obj);
                var oldObj = my.clone(obj);

                // 监听对象变化
                Object.keys(obj).forEach((key) => {
                    let oldVal = obj[key];
                    Object.defineProperty(obj, key, {
                        // writable: true, // 是否可以修改属性的值
                        configurable: true, //配置项(writable、enumerable)是否可以修改
                        enumerable: true,// 是否可以枚举
                        get() {
                            return oldVal;
                        },
                        set(val) {
                            if (Object.prototype.toString.call(val) === "[object Object]") {
                                my.observer(val, callback, true);
                            }
                            oldObj[key] = oldVal;
                            oldVal = val;
                            can_call_callback && callback && callback(obj, oldObj);
                        }
                    });
                    if (["[object Object]", "[object Array]"].includes(Object.prototype.toString.call(oldVal))) {
                        my.observer(oldVal, callback, true);
                        if ( !isObjectValueEqual(obj, oldObj)) {
                            can_call_callback && callback && callback(obj, oldObj);
                        }
                    }
                });
            }
        },
        /**
         * 获取引入时候是否传入额外参数
         * @Author   ZhaoXianFang
         * @DateTime 2019-03-26
         * @return   {[type]}     [description]
         * <script src="……/my.v2.js" my-init='true'></script>
         */
        getArgs: function (attr_name) {
            var sc = document.getElementsByTagName("script");
            var attr_val;
            $.each(sc, function (i, val) { //遍历二维数组
                attr_val = val.getAttribute(attr_name);
                if (attr_val) {
                    return false;
                }
            });
            return attr_val;
        }
    };
    //将my渲染至全局
    window.my = my;
    // 是否默认初始化
    // <script src="……/my.v2.js"></script>
    // <script src="……/my.v2.js" my-init='true'></script>
    // <script src="……/my.v2.js" my-init='form,noticeTool'></script>
    var my_init = my.getArgs("my-init");
    if (my_init && typeof (my_init) !== "undefined") {
        if (my_init == "true") {
            my.init();
        } else {
            try {
                // 仅仅调用指定的某几个某个方法,多个方法中间用英文逗号隔开 例如 my-init='form,noticeTool'
                var fun_arr = my_init.split(",");
                for (let index in fun_arr) {
                    my[fun_arr[index]]();
                }
            } catch (err) {
                //在此处理错误
            }
        }
    }
}(jQuery);
