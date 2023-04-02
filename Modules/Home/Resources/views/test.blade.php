@extends('home::layouts.home')

@section('head_css')
<style>
.docs-menu-box {
    max-width:220px;
    width: 100%;
    float:left;
    position:relative;
    /*box-shadow:0 1px 5px #333;*/
    border: 1px solid rgba(0,0,0,.1);

}
.docs-menu-box .docs-menu-box-footer,.docs-menu-box .docs-menu-box-header {
    width:100%;
    height:50px;
    padding-left:22px;
    float:left;
    line-height:50px;
    font-weight:600;
    color:#f0f0f0;
    background:#414956
}
.docs-menu-box ul {
    margin:0;
    padding:0;
    list-style:none
}
.docs-menu-box ul li {
    width:100%;
    display:block;
    float:left;
    position:relative
}
.docs-menu-box ul li a {
    width:100%;
    padding:14px 22px;
    float:left;
    text-decoration:none;
    color:#f0f0f0;
    font-size:13px;
    background:#414956;
    white-space:nowrap;
    position:relative;
    overflow:hidden;
}
.docs-menu-box>ul>li.active>a,.docs-menu-box>ul>li:hover>a {
    color:#fff;
    background:#3b424d
}
.docs-menu-box>ul>li>a {
    border-bottom:solid 1px #3b424d
}
.docs-menu-box ul li a i {
    width:34px;
    float:left;
    line-height:18px;
    font-size:16px;
    text-align:left
}
.docs-menu-box ul ul.submenu,.docs-menu-box ul ul.submenu li ul.submenu {
    width:100%;
    display:none;
    position:static
}
.docs-menu-box ul ul.submenu li {
    clear:both;
    width:100%
}
.docs-menu-box ul ul.submenu li a {
    width:100%;
    float:left;
    font-size:11px;
    background:#383838;
    border-top:none;
    position:relative;
    border-left:solid 6px transparent;
}
.docs-menu-box.white ul ul.submenu li a{
    background: #fff;
    color: #555!important;
}
.docs-menu-box ul ul.submenu li:hover>a {
    border-left-color:#414956
}
.docs-menu-box ul ul.submenu>li>a {
    padding-left:30px
}
.docs-menu-box ul ul.submenu>li>ul.submenu>li>a {
    padding-left:45px
}
.docs-menu-box ul ul.submenu>li>ul.submenu>li>ul.submenu>li>a {
    padding-left:60px
}
.docs-menu-box ul li .docs-menu-box-label,.docs-menu-box ul ul.submenu li .docs-menu-box-label {
    min-width:20px;
    padding:1px 2px 1px 1px;
    position:absolute;
    right:18px;
    top:14px;
    font-size:11px;
    font-weight:800;
    color:#555;
    text-align:center;
    line-height:18px;
    background:#f0f0f0;
    border-radius:100%
}
.docs-menu-box ul ul.submenu li .docs-menu-box-label {
    top:12px
}
.white.docs-menu-box .docs-menu-box-footer,.white.docs-menu-box .docs-menu-box-header,.white.docs-menu-box ul li a {
    background:#fff;
    color:#555
}
.white.docs-menu-box>ul>li.active>a,.white.docs-menu-box>ul>li:hover>a {
    background:#f0f0f0
}
.white.docs-menu-box>ul>li>a {
    border-bottom-color:#f0f0f0
}
.white.docs-menu-box ul ul.submenu li:hover>a {
    border-left-color:#f0f0f0
}
.white.docs-menu-box ul ul.submenu li a {
    color:#f0f0f0
}
.black.docs-menu-box .docs-menu-box-footer,.black.docs-menu-box .docs-menu-box-header,.black.docs-menu-box ul li a {
    background:#292929
}
.black.docs-menu-box>ul>li.active>a,.black.docs-menu-box>ul>li:hover>a {
    background:#222
}
.black.docs-menu-box>ul>li>a {
    border-bottom-color:#222
}
.black.docs-menu-box ul ul.submenu li:hover>a {
    border-left-color:#222
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
    right: 9px;
    top: 20px;
    transform: rotate(45deg);
}

.custom-down-arrow {
    border-top: 2px solid;
    border-right: 2px solid;
    border-color: #ccc;
    content: '';
    position: absolute;
    right: 7px;
    top: 20px;
    transform: rotate(135deg);
}
 /*定义左边布局*/
