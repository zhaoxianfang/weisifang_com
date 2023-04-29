/* eslint-disable @typescript-eslint/no-this-alias */
/* eslint-disable prettier/prettier */
var Table = {
    list: {},
    defaults: {
        url: "", //AJAX获取表格数据的url
        contentType: "application/x-www-form-urlencoded", //一种编码。在post请求的时候需要用到。这里用的get请求，注释掉这句话也能拿到数据
        height: '', //600
        search: true, //是否显示表格搜索
        searchText: '',
        commonSearch: false, //是否启用通用搜索
        searchFormVisible: false, //是否始终显示搜索表单
        searchOnEnterKey: false, //设置为 true时，按回车触发搜索方法，否则自动触发搜索方法
        searchAlign: "right", //指定搜索框水平方向位置
        showRefresh: false, //是否显示刷新按钮
        showToggle: false, //是否显示详细视图和列表视图的切换按钮
        showExport: false, //是否显示导出按钮 true || false
        showColumns: false, //
        sidePagination: 'server',
        method: 'get', //请求方法
        toolbar: "#toolbar", //工具栏
        cardView: false, //是否显示详细视图 卡片视图
        detailView: false, //是否显示详情
        detailFormatter: false,
        cache: false, // 设置为 false 禁用 AJAX 数据缓存， 默认为true
        sortable: true, //是否启用排序
        sortOrder: "desc", //排序方式
        sortName: 'id', // 要排序的字段
        rowStyle: false, //行样式
        minimumCountColumns: 2, //最少允许的列数
        pk: 'id',
        autoRefresh: false, //表格是否设置数据自动更新 int 类型 ,单位秒
        pageNumber: 1, //初始化加载第一页，默认第一页
        pageSize: 10,
        pageList: [10, 25, 50, 'All'],
        pagination: true, //启用分页
        showPaginationSwitch: false, // 显示分页开关
        paginationLoop: false, //当前页是边界时是否可以继续按
        paginationFirstText: "首页",
        paginationPreText: "上一页",
        paginationNextText: "下一页",
        paginationLastText: "尾页",
        clickToSelect: true, //是否启用点击选中
        singleSelect: false, //是否启用单选
        showFooter: false, //显示脚部统计
        responseHandler: false, //响应处理器 // 在响应前处理数据
        strictSearch: false, //是否全局匹配,false模糊匹配
        idField: "id", //定义id字段
        icons: {
            refresh: "fa-refresh",
            toggle: "fa-list-alt",
            columns: "fa-list",
            export: "fa-download-alt", //fa-export,
            fullscreen: 'fa-resize-full'
        },
        titleForm: '', //为空则不显示标题，不定义默认显示：普通搜索
        idTable: 'commonTable',
        exportDataType: "all", //basic当前页', 'all所有, 'selected'.
        exportTypes: ['json', 'xml', 'csv', 'txt', 'doc', 'excel'],
        exportOptions: {
            ignoreColumn: [0], //忽略某一列的索引
            fileName: '信息报表', //文件名称设置
            worksheetName: 'sheet1', //表格工作区名称
            tableName: '信息报表',
            excelstyles: ['background-color', 'color', 'font-size', 'font-weight']
        },
        locale: 'zh-CN',
        mobileResponsive: true, //是否自适应移动端
        checkOnInit: true, //是否在初始化时判断
        escape: true, //是否对内容进行转义
        striped: true, //设置为 true 会有隔行变色效果
        showFullscreen: false, //是否显示全屏按钮
        trimOnSearch: true, //搜索内容是否自动去除前后空格
        extend: {
            index_url: '',
            add_url: '',
            edit_url: '',
            del_url: '',
            import_url: '', //文件上传
            multi_url: '',
            change_url: '', // 切换
            dragsort_url: '',// 拖动排序  'ajax/weigh'
            text: {
                add_text: '',
                edit_text: '',
                multi_text: '',
                del_text: '',
                import_text: '',
            },
            upload:{
                //最大可上传文件大小
                maxsize: "10mb",
                //文件类型
                mimetype : "csv,xls,xlsx,png,jpg",
                //请求的表单参数
                multipart : true,//[true:multipart/form-data的形式来上传文件][false:以二进制的格式来上传文件]
                //是否支持批量上传
                multiple : false
            }
        }
    },
    // Bootstrap-table 列配置
    columnDefaults: {
        align: 'center',
        valign: 'middle',
    },
    config: {
        firsttd: 'tbody tr td:first-child:not(:has(div.card-views))',
        toolbar: '#toolbar',
        refreshbtn: '.btn-refresh',
        addbtn: '.btn-add',
        editbtn: '.btn-edit',
        delbtn: '.btn-del',
        importbtn: '.btn-import',
        multibtn: '.btn-multi',
        disabledbtn: '.btn-disabled',
        editonebtn: '.btn-editone',
        toolbarOptions: '.toolbar-options',
        dragsortfield: 'weigh'
    },
    // 资源文件
    resources:{
        // 空白图
        empty_img:'data:image/jpg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QAiRXhpZgAATU0AKgAAAAgAAQESAAMAAAABAAEAAAAAAAD/2wBDAAIBAQIBAQICAgICAgICAwUDAwMDAwYEBAMFBwYHBwcGBwcICQsJCAgKCAcHCg0KCgsMDAwMBwkODw0MDgsMDAz/2wBDAQICAgMDAwYDAwYMCAcIDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAz/wAARCAAjACADASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9/K5HXfjx4R8N3jW91rlr5yHDLCrz7T6EoGAPtXXV89+MP2WP7PtNV1rUvEllYJvkuNpgLJySVUuWBycgcKTk8A1MpW9ClG/qe2eEviBovjqFpNJ1G3vfL5dUOHQepU4YfUitivlH9l61vLj4wWDWu8RwpI1yR0Ee0jn6sV/HFfV1aSikk0Zxlq0FeJ/tb20erXWkWs2vaZpsMSvIbe4MzNIxOA+2ON+AAQCcd8d69srzj4x/s7W3xY1qDUF1KTTbqOIQyHyfOSRQSRxuXBGTzn8KzkrtGkXa5s/BXwbpPhHwJZ/2WbWf7XEsk93C28XL45O7AOAcgDAx6A5rrqx/APgy3+H3hKz0i1kkmhtFPzyfecsxYn8yeK2K0m7ydjOOi1CiiipKCiiigD//2Q==',
    },
    api: {
        init: function(defaults, columnDefaults, locales) {
            defaults = defaults ? defaults : {};
            columnDefaults = columnDefaults ? columnDefaults : {};
            locales = locales ? locales : {};
            // 写入bootstrap-table默认配置
            $.extend(true, $.fn.bootstrapTable.defaults, Table.defaults, defaults, {
                showExport: Table.api.isPc()
            });
            // 写入bootstrap-table column配置
            $.extend($.fn.bootstrapTable.columnDefaults, Table.columnDefaults, columnDefaults);
            //用户接管事件
            $.extend($.fn.bootstrapTable.defaults, (typeof(tableInit) !== 'undefined' && typeof(tableInit.operate) !== 'undefined') ? tableInit.operate : {});
            // 写入bootstrap-table defaults配置
            $.extend($.fn.bootstrapTable.defaults, {
                formatSearch: function() {
                    return '搜索...';
                },
                formatCommonSearch: function() {
                    return '搜索';
                },
                formatCommonSubmitButton: function() {
                    return '提交';
                },
                formatCommonResetButton: function() {
                    return '重置';
                },
                formatCommonCloseButton: function() {
                    return '关闭';
                },
                formatCommonChoose: function() {
                    return '选择';
                }
            });
            if (typeof(tableInit) !== 'undefined' && typeof(tableInit.events) !== 'undefined') {
                //追加用户自定义点击事件
                // my.mergeJSON(Table.api.events.operate,tableInit.events || $.noop);
                $.extend(Table.api.events.operate, tableInit.events || $.noop);
            }
        },
        getOptions: function(table) {
            try {
                //放在前面优先判断
                //table 1.12 新增 bootstrapVersion
                if (typeof $.fn.bootstrapTable.utils.bootstrapVersion === 'undefined') {
                    return table.bootstrapTable('getOptions');
                }
                if (typeof table === 'undefined' || typeof table.table === 'undefined') {
                    return $.fn.bootstrapTable.defaults;
                }
            } catch (err) {}
            return {};
        },
        // 绑定事件
        bindevent: function(table) {
            //Bootstrap-table的父元素,包含table,toolbar,pagnation
            var parenttable = table.closest('.bootstrap-table');
            //Bootstrap-table配置
            var options = table.bootstrapTable('getOptions');
            //Bootstrap操作区
            var toolbar = $(options.toolbar, parenttable);
            //当刷新表格时
            table.on('load-error.bs.table', function(status, res, e) {
                if (e.status === 0) {
                    return;
                }
                // Toastr.error('未知的数据格式');
            });
            //当刷新表格时
            table.on('refresh.bs.table', function(e, settings, data) {
                $(Table.config.refreshbtn, toolbar).find(".fa").addClass("fa-spin");
            });
            //当双击单元格时
            table.on('dbl-click-row.bs.table', function(e, row, element, field) {
                //用户接管双击事件
                if (typeof(tableInit) !== 'undefined' && typeof(tableInit.dblclickCallback) === "function") {
                    tableInit.dblclickCallback(row, field);
                } else {
                    $(Table.config.editonebtn, element).trigger("click");
                }
            });
            //当单击单元格时
            table.on('click-row.bs.table', function(e, row, element, field) {
                //用户接管单击事件
                if (typeof(tableInit) !== 'undefined' && typeof(tableInit.clickCallback) === "function") {
                    tableInit.clickCallback(row, field);
                }
            });
            //当内容渲染完成后
            table.on('post-body.bs.table', function(e, settings, json, xhr) {
                $(Table.config.refreshbtn, toolbar).find(".fa").removeClass("fa-spin");
                $(Table.config.delbtn, toolbar).toggleClass('disabled', true);
                $(Table.config.editbtn, toolbar).toggleClass('disabled', true);
                $(Table.config.multibtn, toolbar).toggleClass('disabled', true);
                // zhaoxianfang20210330
                $(Table.config.toolbarOptions, toolbar).toggleClass('disabled', true);
            });
            // 处理选中筛选框后按钮的状态统一变更
            table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table fa.event.check', function(value, row, element) {
                var table = $(this).closest('table');
                var ids = Table.api.selectedids(table);
                $(Table.config.multibtn, toolbar).toggleClass('disabled', !table.bootstrapTable('getSelections').length);
                $(Table.config.delbtn, toolbar).toggleClass('disabled', !table.bootstrapTable('getSelections').length);
                $(Table.config.editbtn, toolbar).toggleClass('disabled', !table.bootstrapTable('getSelections').length);
                $(Table.config.multi_url, toolbar).toggleClass('disabled', !table.bootstrapTable('getSelections').length);
                // zhaoxianfang20210330
                $(Table.config.toolbarOptions, toolbar).toggleClass('disabled', !table.bootstrapTable('getSelections').length);
            });
            // 绑定TAB事件
            $('.panel-heading ul[data-field] li a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var field = $(this).closest("ul").data("field");
                var value = $(this).data("value");
                $("select[name='" + field + "'] option[value='" + value + "']", table.closest(".bootstrap-table").find(".commonsearch-table")).prop("selected", true);
                table.bootstrapTable('refresh', {});
                return false;
            });
            // 刷新按钮事件
            $(toolbar).on('click', Table.config.refreshbtn, function() {
                table.bootstrapTable('refresh');
            });
            // 添加按钮事件
            $(toolbar).on('click', Table.config.addbtn, function() {
                var ids = Table.api.selectedids(table);
                var url = options.extend.add_url;
                if (url.indexOf("{ids}") !== -1) {
                    url = Table.api.replaceurl(url, {
                        ids: ids.length > 0 ? ids.join(",") : 0
                    }, table);
                }
                my.open(url, options.extend.text.add_text ? options.extend.text.add_text : '新增', options.layerOptions || $(this).data());
            });
            // 导入按钮事件
            $(toolbar).on('click', Table.config.importbtn, function() {
                Table.api.plupload(table, $(Table.config.importbtn, toolbar), function(data, ret) {
                    if(ret.code == 1){
                        toastr.success(ret.msg);
                    }else{
                        toastr.error(ret.msg);
                    }
                    table.bootstrapTable('refresh');
                });
            });
            // 批量编辑按钮事件
            $(toolbar).on('click', Table.config.editbtn, function() {
                var that = this;
                //循环弹出多个编辑框
                $.each(table.bootstrapTable('getSelections'), function(index, row) {
                    var url = options.extend.edit_url;
                    row = $.extend({}, row ? row : {}, {
                        ids: row[options.pk]
                    });
                    var url = Table.api.replaceurl(url, row, table);
                    // 用户在编辑前重写URL 用可自己接入的函数 setUrlBeforeEdit(url,row)
                    if (typeof(tableInit) !== 'undefined' && typeof(tableInit.setUrlBeforeEdit) === "function") {
                        var userSetUrl = tableInit.setUrlBeforeEdit(url, row);
                        url = userSetUrl ? userSetUrl : url;
                    }
                    my.open(url, options.extend.text.edit_text ? options.extend.text.edit_text : '编辑', options.layerOptions || $(this).data());
                });
            });
            // 批量操作按钮事件
            $(toolbar).on('click', Table.config.multibtn, function() {
                //ZhaoXianFang 2018-03-30
                var that = this;
                var ids = Table.api.selectedids(table);
                layer.confirm('你确定要操作选中的 ' + ids.length + ' 项 ?', {
                    icon: 3,
                    title: '温馨提示',
                    offset: 0,
                    shadeClose: true
                }, function(index) {
                    Table.api.multi("multi", ids, table, that);
                    layer.close(index);
                });
            });
            // 批量删除按钮事件
            $(toolbar).on('click', Table.config.delbtn, function() {
                var that = this;
                var ids = Table.api.selectedids(table);
                layer.confirm('你确定要删除选中的 ' + ids.length + ' 项 ?', {
                    icon: 3,
                    title: '温馨提示',
                    offset: 0,
                    shadeClose: true
                }, function(index) {
                    Table.api.multi("del", ids, table, that);
                    layer.close(index);
                });
            });
            $(table).on("click", "[data-id].btn-change", function (e) {
                e.preventDefault();
                var row = Table.api.getrowbyid(table,$(this).data("id"));
                if (typeof(tableInit) !== 'undefined' && typeof(tableInit.btnChange) === "function") {
                    var field = $(this).data("params").split("="); //字符分割 出 field
                    tableInit.btnChange(e, row, $(this).data("id"),field['0']);
                }
                Table.api.multi($(this).data("action") ? $(this).data("action") : '', [$(this).data("id")], table, this);
            });
            //绑定定时刷新
            if (typeof($.fn.bootstrapTable.defaults.autoRefresh) === 'number' && ($.fn.bootstrapTable.defaults.autoRefresh) % 1 === 0 && ($.fn.bootstrapTable.defaults.autoRefresh) > 0) {
                setInterval(function() {
                    var page = table.bootstrapTable('getOptions').pageNumber; //用户所在页码
                    table.bootstrapTable('selectPage', +page);
                }, ($.fn.bootstrapTable.defaults.autoRefresh) * 1000);
            }
            //修复dropdown定位溢出的情况
            if (options.fixDropdownPosition) {
                var tableBody = table.closest(".fixed-table-body");
                table.on('show.bs.dropdown fa.event.refreshdropdown', ".btn-group", function (e) {
                    var dropdownMenu = $(".dropdown-menu", this);
                    var btnGroup = $(this);
                    var isPullRight = dropdownMenu.hasClass("pull-right") || dropdownMenu.hasClass("dropdown-menu-right");
                    var left, top, position;
                    if (dropdownMenu.outerHeight() + btnGroup.outerHeight() > tableBody.outerHeight() - 41) {
                        position = 'fixed';
                        top = btnGroup.offset().top - $(window).scrollTop() + btnGroup.outerHeight();
                        left = isPullRight ? btnGroup.offset().left + btnGroup.outerWidth() - dropdownMenu.outerWidth() : btnGroup.offset().left;
                    } else {
                        if (btnGroup.offset().top + btnGroup.outerHeight() + dropdownMenu.outerHeight() > tableBody.offset().top + tableBody.outerHeight() - 30) {
                            position = 'absolute';
                            left = isPullRight ? -(dropdownMenu.outerWidth() - btnGroup.outerWidth()) : 0;
                            top = -(dropdownMenu.outerHeight() + 3);
                        }
                    }
                    if (left || top) {
                        dropdownMenu.css({
                            position: position, left: left, top: top, right: 'inherit'
                        });
                    }
                });
                var checkdropdown = function () {
                    if ($(".btn-group.open", table).length > 0 && $(".btn-group.open .dropdown-menu", table).css("position") == 'fixed') {
                        $(".btn-group.open", table).trigger("fa.event.refreshdropdown");
                    }
                };
                $(window).on("scroll", function () {
                    checkdropdown();
                });
                tableBody.on("scroll", function () {
                    checkdropdown();
                });
            }
            var id = table.attr("id");
            Table.list[id] = table;
            return table;
        },
        // 获取选中的条目ID集合
        selectedids: function(table) {
            var options = table.bootstrapTable('getOptions');
            if (options.templateView) {
                return $.map($("input[data-id][name='checkbox']:checked"), function(dom) {
                    return $(dom).data("id");
                });
            } else {
                return $.map(table.bootstrapTable('getSelections'), function(row) {
                    return row[options.pk];
                });
            }
        },
        // row 自定义请求 20200617 zxf add ,action 为 extend 里面的url地址名称 ,例如 add_url
        custom_request: function(action, ids, table, element) {
            var options = table.bootstrapTable('getOptions');
            var data = element ? $(element).data() : {};
            var ids = ($.isArray(ids) ? ids.join(",") : ids);
            var url = typeof data.url !== "undefined" ? data.url : (typeof(options.extend[action]) !== "undefined" ? options.extend[action] : '');
            if(!url){
                layer.error('未定义的请求:'+action);
                return false;
            }
            url = this.replaceurl(url, {
                ids: ids
            }, table);
            var params = typeof data.params !== "undefined" ? (typeof data.params == 'object' ? $.param(data.params) : data.params) : '';
            var ajaxData = {
                action: action,
                ids: ids,
                params: params
            }; //请求的数据
            // 用户在编辑前重写URL 用可自己接入的函数 setUrlBeforeMulti(url,data,selections,ids)
            if (typeof(tableInit) !== 'undefined' && typeof(tableInit.setUrlBeforeMulti) === "function") {
                var selections = table.bootstrapTable('getSelections'); //选中的项
                var userSetUrl = tableInit.setUrlBeforeMulti(url, data, selections, ids);
                //重写url
                if (typeof(userSetUrl.url) !== 'undefined' && userSetUrl.url) {
                    url = userSetUrl.url;
                }
                //重写data 请求数据
                if (typeof(userSetUrl.data) !== 'undefined' && userSetUrl.data) {
                    ajaxData = userSetUrl.data;
                }
            }
            my.ajax(url, ajaxData, function(data, ret) {
                var success = $(element).data("success") || $.noop;
                if (typeof success === 'function') {
                    if (false === success.call(element, data, ret)) {
                        return false;
                    }
                }
                table.bootstrapTable('refresh');
            }, function(data, ret) {
                var error = $(element).data("error") || $.noop;
                if (typeof error === 'function') {
                    if (false === error.call(element, data, ret)) {
                        return false;
                    }
                }
            });
        },
        // 批量操作请求
        multi: function(action, ids, table, element) {
            var options = table.bootstrapTable('getOptions');
            var data = element ? $(element).data() : {};
            var ids = ($.isArray(ids) ? ids.join(",") : ids);
            var url = typeof data.url !== "undefined" ? data.url : (action == "del" ? options.extend.del_url : options.extend.multi_url);
            url = this.replaceurl(url, {
                ids: ids
            }, table);
            var params = typeof data.params !== "undefined" ? (typeof data.params == 'object' ? $.param(data.params) : data.params) : (my.isJsonString(data.params)?JSON.parse(data.params):'');
            var ajaxData = {
                action: action,
                ids: ids,
                params: my.urlToObject(params)
            }; //请求的数据
            // 用户在编辑前重写URL 用可自己接入的函数 setUrlBeforeMulti(url,data,selections,ids)
            if (typeof(tableInit) !== 'undefined' && typeof(tableInit.setUrlBeforeMulti) === "function") {
                var selections = table.bootstrapTable('getSelections'); //选中的项
                var userSetUrl = tableInit.setUrlBeforeMulti(url, data, selections, ids);
                //重写url
                if (typeof(userSetUrl.url) !== 'undefined' && userSetUrl.url) {
                    url = userSetUrl.url;
                }
                //重写data 请求数据
                if (typeof(userSetUrl.data) !== 'undefined' && userSetUrl.data) {
                    ajaxData = userSetUrl.data;
                }
            }
            // console.log(ajaxData,url)
            my.ajax(url, ajaxData, function(data, ret) {
                var success = $(element).data("success") || $.noop;
                if (typeof success === 'string') {
                    if (false === window[success].call(element, data, ret)) {
                        return false;
                    }
                }
                if (typeof success === 'function') {
                    if (false === success.call(element, data, ret)) {
                        return false;
                    }
                }
                table.bootstrapTable('refresh');
            }, function(data, ret) {
                var error = $(element).data("error") || $.noop;
                if (typeof error === 'string') {
                    if (false === window[error].call(element, data, ret)) {
                        return false;
                    }
                }
                if (typeof error === 'function') {
                    if (false === error.call(element, data, ret)) {
                        return false;
                    }
                }
            });
        },
        gettablecolumnbutton: function (options) {
            if (typeof options.tableId !== 'undefined' && typeof options.fieldIndex !== 'undefined' && typeof options.buttonIndex !== 'undefined') {
                var tableOptions = $("#" + options.tableId).bootstrapTable('getOptions');
                if (tableOptions) {
                    var columnObj = null;
                    $.each(tableOptions.columns, function (i, columns) {
                        $.each(columns, function (j, column) {
                            if (typeof column.fieldIndex !== 'undefined' && column.fieldIndex === options.fieldIndex) {
                                columnObj = column;
                                return false;
                            }
                        });
                        if (columnObj) {
                            return false;
                        }
                    });
                    if (columnObj) {
                        return columnObj['buttons'][options.buttonIndex];
                    }
                }
            }
            return null;
        },
        sidebar: function (params) {
            colorArr = ['red', 'green', 'yellow', 'blue', 'teal', 'orange', 'purple'];
            $colorNums = colorArr.length;
            badgeList = {};
            $.each(params, function (k, v) {
                $url = my.fixurl(k);
                if ($.isArray(v)) {
                    $nums = typeof v[0] !== 'undefined' ? v[0] : 0;
                    $color = typeof v[1] !== 'undefined' ? v[1] : colorArr[(!isNaN($nums) ? $nums : $nums.length) % $colorNums];
                    $class = typeof v[2] !== 'undefined' ? v[2] : 'label';
                } else {
                    $nums = v;
                    $color = colorArr[(!isNaN($nums) ? $nums : $nums.length) % $colorNums];
                    $class = 'label';
                }
                //必须nums大于0才显示
                badgeList[$url] = $nums > 0 ? '<small class="' + $class + ' pull-right bg-' + $color + '">' + $nums + '</small>' : '';
            });
            $.each(badgeList, function (k, v) {
                var anchor = top.window.$("li a[addtabs][url='" + k + "']");
                if (anchor) {
                    top.window.$(".pull-right-container", anchor).html(v);
                    top.window.$(".nav-addtabs li a[node-id='" + anchor.attr("addtabs") + "'] .pull-right-container").html(v);
                }
            });
        },
        addtabs: function (url, title, icon) {
            var dom = "a[url='{url}']"
            var leftlink = top.window.$(dom.replace(/\{url\}/, url));
            if (leftlink.length > 0) {
                leftlink.trigger("click");
            } else {
                url = my.fixurl(url);
                leftlink = top.window.$(dom.replace(/\{url\}/, url));
                if (leftlink.length > 0) {
                    var event = leftlink.parent().hasClass("active") ? "dblclick" : "click";
                    leftlink.trigger(event);
                } else {
                    var baseurl = url.substr(0, url.indexOf("?") > -1 ? url.indexOf("?") : url.length);
                    leftlink = top.window.$(dom.replace(/\{url\}/, baseurl));
                    //能找到相对地址
                    if (leftlink.length > 0) {
                        icon = typeof icon !== 'undefined' ? icon : leftlink.find("i").attr("class");
                        title = typeof title !== 'undefined' ? title : leftlink.find("span:first").text();
                        leftlink.trigger("fa.event.toggleitem");
                    }
                    var navnode = top.window.$(".nav-tabs ul li a[node-url='" + url + "']");
                    if (navnode.length > 0) {
                        navnode.trigger("click");
                    } else {
                        //追加新的tab
                        var id = Math.floor(new Date().valueOf() * Math.random());
                        icon = typeof icon !== 'undefined' ? icon : 'fa fa-circle-o';
                        title = typeof title !== 'undefined' ? title : '';
                        top.window.$("<a />").append('<i class="' + icon + '"></i> <span>' + title + '</span>').prop("href", url).attr({
                            url: url,
                            addtabs: id
                        }).addClass("hide").appendTo(top.window.document.body).trigger("click");
                    }
                }
            }
        },
        closetabs: function (url) {
            if (typeof url === 'undefined') {
                top.window.$("ul.nav-addtabs li.active .close-tab").trigger("click");
            } else {
                var dom = "a[url='{url}']"
                var navlink = top.window.$(dom.replace(/\{url\}/, url));
                if (navlink.length === 0) {
                    url = my.fixurl(url);
                    navlink = top.window.$(dom.replace(/\{url\}/, url));
                    if (navlink.length === 0) {
                    } else {
                        var baseurl = url.substr(0, url.indexOf("?") > -1 ? url.indexOf("?") : url.length);
                        navlink = top.window.$(dom.replace(/\{url\}/, baseurl));
                        //能找到相对地址
                        if (navlink.length === 0) {
                            navlink = top.window.$(".nav-tabs ul li a[node-url='" + url + "']");
                        }
                    }
                }
                if (navlink.length > 0 && navlink.attr('addtabs')) {
                    top.window.$("ul.nav-addtabs li#tab_" + navlink.attr('addtabs') + " .close-tab").trigger("click");
                }
            }
        },
        replaceids: function (elem, url) {
            //如果有需要替换ids的
            if (url.indexOf("{ids}") > -1) {
                var ids = 0;
                var tableId = $(elem).data("table-id");
                if (tableId && $("#" + tableId).length > 0 && $("#" + tableId).data("bootstrap.table")) {
                    var Table = require("table");
                    ids = Table.api.selectedids($("#" + tableId)).join(",");
                }
                url = url.replace(/\{ids\}/g, ids);
            }
            return url;
        },
        refreshmenu: function () {
            top.window.$(".sidebar-menu").trigger("refresh");
        },
        // 单元格元素事件
        events: {
            operate: {
                'click .btn-editone': function(e, value, row, index) {
                    e.stopPropagation();
                    e.preventDefault();
                    var table = $(this).closest('table');
                    var options = table.bootstrapTable('getOptions');
                    var ids = row[options.pk];
                    row = $.extend({}, row ? row : {}, {
                        ids: ids
                    });
                    var url = options.extend.edit_url;
                    // 用户在编辑前重写URL 用可自己接入的函数 setUrlBeforeEdit(url,row)
                    if (typeof(setUrlBeforeEdit) === "function") {
                        var userSetUrl = setUrlBeforeEdit(url, row);
                        url = userSetUrl ? userSetUrl : url;
                    }
                    my.open(Table.api.replaceurl(url, row, table), options.extend.text.edit_text ? options.extend.text.edit_text : '编辑', options.layerOptions || $(this).data());
                },
                'click .btn-delone': function(e, value, row, index) {
                    e.stopPropagation();
                    e.preventDefault();
                    var that = this;
                    var top = $(that).offset().top - $(window).scrollTop();
                    var left = $(that).offset().left - $(window).scrollLeft() - 260;
                    if (top + 154 > $(window).height()) {
                        top = top - 154;
                    }
                    if ($(window).width() < 480) {
                        top = left = undefined;
                    }
                    layer.confirm('确定删除此项?', {
                        icon: 3,
                        title: '温馨提示',
                        skin: 'layui-layer-lan', //样式类名 深蓝
                        offset: [top, left],
                        shadeClose: true
                    }, function(index) {
                        var table = $(that).closest('table');
                        var options = table.bootstrapTable('getOptions');
                        Table.api.multi("del", row[options.pk], table, that);
                        layer.close(index);
                    });
                },
                'click .btn-click': function(e, value, row, index) {
                    var that = this;
                    var options = $.extend({}, $(that).data() || {});
                    var row = {};
                    if (typeof options.tableId !== 'undefined') {
                        var index = parseInt(options.rowIndex);
                        var data = $("#" + options.tableId).bootstrapTable('getData');
                        row = typeof data[index] !== 'undefined' ? data[index] : {};
                    }
                    var button = Table.api.gettablecolumnbutton(options);
                    var click = typeof button.click === 'function' ? button.click : $.noop;
                    if (typeof options.confirm !== 'undefined') {
                        layer.confirm(options.confirm, function (index) {
                            click.apply(that, [options, row, button]);
                            layer.close(index);
                        });
                    } else {
                        click.apply(that, [options, row, index, button]);
                    }
                    return false;
                },
                'click .btn-open': function(e, value, row, index) {
                    e.preventDefault()
                    var that = this;
                    var options = $.extend({}, $(that).data() || {});
                    var url = Table.api.replaceids(that, $(that).data("url") || $(that).attr('href'));
                    var title = $(that).attr("title") || $(that).data("title") || $(that).data('original-title');
                    var button = Table.api.gettablecolumnbutton(options);
                    if (button && typeof button.callback === 'function') {
                        options.callback = button.callback;
                    }
                    if (typeof options.confirm !== 'undefined') {
                        layer.confirm(options.confirm, function (index) {
                            my.open(url, title, options);
                            layer.close(index);
                        });
                    } else {
                        window[$(that).data("window") || 'self'].my.open(url, title, options);
                    }
                    var callback = typeof button.callback === 'function' ? button.callback : $.noop;
                    callback.apply(that, [options, row, index, button]);
                    return false;
                },
                'click .btn-ajax': function(e, value, row, index) {
                    var that = this;
                    var options = $.extend({}, $(that).data() || {});
                    if (typeof options.url === 'undefined' && $(that).attr("href")) {
                        options.url = $(that).attr("href");
                    }
                    options.url = Table.api.replaceids(this, options.url);
                    var success = typeof options.success === 'function' ? options.success : null;
                    var error = typeof options.error === 'function' ? options.error : null;
                    delete options.success;
                    delete options.error;
                    var button = Table.api.gettablecolumnbutton(options);
                    if (button) {
                        if (typeof button.success === 'function') {
                            success = button.success;
                        }
                        if (typeof button.error === 'function') {
                            error = button.error;
                        }
                    }
                    //如果未设备成功的回调,设定了自动刷新的情况下自动进行刷新
                    if (!success && typeof options.tableId !== 'undefined' && typeof options.refresh !== 'undefined' && options.refresh) {
                        success = function () {
                            $("#" + options.tableId).bootstrapTable('refresh');
                        }
                    }
                    if (typeof options.confirm !== 'undefined') {
                        layer.confirm(options.confirm, function (index) {
                            // my.ajax(options, success, error);
                            my.ajax(options.url,options, success, error);
                            layer.close(index);
                        });
                    } else {
                        my.ajax(options.url,options, success, error);
                        // my.ajax(options, success, error);
                    }
                    return false;
                },
                //点击包含.btn-addtabs的元素时新增选项卡
                'click .btn-addtabs': function(e, value, row, index) {
                    var that = this;
                    var options = $.extend({}, $(that).data() || {});
                    var url = Backend.api.replaceids(that, $(that).data("url") || $(that).attr('href'));
                    var title = $(that).attr("title") || $(that).data("title") || $(that).data('original-title');
                    var icon = $(that).attr("icon") || $(that).data("icon");
                    if (typeof options.confirm !== 'undefined') {
                        layer.confirm(options.confirm, function (index) {
                            Table.api.addtabs(url, title, icon);
                            layer.close(index);
                        });
                    } else {
                        Table.api.addtabs(url, title, icon);
                    }
                    var button = Table.api.gettablecolumnbutton(options);
                    var callback = typeof button.callback === 'function' ? button.callback : $.noop;
                    callback.apply(that, [options, row, index, button]);
                    return false;
                }
            }
        },
        // 单元格数据格式化
        formatter: {
            icon: function(value, row, index) {
                if (!value) return '';
                value = value === null ? '' : value.toString();
                value = value.indexOf(" ") > -1 ? value : "fa fa-" + value;
                //渲染fontawesome图标
                return '<i class="' + value + '"></i> ' + value;
            },
            image: function(value, row, index) {
                value = value ? value : Table.resources.empty_img;
                var classname = typeof this.classname !== 'undefined' ? this.classname : 'img-sm img-center';
                return '<a href="' + value + '" target="_blank"><img class="' + classname + '" src="' + value + '" /></a>';
            },
            images: function(value, row, index) {
                value = value === null ? '' : value.toString();
                var classname = typeof this.classname !== 'undefined' ? this.classname : 'img-sm img-center';
                var arr = value.split(',');
                var html = [];
                $.each(arr, function(i, value) {
                    value = value ? value : Table.resources.empty_img;
                    html.push('<a href="' + value + '" target="_blank"><img class="' + classname + '" src="' + value + '" /></a>');
                });
                return html.join(' ');
            },
            status: function(value, row, index) {
                var custom = {
                    normal: 'success',
                    hidden: 'gray',
                    deleted: 'danger',
                    locked: 'info'
                };
                if (typeof this.custom !== 'undefined') {
                    custom = $.extend(custom, this.custom);
                }
                this.custom = custom;
                this.icon = 'fa fa-circle';
                return Table.api.formatter.normal.call(this, value, row, index);
            },
            method: function(value, row, index) {
                var method = {
                    "post": '#008d4c',
                    "get": '#5bc0de',
                    "delete": '#c9302c',
                    'put': '#C0C0C0'
                };
                return '<a class="btn btn-sm btn-ip " style="background-color:'+method[value]+';color:#ffffff;"><i class="fa fa-info"></i> ' + value + '</a>';
            },
            normal: function(value, row, index) {
                var colorArr = ["primary", "success", "danger", "warning", "info", "gray", "red", "yellow", "aqua", "blue", "navy", "teal", "olive", "lime", "fuchsia", "purple", "maroon"];
                var custom = {};
                if (typeof this.custom !== 'undefined') {
                    custom = $.extend(custom, this.custom);
                }
                value = value === null ? '' : value.toString();
                var keys = typeof this.searchList === 'object' ? Object.keys(this.searchList) : [];
                var index = keys.indexOf(value);
                var color = value && typeof custom[value] !== 'undefined' ? custom[value] : null;
                var display = index > -1 ? this.searchList[value] : null;
                var icon = typeof this.icon !== 'undefined' ? this.icon : null;
                if (!color) {
                    color = index > -1 && typeof colorArr[index] !== 'undefined' ? colorArr[index] : 'primary';
                }
                if (!display) {
                    display = value.charAt(0).toUpperCase() + value.slice(1);
                }
                var html = '<span class="text-' + color + '">' + (icon ? '<i class="' + icon + '"></i> ' : '') + display + '</span>';
                if (this.operate != false) {
                    html = '<a href="javascript:;" class="searchit" data-toggle="tooltip" title="' + '点击搜索 ' + display + '" data-field="' + this.field + '" data-value="' + value + '">' + html + '</a>';
                }
                return html;
            },
            toggle: function(value, row, index) {
                var color = typeof this.color !== 'undefined' ? this.color : 'success';
                var yes = typeof this.yes !== 'undefined' ? this.yes : 1;
                var no = typeof this.no !== 'undefined' ? this.no : 0;
                return "<a href='javascript:;' data-toggle='tooltip' title='" + '点击切换' + "' class='btn-change' data-id='" + row.id + "' data-params='" + this.field + "=" + (value == yes ? no : yes) + "'><i class='fa fa-toggle-on " + (value == yes ? 'text-' + color : 'fa-flip-horizontal text-gray') + " fa-2x'></i></a>";
            },
            url: function(value, row, index) {
                return '<div class="input-group input-group-sm" style="width:250px;margin:0 auto;"><input type="text" class="form-control input-sm" value="' + value + '"><span class="input-group-btn input-group-sm"><a href="' + value + '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-link"></i></a></span></div>';
            },
            input: function(value, row, index) {
                value = value?value:'';
                return '<div class="input-group input-group-sm" style="width:230px;margin:0 auto;"><input type="text" class="form-control input-sm" placeholder="未设置" value="' + value + '"></div>';
            },
            search: function(value, row, index) {
                return '<a href="javascript:;" class="searchit" data-toggle="tooltip" title="' + '点击搜索 ' + value + '" data-field="' + this.field + '" data-value="' + value + '">' + value + '</a>';
            },
            addtabs: function(value, row, index) {
                var url = Table.api.replaceurl(this.url, row, this.table);
                var title = this.atitle ? this.atitle : "搜索 " + value;
                return '<a href="' + url + '" class="addtabsit" data-value="' + value + '" title="' + title + '">' + value + '</a>';
            },
            dialog: function(value, row, index) {
                var url = Table.api.replaceurl(this.url, row, this.table);
                var title = this.atitle ? this.atitle : "查看 " + value;
                return '<a href="' + url + '" class="dialogit" data-value="' + value + '" title="' + title + '">' + value + '</a>';
            },
            flag: function(value, row, index) {
                var that = this;
                value = value === null ? '' : value.toString();
                var colorArr = {
                    index: 'success',
                    hot: 'warning',
                    recommend: 'danger',
                    'new': 'info'
                };
                //如果字段列有定义custom
                if (typeof this.custom !== 'undefined') {
                    colorArr = $.extend(colorArr, this.custom);
                }
                var field = this.field;
                if (typeof this.customField !== 'undefined' && typeof row[this.customField] !== 'undefined') {
                    value = row[this.customField];
                    field = this.customField;
                }
                //渲染Flag
                var html = [];
                var arr = value.split(',');
                $.each(arr, function(i, value) {
                    value = value === null ? '' : value.toString();
                    if (value == '') return true;
                    var color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'primary';
                    var display = typeof that.searchList !== 'undefined' && typeof that.searchList[value] !== 'undefined' ? that.searchList[value] : value.charAt(0).toUpperCase() + value.slice(1);
                    html.push('<a href="javascript:;" class="searchit" data-toggle="tooltip" title="' + '点击搜索 ' + display + '" data-field="' + field + '" data-value="' + value + '"><span class="label label-' + color + '">' + display + '</span></a>');
                });
                return html.join(' ');
            },
            label: function(value, row, index) {
                return Table.api.formatter.flag.call(this, value, row, index);
            },
            datetime: function(value, row, index) {
                var datetimeFormat = typeof this.datetimeFormat === 'undefined' ? 'YYYY-MM-DD HH:mm:ss' : this.datetimeFormat;
                if (isNaN(value)) {
                    return value ? moment(value).format(datetimeFormat) : '空';
                } else {
                    return value ? moment(parseInt(value) * 1000).format(datetimeFormat) : '空';
                }
            },
            gender: function (value, row, index) {
                value = value?value:0;
                // 性别
                var gender_list = {
                    "0": 'venus-mars',//未设置
                    "1": 'mars',//男
                    "2": 'venus'//女
                };
                var gender_text = {
                    "0": '未设置',//未设置
                    "1": '男',//男
                    "2": '女'//女
                };
                //渲染fontawesome图标
                return '<span class="text-success"><i class="fa fa-' + gender_list[value] + '"></i> '+gender_text[value]+'</span>';
            },
            operate: function(value, row, index) {
                var _this =this
                var table = this.table;
                // 操作配置
                var options = (table && table.bootstrapTable !== undefined)  ? table.bootstrapTable('getOptions') : {};
                // 默认按钮组
                var buttons = $.extend([], this.buttons || []);
                // 所有按钮名称
                var names = [];
                buttons.forEach(function(item) {
                    names.push(item.name);
                });
                // zhaoxianfang 添加自定义 data-params 属性，使用：在events 同级添加 extendParamsField字段属性
                var customizeExtend = $(_this)['0'].extendParamsField != undefined ?  'data-params={"'+$(_this)['0'].extendParamsField+'":"' + row[$(_this)['0'].extendParamsField]+'"}' :'';
                if(options.extend !== undefined){
                    if (options.extend.dragsort_url  !== undefined && options.extend.dragsort_url !== '' && names.indexOf('dragsort') === -1) {
                        buttons.push({
                            name: 'dragsort',
                            icon: 'fa fa-arrows',
                            title: 'Drag to sort',
                            extend: 'data-toggle="tooltip"',
                            classname: 'btn btn-sm btn-primary btn-dragsort'
                        });
                    }
                    if (options.extend.edit_url  !== undefined && options.extend.edit_url !== '' && names.indexOf('edit') === -1) {
                        buttons.push({
                            name: 'edit',
                            icon: 'fa fa-pencil',
                            title: (typeof (options.extend.text) !== 'undefined' && typeof (options.extend.text.edit_text) !== 'undefined' && options.extend.text.edit_text)  ? options.extend.text.edit_text : '编辑',
                            text: (typeof (options.extend.text) !== 'undefined' && typeof (options.extend.text.edit_text) !== 'undefined' && options.extend.text.edit_text) ? options.extend.text.edit_text : '编辑',
                            extend: 'data-toggle="tooltip" '+ customizeExtend,
                            classname: 'btn btn-sm btn-success btn-editone',
                            url: options.extend.edit_url
                        });
                    }
                    if (options.extend.del_url  !== undefined && options.extend.del_url !== '' && names.indexOf('del') === -1) {
                        buttons.push({
                            name: 'del',
                            icon: 'fa fa-trash',
                            title: (typeof (options.extend.text) !== 'undefined' && typeof options.extend.text.del_text !== 'undefined' && options.extend.text.del_text) ? options.extend.text.del_text : '删除',
                            text: (typeof (options.extend.text) !== 'undefined' && typeof options.extend.text.del_text !== 'undefined' && options.extend.text.del_text) ? options.extend.text.del_text : '删除',
                            extend: 'data-toggle="tooltip" '+ customizeExtend,
                            classname: 'btn btn-sm btn-danger btn-delone'
                        });
                    }
                }
                var userBtn = '';
                var useSysBtn = true; //使用系统按钮
                // 追加用户自定义btn 用可自己接入的函数 initOperateBtn(row, index, field)
                if (typeof(tableInit) !== 'undefined' && typeof(tableInit.initOperateBtn) === "function") {
                    userRes = tableInit.initOperateBtn(row, index, 'operate');
                    if (userRes === false) {
                        //禁用默认按钮
                        useSysBtn = false;
                        userBtn = '';
                    } else if (typeof userRes === 'string') {
                        userBtn = userRes || '';
                    } else if (userRes instanceof Array) {
                        userBtn = userRes['0'] || '';
                        if (typeof(userRes['1']) !== 'undefined' && userRes['1'] === false) {
                            //禁用默认按钮
                            useSysBtn = false;
                        }
                    }
                }
                return userBtn + (useSysBtn ? Table.api.buttonlink(this, buttons, value, row, index, 'operate') : '');
            },
            buttons: function(value, row, index) {
                // 默认按钮组
                var buttons = $.extend([], this.buttons || []);
                return Table.api.buttonlink(this, buttons, value, row, index, 'buttons');
            },
            ip: function (value, row, index) {
                return '<a class="btn btn-xs btn-ip bg-success"><i class="fa fa-map-marker"></i> ' + value + '</a>';
            },
            browser: function (value, row, index) {
                //这里我们直接使用row的数据
                return '<a class="btn btn-xs btn-browser">' + row.useragent.split(" ")[0] + '</a>';
            },
        },
        buttonlink: function(column, buttons, value, row, index, type) {
            // var table = column.table;
            // type = typeof type === 'undefined' ? 'buttons' : type;
            // var options = (table && table.bootstrapTable !== undefined)? table.bootstrapTable('getOptions') : {};
            // var html = [];
            // var url, classname, icon, text, title, extend;
            // var fieldIndex = column.fieldIndex;
            // $.each(buttons, function(i, j) {
            //     if (type === 'operate') {
            //         if (j.name === 'dragsort' && typeof row[Table.config.dragsortfield] === 'undefined') {
            //             return true;
            //         }
            //         if (['add', 'edit', 'del', 'multi', 'dragsort'].indexOf(j.name) > -1 && !options.extend[j.name + "_url"]) {
            //             return true;
            //         }
            //     }
            //     var attr = table.data(type + "-" + j.name);
            //     if (typeof attr === 'undefined' || attr) {
            //         url = j.url ? j.url : '';
            //         url = url ? url : 'javascript:;';
            //         classname = j.classname ? j.classname : 'btn-primary btn-' + name + 'one';
            //         icon = j.icon ? j.icon : '';
            //         text = j.text ? j.text : '';
            //         title = j.title ? j.title : text;
            //         refresh = j.refresh ? 'data-refresh="' + j.refresh + '"' : '';
            //         confirm = j.confirm ? 'data-confirm="' + j.confirm + '"' : '';
            //         extend = j.extend ? j.extend : '';
            //         html.push('<a href="' + url + '" class="' + classname + '" ' + (confirm ? confirm + ' ' : '') + (refresh ? refresh + ' ' : '') + extend + ' title="' + title + '" data-table-id="' + (table ? table.attr("id") : '') + '" data-field-index="' + fieldIndex + '" data-row-index="' + index + '" data-button-index="' + i + '"><i class="' + icon + '"></i>' + (text ? ' ' + text : '') + '</a>');
            //     }
            // });
            // return html.join(' ');
            var table = column.table;
                type = typeof type === 'undefined' ? 'buttons' : type;
                var options = table ? table.bootstrapTable('getOptions') : {};
                var html = [];
                var hidden, visible, disable, url, classname, icon, text, title, refresh, confirm, extend,
                    dropdown, link;
                var fieldIndex = column.fieldIndex;
                var dropdowns = {};
                $.each(buttons, function (i, j) {
                    if (type === 'operate') {
                        if (j.name === 'dragsort' && typeof row[Table.config.dragsortfield] === 'undefined') {
                            return true;
                        }
                        if (['add', 'edit', 'del', 'multi', 'dragsort'].indexOf(j.name) > -1 && !options.extend[j.name + "_url"]) {
                            return true;
                        }
                    }
                    var attr = table.data(type + "-" + j.name);
                    if (typeof attr === 'undefined' || attr) {
                        hidden = typeof j.hidden === 'function' ? j.hidden.call(table, row, j) : (typeof j.hidden !== 'undefined' ? j.hidden : false);
                        if (hidden) {
                            return true;
                        }
                        visible = typeof j.visible === 'function' ? j.visible.call(table, row, j) : (typeof j.visible !== 'undefined' ? j.visible : true);
                        if (!visible) {
                            return true;
                        }
                        dropdown = j.dropdown ? j.dropdown : '';
                        url = j.url ? j.url : '';
                        url = typeof url === 'function' ? url.call(table, row, j) : (url ? my.fixurl(Table.api.replaceurl(url, row, table)) : 'javascript:;');
                        classname = j.classname ? j.classname : 'btn-primary btn-' + name + 'one';
                        icon = j.icon ? j.icon : '';
                        text = typeof j.text === 'function' ? j.text.call(table, row, j) : j.text ? j.text : '';
                        title = typeof j.title === 'function' ? j.title.call(table, row, j) : j.title ? j.title : text;
                        refresh = j.refresh ? 'data-refresh="' + j.refresh + '"' : '';
                        confirm = typeof j.confirm === 'function' ? j.confirm.call(table, row, j) : (typeof j.confirm !== 'undefined' ? j.confirm : false);
                        confirm = confirm ? 'data-confirm="' + confirm + '"' : '';
                        extend = j.extend ? j.extend : '';
                        disable = typeof j.disable === 'function' ? j.disable.call(table, row, j) : (typeof j.disable !== 'undefined' ? j.disable : false);
                        if (disable) {
                            classname = classname + ' disabled';
                        }
                        link = '<a href="' + url + '" class="' + classname + '" ' + (confirm ? confirm + ' ' : '') + (refresh ? refresh + ' ' : '') + extend + ' title="' + title + '" data-table-id="' + (table ? table.attr("id") : '') + '" data-field-index="' + fieldIndex + '" data-row-index="' + index + '" data-button-index="' + i + '"><i class="' + icon + '"></i>' + (text ? ' ' + text : '') + '</a>';
                        if (dropdown) {
                            if (typeof dropdowns[dropdown] == 'undefined') {
                                dropdowns[dropdown] = [];
                            }
                            dropdowns[dropdown].push(link);
                        } else {
                            html.push(link);
                        }
                    }
                });
                if (!$.isEmptyObject(dropdowns)) {
                    var dropdownHtml = [];
                    $.each(dropdowns, function (i, j) {
                        dropdownHtml.push('<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown">' + i + '</button><button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown"><span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li>' + j.join('</li><li>') + '</li></ul></div>');
                    });
                    html.unshift(dropdownHtml);
                }
                return html.join(' ');
        },
        //替换URL中的数据
        replaceurl: function(url, row, table) {
            var options = (table && table.bootstrapTable !== undefined) ? table.bootstrapTable('getOptions') : null;
            var ids = options ? row[options.pk] : 0;
            row.ids = ids ? ids : (typeof row.ids !== 'undefined' ? row.ids : 0);
            //自动添加ids参数
            url = !url.match(/\{ids\}/i) ? url + (url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + '{ids}' : url;
            url = url.replace(/\{(.*?)\}/gi, function(matched) {
                matched = matched.substring(1, matched.length - 1);
                if (matched.indexOf(".") !== -1) {
                    var temp = row;
                    var arr = matched.split(/\./);
                    for (var i = 0; i < arr.length; i++) {
                        if (typeof temp[arr[i]] !== 'undefined') {
                            temp = temp[arr[i]];
                        }
                    }
                    return typeof temp === 'object' ? '' : temp;
                }
                return row[matched];
            });
            return url;
        },
        /*判断终端是不是PC--用于判断文件是否导出(电脑需要导出)*/
        isPc: function() {
            var sUserAgent = navigator.userAgent.toLowerCase();
            var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
            var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
            var bIsMidp = sUserAgent.match(/midp/i) == "midp";
            var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
            var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
            var bIsAndroid = sUserAgent.match(/android/i) == "android";
            var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
            var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
            if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
                return false;
            } else {
                return true;
            }
        },
        // 根据行索引获取行数据
        getrowdata: function(table, index) {
            index = parseInt(index);
            var data = table.bootstrapTable('getData');
            return typeof data[index] !== 'undefined' ? data[index] : null;
        },
        // 根据行索引获取行数据
        getrowbyindex: function(table, index) {
            return Table.api.getrowdata(table, index);
        },
        // 根据主键ID获取行数据
        getrowbyid: function(table, id) {
            var row = {};
            var options = table.bootstrapTable('getOptions');
            $.each(table.bootstrapTable('getData'), function(i, j) {
                if (j[options.pk] == id) {
                    row = j;
                    return false;
                }
            });
            return row;
        },
        //Plupload上传
        plupload: function(table, element, onUploadSuccess, onUploadError, onUploadComplete) {
            //Bootstrap-table配置
            var options = table.bootstrapTable('getOptions');
            // element = typeof element === 'undefined' ? pupload.config.classname : element;
            // $(element, pupload.config.container).each(function() {
            $(element).each(function() {
                if ($(this).attr("initialized")) {
                    return true;
                }
                $(this).attr("initialized", true);
                var that = this;
                var id = $(this).prop("id");
                var url = $(this).data("url");
                var maxsize = $(this).data("maxsize");
                var mimetype = $(this).data("mimetype");
                var multipart = $(this).data("multipart");
                var multiple = $(this).data("multiple");
                //填充ID
                var input_id = $(that).data("input-id") ? $(that).data("input-id") : "";
                //预览ID
                var preview_id = $(that).data("preview-id") ? $(that).data("preview-id") : "";
                //上传URL
                url = url ? url : options.extend.import_url;
                //最大可上传文件大小
                maxsize = typeof maxsize !== "undefined" ? maxsize : options.extend.upload.maxsize;
                //文件类型
                mimetype = typeof mimetype !== "undefined" ? mimetype : options.extend.upload.mimetype;
                //请求的表单参数
                multipart = typeof multipart !== "undefined" ? multipart : options.extend.upload.multipart;
                //是否支持批量上传
                multiple = typeof multiple !== "undefined" ? multiple : options.extend.upload.multiple;
                var mimetypeArr = new Array();
                //支持后缀和Mimetype格式,以,分隔
                if (mimetype && mimetype !== "*" && mimetype.indexOf("/") === -1) {
                    var tempArr = mimetype.split(',');
                    for (var i = 0; i < tempArr.length; i++) {
                        mimetypeArr.push({
                            title: '文件',
                            extensions: tempArr[i]
                        });
                    }
                    mimetype = mimetypeArr;
                }
                //生成Plupload实例
                Upload.list[id] = new plupload.Uploader({
                    runtimes: 'html5,flash,silverlight,html4',
                    multi_selection: multiple, //是否允许多选批量上传
                    browse_button: id, // 浏览按钮的ID
                    container: $(this).parent().get(0), //取按钮的上级元素
                    flash_swf_url: '/assets/libs/plupload/js/Moxie.swf',
                    silverlight_xap_url: '/assets/libs/plupload/js/Moxie.xap',
                    filters: {
                        max_file_size: maxsize,
                        mime_types: mimetype,
                    },
                    url: url,
                    multipart_params: $.isArray(multipart) ? {} : multipart,
                    init: {
                        PostInit: Upload.events.onPostInit,
                        FilesAdded: Upload.events.onFileAdded,
                        BeforeUpload: Upload.events.onBeforeUpload,
                        UploadProgress: function(up, file) {
                            var button = up.settings.button;
                            $(button).prop("disabled", true).html("<i class='fa fa-upload'></i> " + '上传' + file.percent + "%");
                            Upload.events.onUploadProgress(up, file);
                        },
                        FileUploaded: function(up, file, info) {
                            var button = up.settings.button;
                            //还原按钮文字及状态
                            $(button).prop("disabled", false).html($(button).data("bakup-html"));
                            var ret = Upload.events.onUploadResponse(info.response, info, up, file);
                            file.ret = ret;
                            if (ret.code === 1) {
                                Upload.events.onUploadSuccess(up, ret, file);
                            } else {
                                Upload.events.onUploadError(up, ret, file);
                            }
                        },
                        UploadComplete: Upload.events.onUploadComplete,
                        Error: function(up, err) {
                            var button = up.settings.button;
                            $(button).prop("disabled", false).html($(button).data("bakup-html"));
                            var ret = {
                                code: err.code,
                                msg: err.message,
                                data: null
                            };
                            Upload.events.onUploadError(up, ret);
                        }
                    },
                    onUploadSuccess: onUploadSuccess,
                    onUploadError: onUploadError,
                    onUploadComplete: onUploadComplete,
                    button: that
                });
                Upload.list[id].init();
            });
        },
    }
};
//将Table渲染至全局
window.Table = Table;
