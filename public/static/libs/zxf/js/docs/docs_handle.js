$(function () {
    // 左侧新建目录
    $("#left_create_menu").click(function () {
        add_edit_menu(false, '/docs/menus/' + docapp.id + '/store');
    });
    // 左侧目录右键 编辑或者新增子项
    $('#docs-left-menus-box').mouseRight({
        ele: '.docs-menu-item',
        menu: [
            {
                itemName: "添加 子目录",
                icon: "wsf wsf-icon-zidian",
                callback: function (dom_obj, ele) {
                    add_edit_menu(true, '/docs/menus/' + dom_obj.data('id') + '/store_child');
                }
            }, {
                itemName: "编辑 目录",
                icon: "wsf wsf-icon-edit",
                callback: function (dom_obj) {
                    add_edit_menu(true, '/docs/menus/' + dom_obj.data('id') + '/update', dom_obj.data('name'), dom_obj.data('open_type'));
                }
            }, {
                itemName: "删除 目录",
                icon: "wsf wsf-icon-delete",
                callback: function (dom_obj) {
                    ajax_delete('确认删除菜单[' + dom_obj.data('name') + ']吗？', '/docs/menus/' + dom_obj.data('id') + '/delete');
                }
            }, {
                // 空json 表示 分隔符
            }, {
                itemName: "添加富文本文档",
                icon: "wsf wsf-icon-docs",
                callback: function (dom_obj, ele) {
                    window.location.href = "/docs/doc/" + dom_obj.data('id') + "/add/editor"
                }
            }, {
                itemName: "添加MD文档",
                icon: "wsf wsf-icon-markdown",
                callback: function (dom_obj, ele) {
                    window.location.href = "/docs/doc/" + dom_obj.data('id') + "/add/markdown"
                }
            }, {
                itemName: "添加API接口",
                icon: "wsf wsf-icon-api",
                callback: function (dom_obj, ele) {
                    window.location.href = "/docs/doc/" + dom_obj.data('id') + "/add/api"
                }
            }
        ]
    });

    $('#docs-left-menus-box').mouseRight({
        ele: '.menu-node',
        menu: [
            {
                itemName: "修改",
                icon: "wsf wsf-icon-edit",
                callback: function (dom_obj) {
                    console.log(dom_obj['0'].innerText)
                    window.location.href = "/docs/doc/" + dom_obj.data('id') + "/update"
                }
            }, {
                itemName: "删除",
                icon: "wsf wsf-icon-delete",
                callback: function (dom_obj) {
                    console.log(dom_obj['0'].innerText)
                    ajax_delete('确认删除文章[' + dom_obj['0'].innerText + ']吗？', '/docs/doc/' + dom_obj.data('id') + '/delete')
                }
            }
        ]
    });

    // 文档申请用户审核通过
    $(".docs_app_check_pass").click(function () {
        // console.log('通过', $(this).data('appid'), $(this).data('userid'));
        docs_app_user_join($(this).data('nickname'), '/docs/user/' + $(this).data('appid') + '/pass/' + $(this).data('userid') + '/role');
    });
    // 文档申请用户审核拒绝
    $(".docs_app_check_refuse").click(function () {
        ajax_delete('确认拒绝用户[' + $(this).data('nickname') + ']的申请吗？', '/docs/user/' + $(this).data('appid') + '/refuse/' + $(this).data('userid'))
    });
    // 踢出文档正式用户
    $(".docs_app_kick_out_user").click(function () {
        ajax_delete('确认把用户[' + $(this).data('nickname') + ']踢出本文档吗？', '/docs/user/' + $(this).data('appid') + '/kick_out/' + $(this).data('userid'))
    });

})
