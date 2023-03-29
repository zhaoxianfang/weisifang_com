@extends('home::layouts.home')
<style>
    .jquery-accordion-menu, .jquery-accordion-menu * {
        font-family: 'Open Sans', sans-serif;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        outline: 0
    }

    .jquery-accordion-menu {
        width: 100%;
        float: left;
        position: relative;
        box-shadow: 0 20px 20px #333;
        margin-bottom: 50px;
    }

    .jquery-accordion-menu .jquery-accordion-menu-footer, .jquery-accordion-menu .jquery-accordion-menu-header {
        width: 100%;
        height: 50px;
        padding-left: 22px;
        float: left;
        line-height: 50px;
        font-weight: 600;
        color: #f0f0f0;
        background: #414956
    }

    .jquery-accordion-menu ul {
        margin: 0;
        padding: 0;
        list-style: none
    }

    .jquery-accordion-menu ul li {
        width: 100%;
        display: block;
        float: left;
        position: relative
    }

    .jquery-accordion-menu ul li a {
        width: 100%;
        padding: 14px 22px;
        float: left;
        text-decoration: none;
        color: #f0f0f0;
        font-size: 13px;
        background: #414956;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
        -o-transition: color .2s linear, background .2s linear;
        -moz-transition: color .2s linear, background .2s linear;
        -webkit-transition: color .2s linear, background .2s linear;
        transition: color .2s linear, background .2s linear
    }

    .jquery-accordion-menu li.active > a, .jquery-accordion-menu li:hover > a {
        color: #fff;
        background: #3b424d;
    }

    .jquery-accordion-menu li.active > a {
        border-left: solid 6px seagreen !important;
        border-left-color: seagreen !important;
    }

    .jquery-accordion-menu > ul > li > a {
        border-bottom: solid 1px #3b424d
    }

    .jquery-accordion-menu ul li a i {
        width: 34px;
        float: left;
        line-height: 18px;
        font-size: 16px;
        text-align: left
    }

    .jquery-accordion-menu .submenu-indicator {
        float: right;
        right: 22px;
        position: absolute;
        line-height: 19px;
        font-size: 20px;
        -o-transition: transform .3s linear;
        -moz-transition: transform .3s linear;
        -webkit-transition: transform .3s linear;
        -ms-transition: transform .3s linear
    }

    .jquery-accordion-menu ul ul.submenu .submenu-indicator {
        line-height: 16px
    }

    .jquery-accordion-menu .submenu-indicator-minus > .submenu-indicator {
        -ms-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg)
    }

    .jquery-accordion-menu ul ul.submenu, .jquery-accordion-menu ul ul.submenu li ul.submenu {
        width: 100%;
        display: none;
        position: static
    }

    .jquery-accordion-menu ul ul.submenu li {
        clear: both;
        width: 100%
    }

    .jquery-accordion-menu ul ul.submenu li a {
        width: 100%;
        float: left;
        font-size: 11px;
        background: #383838;
        border-top: none;
        position: relative;
        border-left: solid 6px transparent;
        -o-transition: border .2s linear;
        -moz-transition: border .2s linear;
        -webkit-transition: border .2s linear;
        transition: border .2s linear
    }

    .jquery-accordion-menu ul ul.submenu li:hover > a {
        border-left-color: #414956
    }

    .jquery-accordion-menu ul ul.submenu > li > a {
        padding-left: 30px
    }

    .jquery-accordion-menu ul ul.submenu > li > ul.submenu > li > a {
        padding-left: 45px
    }

    .jquery-accordion-menu ul ul.submenu > li > ul.submenu > li > ul.submenu > li > a {
        padding-left: 60px
    }

    .jquery-accordion-menu ul li .jquery-accordion-menu-label, .jquery-accordion-menu ul ul.submenu li .jquery-accordion-menu-label {
        min-width: 20px;
        padding: 1px 2px 1px 1px;
        position: absolute;
        right: 26px;
        top: 14px;
        font-size: 11px;
        font-weight: 800;
        color: #555;
        text-align: center;
        line-height: 18px;
        background: #f0f0f0;
        border-radius: 100%
    }

    .jquery-accordion-menu ul ul.submenu li .jquery-accordion-menu-label {
        top: 12px
    }

    .ink {
        display: block;
        position: absolute;
        background: rgba(255, 255, 255, .3);
        border-radius: 100%;
        -webkit-transform: scale(0);
        -moz-transform: scale(0);
        -ms-transform: scale(0);
        -o-transform: scale(0);
        transform: scale(0)
    }

    .animate-ink {
        -webkit-animation: ripple .5s linear;
        -moz-animation: ripple .5s linear;
        -ms-animation: ripple .5s linear;
        -o-animation: ripple .5s linear;
        animation: ripple .5s linear
    }

    @-webkit-keyframes ripple {
        100% {
            opacity: 0;
            -webkit-transform: scale(2.5)
        }
    }

    @-moz-keyframes ripple {
        100% {
            opacity: 0;
            -moz-transform: scale(2.5)
        }
    }

    @-o-keyframes ripple {
        100% {
            opacity: 0;
            -o-transform: scale(2.5)
        }
    }

    @keyframes ripple {
        100% {
            opacity: 0;
            transform: scale(2.5)
        }
    }

    .blue.jquery-accordion-menu .jquery-accordion-menu-footer, .blue.jquery-accordion-menu .jquery-accordion-menu-header, .blue.jquery-accordion-menu ul li a {
        background: #4A89DC
    }

    .blue.jquery-accordion-menu > ul > li.active > a, .blue.jquery-accordion-menu > ul > li:hover > a {
        background: #3e82da
    }

    .blue.jquery-accordion-menu > ul > li > a {
        border-bottom-color: #3e82da
    }

    .blue.jquery-accordion-menu ul ul.submenu li:hover > a {
        border-left-color: #3e82da
    }

    .green.jquery-accordion-menu .jquery-accordion-menu-footer, .green.jquery-accordion-menu .jquery-accordion-menu-header, .green.jquery-accordion-menu ul li a {
        background: #03A678
    }

    .green.jquery-accordion-menu > ul > li.active > a, .green.jquery-accordion-menu > ul > li:hover > a {
        background: #049372
    }

    .green.jquery-accordion-menu > ul > li > a {
        border-bottom-color: #049372
    }

    .green.jquery-accordion-menu ul ul.submenu li:hover > a {
        border-left-color: #049372
    }

    .red.jquery-accordion-menu .jquery-accordion-menu-footer, .red.jquery-accordion-menu .jquery-accordion-menu-header, .red.jquery-accordion-menu ul li a {
        background: #ED5565
    }

    .red.jquery-accordion-menu > ul > li.active > a, .red.jquery-accordion-menu > ul > li:hover > a {
        background: #DA4453
    }

    .red.jquery-accordion-menu > ul > li > a {
        border-bottom-color: #DA4453
    }

    .red.jquery-accordion-menu ul ul.submenu li:hover > a {
        border-left-color: #DA4453
    }

    .white.jquery-accordion-menu .jquery-accordion-menu-footer, .white.jquery-accordion-menu .jquery-accordion-menu-header, .white.jquery-accordion-menu ul li a {
        background: #fff;
        color: #555
    }

    .white.jquery-accordion-menu > ul > li.active > a, .white.jquery-accordion-menu > ul > li:hover > a {
        background: #f0f0f0
    }

    .white.jquery-accordion-menu > ul > li > a {
        border-bottom-color: #f0f0f0
    }

    .white.jquery-accordion-menu ul ul.submenu li:hover > a {
        border-left-color: #f0f0f0
    }

    .white.jquery-accordion-menu ul ul.submenu li a {
        color: #f0f0f0
    }

    .white.jquery-accordion-menu > ul > li > a > .ink {
        background: rgba(0, 0, 0, .1)
    }

    .black.jquery-accordion-menu .jquery-accordion-menu-footer, .black.jquery-accordion-menu .jquery-accordion-menu-header, .black.jquery-accordion-menu ul li a {
        background: #292929
    }

    .black.jquery-accordion-menu > ul > li.active > a, .black.jquery-accordion-menu > ul > li:hover > a {
        background: #222
    }

    .black.jquery-accordion-menu > ul > li > a {
        border-bottom-color: #222
    }

    .black.jquery-accordion-menu ul ul.submenu li:hover > a {
        border-left-color: #222
    }

