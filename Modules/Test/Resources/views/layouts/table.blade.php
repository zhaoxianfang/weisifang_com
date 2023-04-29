<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title> Test Bootstrap Table</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('static/inspinia/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/inspinia/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- Toastr style -->
    <link href="{{ asset('static/inspinia/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">

    <link href="{{ asset('static/inspinia/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/inspinia/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/libs/zxf/css/inspinia.extend.css') }}" rel="stylesheet">

@section('table_css')
    <!-- 使用bootstrap-table 仅在有模板的时候才引入-->
    @hasSection('table_css')
        @include('test::layouts.table.table_css')
    @endif
@show

@section('head_css')
    <!-- 页面中引入page css -->
    @show

</head>

<body class="">
<div style="background-color: #fff;padding: 20px">
    @yield('content')
</div>


@include('test::layouts.table.script')

@section('table_js')
    <!-- 使用bootstrap-table 仅在有模板的时候才引入-->
    @hasSection('table_js')
        @include('test::layouts.table.table_js')
    @endif
@show


@section('page_js')
    <!-- 页面中引入page js -->
@show

</body>

</html>

