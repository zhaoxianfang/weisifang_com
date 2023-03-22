/**
 * 鼠标右键菜单 和手势长按菜单
 * 2020-10-03 zhaoxianfang
 */
// 使用 demo
// $('body').mouseRight({
//   ele:'.nav-item',
//   menu: [
//     {
//       itemName: "添加",
//       icon:"fa fa-plus",
//       callback: function(dom_obj,ele) {
//         console.log(dom_obj['0'].innerText)
//         console.log(ele)
//     }
//     },{ // 空json 表示 hr 分隔符
//     }, {
//       itemName: "修改",
//       icon:"fa fa-files-o",
//       callback: function(dom_obj) {
//         console.log(dom_obj['0'].innerText)
//       }
//     }
// ]});
;
(function ($, window) {
    // 使用到的样式
    // .right-menu-shade{width:100%;height:100%;position:absolute;top:0;left:0;display:none}[class^="right-menu-wrap-ms-"]{list-style:none;position:absolute;top:0;left:0;padding:5px 0;min-width:80px;margin:0;display:none;font-family:"微软雅黑";font-size:14px;background-color:#fff;border:1px solid rgba(0,0,0,.15);box-sizing:border-box;border-radius:4px;-webkit-box-shadow:0 4px 12px rgba(0,0,0,0.1);-moz-box-shadow:0 4px 12px rgba(0,0,0,0.1);-ms-box-shadow:0 4px 12px rgba(0,0,0,0.1);-o-box-shadow:0 4px 12px rgba(0,0,0,0.1);box-shadow:0 4px 12px rgba(0,0,0,0.1);z-index:100}.right-menu-ms-item{height:30px;line-height:30px;text-align:center;cursor:pointer}.right-menu-ms-item:hover{background-color:#343a40;color:#fff}
    $.prototype.mouseRight = function (options, callback) {
        var defaults, settings, me, _this;
        me = this;
        defaults = {
            menu: [{}],
            ele: 'body',
            unicode:100001
        };
        var time_unicode = (new Date()).valueOf()
        // settings = $.extend({}, defaults, options,{unicode:time_unicode } );
        settings = $.extend({}, options,{unicode:time_unicode } );
        $(this).each(function () {
            (function () {
                var parentDiv = $('<div></div>');
                parentDiv.attr('class', 'right-menu-wrap-ms-' + (options.ele).substring(1)    );
                for (let i = 0; i < settings.menu.length; i++) {
                    var childDiv = $('<li></li>');
                    if(JSON.stringify(settings.menu[i]) == '{}'){
                        var childDiv1 = $('<hr style="margin: 0;height:1px;border:none;border-top:1px dashed #0066CC;" />');
                        childDiv1.appendTo(childDiv);
                        childDiv.appendTo(parentDiv);
                    }else{
                        childDiv.addClass('right-menu-ms-item');
                        var childDiv1 = $('<i></i>');
                        childDiv.attr('data-uni', time_unicode);
                        childDiv.attr('data-item', i);
                        childDiv1.addClass(settings.menu[i].icon);
                        childDiv1.attr('data-uni', time_unicode);
                        childDiv1.attr('data-item', i );
                        childDiv1.appendTo(childDiv);
                        childDiv.appendTo(parentDiv);
                        childDiv1.after('&nbsp;' + settings.menu[i].itemName)
                    }
                }
                parentDiv.prependTo('body');
                var parentShade = $('<div></div>');
                parentShade.attr('class', 'right-menu-shade');
                parentShade.prependTo('body')
            })();
            window.oncontextmenu = function () {
                return true
            };
            // 移动端监听长按事件
            var touchTimeOutEvent;
            $(settings.ele).on({
                touchstart: function(ele) {
                    settings.dom_obj = $(this);
                    var e = ele.hasOwnProperty('touches')?ele.touches[0]:ele.originalEvent.touches[0];
                    // 长按事件触发
                    touchTimeOutEvent = setTimeout(function(e,touchTimeOutEvent) {
                        touchTimeOutEvent = 0;
                        showRightMenu(e)
                    }, 400,e,touchTimeOutEvent);
                    // ele.preventDefault();
                },
                touchmove: function() {
                    clearTimeout(touchTimeOutEvent);
                    touchTimeOutEvent = 0;
                    hiddenRightMenu();
                },
                touchend: function() {
                    clearTimeout(touchTimeOutEvent);
                    hiddenRightMenu();
                    if (touchTimeOutEvent != 0) {
                        // 你点击了
                        return true;
                    }else{
                        touchTimeOutEvent = 0;
                        return false;
                    }

                }
            });
            // 显示右键菜单
            var showRightMenu = function (e) {
                $("[class^='right-menu-wrap-ms-']").css({
                    'display': 'none'
                });
                $('.right-menu-wrap-ms-'+(settings.ele).substring(1)).css({
                    'display': 'block',
                    'top': (e.pageY-8) + 'px',
                    'left': (e.pageX-8) + 'px'
                    // 'top': e.clientY + 'px',
                    // 'left': e.clientX + 'px'
                });
                $('.right-menu-shade').css({
                    'display': 'block',
                })
                // 遮罩出来后让body不可滚动
                $('body,html').addClass('right-mouse-not-scroll');
            };
            var hiddenRightMenu = function () {
                // 遮罩去掉之后body 可滚动
                $('body,html').removeClass('right-mouse-not-scroll');
            }
            $(settings.ele).mousedown(function (e) {
                if (e.which === 3) {
                    window.oncontextmenu = function () {
                        return false
                    };
                    settings.dom_obj = $(this)
                    showRightMenu(e);
                }else{
                    window.oncontextmenu = function () {
                        return true;
                    };
                    hiddenRightMenu();
                }
            });
            $('.right-menu-ms-item').click(function (e) {
                window.oncontextmenu = function () {
                    return true
                };
                var clickID = parseInt($(e.target).attr('data-item'));
                var dataUni = parseInt($(e.target).attr('data-uni'));
                for (let i = 0; i < settings.menu.length; i++) {
                    if (dataUni == settings.unicode && clickID == i) {
                        // 长按没有dom_obj属性
                        settings.menu[i].callback(settings.dom_obj, $(e.target));
                        $("[class^='right-menu-wrap-ms-']").css({
                            'display': 'none'
                        });
                        $('.right-menu-shade').css({
                            'display': 'none'
                        })
                    }
                }
                // 遮罩去掉之后body 可滚动
                $('body,html').removeClass('right-mouse-not-scroll');
            });
            $('.right-menu-shade').click(function () {
                window.oncontextmenu = function () {
                    return true
                };
                $("[class^='right-menu-wrap-ms-']").css({
                    'display': 'none'
                });
                $('.right-menu-shade').css({
                    'display': 'none'
                })
                // 遮罩去掉之后body 可滚动
                $('body,html').removeClass('right-mouse-not-scroll');
            })
            $(":not([class^='right-menu-wrap-ms-'])").click(function(e){
                window.oncontextmenu = function () {
                    return true
                };
                $("[class^='right-menu-wrap-ms-']").css({
                    'display': 'none'
                });
                $('.right-menu-shade').css({
                    'display': 'none'
                })
                // 遮罩去掉之后body 可滚动
                $('body,html').removeClass('right-mouse-not-scroll');
            });
        });
        return this
    }
})(jQuery, window)
