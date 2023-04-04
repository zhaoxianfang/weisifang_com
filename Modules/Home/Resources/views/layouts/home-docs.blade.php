<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @hasSection('title')
        <title> @yield('title','') | {{ $sys['name']??'威四方' }}</title>
    @endif
    @sectionMissing('title')
        <title>{{ $sys['name']??'威四方' }}</title>
    @endif

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <meta name="author" content="威四方" />
    <meta name="Copyright" content="威四方" />

    <meta name="keywords" content="威四方,weisifang,wsf,在线文档,企业服务,办公系统,photo,在线相册,仓库管理系统,OA,企业办公系统,CRM,客户关系管理,项目承接,ERP,企业资源计划,SCM,供应链管理系统,SRM,供应商关系管理,管理系统">
    <meta name="description" content="威四方是一个提供客户关系管理系统(CRM)、仓库管理系统(WMS)、采购系统(SRM)、在线文档(DOCS)，在线相册(PHOTOS)、企业办公系统(OA)、在线工具(tools)等的综合性服务平台;客户满意，服务至上。">

    <link href="{{ asset('static/inspinia/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/inspinia/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/inspinia/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/inspinia/css/style.min.css') }}" rel="stylesheet">

    <link href="{{ asset('static/inspinia/css/adminlte_nav.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/inspinia/css/home-docs-menu.min.css') }}" rel="stylesheet">

    @section('head_css')
        <!-- 页面中引入page css -->
    @show
</head>

<body class="top-navigation">

<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
            @include('home::layouts.home.top_nav')
        </div>
        <div class="docs-page-content animated fadeInRight">
            <div class="container">
                <div class="docs-box docs-left-show-menu">
                    <div class="docs-left-menu">
                        @include('home::layouts.home-docs.left-menu')
                    </div>
                    <button type="button" class="show-menu docs-menu-btn-nav"></button>
                    <div class="docs-right-content">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="float-right">
                <a href="https://beian.miit.gov.cn/#" target="_blank" style="color: #676a6c;"> <strong>{{ empty( $sys['beian'] ) ? '滇ICP备16003347号-2': $sys['beian'] }}&nbsp;</strong></a>
            </div>
            <div>
                <strong>Copyright</strong>{{ empty( $sys['name'] ) ? '威四方': $sys['name'] }}<span class="copyright-begin">  &copy; 2023-</span><script>document.write(new Date().getFullYear())</script>
            </div>
        </div>
    </div>
</div>

<!-- Mainly scripts -->
<script src="{{ asset('static/inspinia/js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('static/inspinia/js/popper.min.js') }}"></script>
<script src="{{ asset('static/inspinia/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('static/inspinia/js/plugins/metisMenu/jquery.metisMenu.min.js') }}"></script>
<script src="{{ asset('static/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- Custom and plugin javascript -->
<script src="{{ asset('static/inspinia/js/inspinia.min.js') }}"></script>
<script src="{{ asset('static/inspinia/js/plugins/pace/pace.min.js') }}"></script>

<script src="{{ asset('static/inspinia/js/top-nav.js') }}"></script>

<script src="{{ asset('static/libs/layer-3.5.1/layer.js') }}"></script>

<script src="{{ asset('static/libs/zxf/js/my.min.js') }}" my-init='true'></script>
<script src="{{ asset('static/inspinia/js/home-docs-menu.min.js') }}"></script>

@section('page_js')
    <!-- 页面中引入page js -->
@show

<script>
    $(document).ready(function() {

    });
</script>

</body>

</html>