.docs-box{
    display:flex;
    width: 100%;
}
.docs-left-menu{
    flex:0 0 220px;
    /*margin-bottom: 50px;*/
    overflow: auto;
    height: calc( 100vh - 102px );
    background-color: #fff;

    /* 设置左右方向显示隐藏 */
    /*height: 100%;*/
    z-index: 100;
    transition: all 0.5s;
}
@media screen and (max-width:768px){
    /*.docs-left-menu{*/
    /*    flex:0 0 0px;*/
    /*}*/
    .docs-left-menu{
        position: absolute;
    }
    .docs-menu-btn-nav{
        position: absolute!important;
        top: 30vh!important;
        transition: all 0.5s;
    }
    .show-menu.docs-menu-btn-nav{
        left: 220px!important;
    }
    .top-navigation .container{
        padding: 0;
    }
}
@media screen and (min-width:768px){
    .docs-menu-btn-nav{
        /*display: none;*/
    }
}
.docs-right-content{
    flex: 1 1;
    border: 1px solid green;
    padding: 0 20px;
}
.docs-menu-btn-nav.show-menu {
    background-image: url({{ asset('static/images/nuv_03.png') }});
}
.docs-menu-btn-nav.hidden-menu {
    background-image: url({{ asset('static/images/nuv_04.png') }});
}

.docs-menu-btn-nav {
    position: sticky;
    left: 0;
    top: 40%;
    width: 25px;
    height: 93px;
    border-style: none;
    background: url({{ asset('static/images/nuv_03.png') }}) no-repeat transparent;
    outline: none;
    border-left: 1px solid #fff;
    margin-left: -1px;
    z-index: 100;
}


</style>
@endsection

@section('page_js')
    <!-- 页面中引入page js -->
    <script type="text/javascript">
        jQuery(document).ready(function () {

            $(".docs-menu-box a").siblings("ul.submenu").each(function(index, domele) {
                // 追加一个有子元素的箭头元素
                $(domele).siblings("a").append('<span class="custom-arrow custom-right-arrow"></span>');
            })
            // 点击时候修改箭头方向和切换展示子元素
            jQuery(".docs-menu-box a").click(function () {
                var submenu = $(this).siblings("ul.submenu");
                if (submenu.length > 0) {
                    var submenuShow = submenu.css("display");
                    // submenu.css("display", submenuShow == "block" ? "none" : "block");
                    // 展开收起效果
                    submenuShow == "block"? $(submenu).slideUp(600):$(submenu).slideDown(600);

                    if (submenuShow == "block") {
                        $(this).find(".custom-arrow").removeClass("custom-down-arrow");
                        $(this).find(".custom-arrow").addClass("custom-right-arrow");
                    } else {
                        $(this).find(".custom-arrow").removeClass("custom-right-arrow");
                        $(this).find(".custom-arrow").addClass("custom-down-arrow");
                    }
                }
            });

            function bindClickDocsMenu(){
                // open hidden
                $('.hidden-menu.docs-menu-btn-nav').off().on('click', function (e) {
                    console.log('open')
                    $('.docs-left-menu').css({ 'flex':'0 0 220px','left':'0' })
                    $(this).css({'left':'220px' })
                    $(this).removeClass('hidden-menu').addClass('show-menu')
                    // $('.docs-menu-box').css({ 'left': 0 })
                    // document.body.style.overflow = 'hidden' //禁止滑动
                    bindClickDocsMenu();
                })
                $('.show-menu.docs-menu-btn-nav').off().on('click', function (e) {
                    console.log('hidden')
                    // $('.docs-menu-box').css({ 'left': -50 + 'vw' })
                    $('.docs-left-menu').css({ 'flex':'0 0 0px','left':'-220px' })
                    $(this).css({ 'left':'0' })
                    $(this).removeClass('show-menu').addClass('hidden-menu')
                    // document.body.style.overflow = 'visible'  //关闭抽屉,解除禁止滑动
                    bindClickDocsMenu();
                })
            }
            bindClickDocsMenu();
        });
    </script>
@endsection

@section('content')
    <div class="docs-box docs-left-show-menu">
        <div class="docs-left-menu">
            {{-- white 或者默认黑色 --}}
            <div class="docs-menu-box white">
                <div class="docs-menu-box-header">
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
                        <span class="docs-menu-box-label">
							12
						</span>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-cog">
                            </i>
                            Services
                            <span class="docs-menu-box-label">+1</span>
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
                                <span class="docs-menu-box-label">
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
                <div class="docs-menu-box-footer test-close">
                    Footer  test Close
                </div>
            </div>
        </div>
        <button type="button" class="show-menu docs-menu-btn-nav"></button>
        <div class="docs-right-content" style="height: 8000px">
            <div class="row">
                content-row
                https://www.jq22.com/yanshi22165
            </div>
        </div>
    </div>
@endsection
