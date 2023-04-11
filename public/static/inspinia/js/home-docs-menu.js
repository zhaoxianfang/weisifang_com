// docs
$(function () {
    $(".docs-menu-box a").siblings("ul.submenu").each(function (index, domele) {
        // 追加一个有子元素的箭头元素
        $(domele).siblings("a").append("<span class=\"custom-arrow custom-right-arrow\"></span>");
    });
    // 点击时候修改箭头方向和切换展示子元素
    $(".docs-menu-box a").click(function () {
        var submenu = $(this).siblings("ul.submenu");
        if (submenu.length > 0) {
            var submenuShow = submenu.css("display");
            // submenu.css("display", submenuShow == "block" ? "none" : "block");
            // 展开收起效果
            if (submenuShow === "block") {
                $(submenu).slideUp(600);
            } else {
                $(submenu).slideDown(600);
            }

            if (submenuShow === "block") {
                $(this).find(".custom-arrow").removeClass("custom-down-arrow");
                $(this).find(".custom-arrow").addClass("custom-right-arrow");
            } else {
                $(this).find(".custom-arrow").removeClass("custom-right-arrow");
                $(this).find(".custom-arrow").addClass("custom-down-arrow");
            }
        }
    });

    function bindClickDocsMenu() {
        // open hidden
        $(".hidden-menu.docs-menu-btn-nav").off().on("click", function (e) {
            // $('.docs-left-menu').css({ 'flex':'0 0 220px','left':'0' })
            $(".docs-left-menu").css({"flex": "0 0 220px", "left": "unset"});
            $(this).css({"left": "220px"});
            $(this).removeClass("hidden-menu").addClass("show-menu");
            // $('.docs-menu-box').css({ 'left': 0 })
            // document.body.style.overflow = 'hidden' //禁止滑动
            bindClickDocsMenu();
        });
        $(".show-menu.docs-menu-btn-nav").off().on("click", function (e) {
            // $('.docs-menu-box').css({ 'left': -50 + 'vw' })
            $(".docs-left-menu").css({"flex": "0 0 0px", "left": "-220px"});
            $(this).css({"left": "0"});
            $(this).removeClass("show-menu").addClass("hidden-menu");
            // document.body.style.overflow = 'visible'  //关闭抽屉,解除禁止滑动
            bindClickDocsMenu();
        });
    }

    bindClickDocsMenu();

    // 使用 .container 或 .docs-page-content元素滚动事件触发到 .docs-right-content 上
    // $(document).on("mousewheel DOMMouseScroll", function (event) {
    //     let classList = event.target.className.split(/\s+/);
    //     if (classList.includes("bind-scroll-to-content")) {
    //         var targetDom = $(".docs-right-content");
    //         var scroll_top = targetDom.scrollTop();
    //         if (event.originalEvent.wheelDelta > 0 || event.originalEvent.detail < 0) {
    //             targetDom.scrollTop(scroll_top - 100);
    //         } else {
    //             targetDom.scrollTop(scroll_top + 100);
    //         }
    //     }
    // });
});