</style>
<style>
/*自定义箭头*/
    .custom-arrow{
        float: right;
        right: 22px;
        position: absolute;
        line-height: 19px;
        font-size: 20px;
        width: 8px;
        height: 8px;
    }
    .custom-right-arrow {
        border-top: 2px solid;
        border-right: 2px solid;
        border-color: #ccc;
        content: '';
        position: absolute;
        right: 11px;
        top: 20px;
        transform: rotate(45deg);
    }

    .custom-down-arrow {
        border-top: 2px solid;
        border-right: 2px solid;
        border-color: #ccc;
        content: '';
        position: absolute;
        right: 11px;
        top: 20px;
        transform: rotate(135deg);
    }
</style>
@section('page_js')
    <!-- 页面中引入page js -->
    <script type="text/javascript">
        jQuery(document).ready(function () {

            $(".jquery-accordion-menu a").siblings("ul.submenu").each(function(index, domele) {
                // 追加一个有子元素的箭头元素
                $(domele).siblings("a").append('<span class="custom-arrow custom-right-arrow"></span>');
            })
            // 点击时候修改箭头方向和切换展示子元素
            jQuery(".jquery-accordion-menu a").click(function () {
                var submenu = $(this).siblings("ul.submenu");
                if (submenu.length > 0) {
                    var submenuShow = submenu.css("display");
                    submenu.css("display", submenuShow == "block" ? "none" : "block");

                    if (submenuShow == "block") {
                        $(this).find(".custom-arrow").removeClass("custom-down-arrow");
                        $(this).find(".custom-arrow").addClass("custom-right-arrow");
                    } else {
                        $(this).find(".custom-arrow").removeClass("custom-right-arrow");
                        $(this).find(".custom-arrow").addClass("custom-down-arrow");
                    }
                }
            });
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div id="jquery-accordion-menu" class="jquery-accordion-menu">
                <div class="jquery-accordion-menu-header">
                    Header
                </div>
                <ul>
                    <li class="active">
                        <a href="#">
                            <i class="fa fa-home">
                            </i>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-glass">
                            </i>
                            Events
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-file-image-o">
                            </i>
                            Gallery
                        </a>
                        <span class="jquery-accordion-menu-label">
							12
						</span>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-cog">
                            </i>
                            Services
                            <span class="jquery-accordion-menu-label">+1</span>
                            <span class="custom-arrow custom-right-arrow"></span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="#">
                                    Web Design
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Hosting
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Design
                                </a>
                                <ul class="submenu">
                                    <li>
                                        <a href="#">
                                            Graphics
                                        </a>
                                    </li>
                                    <li class="active">
                                        <a href="#">
                                            Vectors -active
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            Photoshop
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            Fonts
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    Consulting
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-newspaper-o">
                            </i>
                            News
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-suitcase">
                            </i>
                            Portfolio
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="#">
                                    Web Design
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Graphics
                                </a>
                                <span class="jquery-accordion-menu-label">
									10
								</span>
                            </li>
                            <li>
                                <a href="#">
                                    Photoshop
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Programming
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-user">
                            </i>
                            About
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-envelope">
                            </i>
                            Contact
                        </a>
                    </li>
                </ul>
                <div class="jquery-accordion-menu-footer">
                    Footer
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            content
        </div>
    </div>
@endsection
