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

    <link href="{{ asset('static/inspinia/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/inspinia/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <link href="{{ asset('static/inspinia/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/inspinia/css/style.min.css') }}" rel="stylesheet">

    @section('head_css')
        <!-- 页面中引入page css -->
    @show
</head>

<body>

<div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            @include('admin::layouts.admin.left_metis_menu')
        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="border-bottom">
            @include('admin::layouts.admin.top_nav')
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            @yield('content')
        </div>
        <div class="footer">
            <div class="float-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> {{ empty( $sys['name'] ) ? '威四方': $sys['name'] }}  &copy; 2023-<script>document.write(new Date().getFullYear())</script>
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

@section('page_js')
    <!-- 页面中引入page js -->
@show

<script>
    $(document).ready(function() {

    });
</script>
</body>

</html>
