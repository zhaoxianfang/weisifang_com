<!-- 继承home模板 -->
@extends('home::layouts.home')

<!-- 设置页面标题 -->
@section('title', 'JS、CSS在线压缩 Code Minify')

<!-- 引入当前页面的css样式文件 -->
@section('head_css')
    @parent
    <!-- 当前页面中的css -->

    <link href="{{ asset('static/inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/inspinia/css/plugins/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">

    <link href="{{ asset('static/inspinia/css/plugins/colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet">


    <link href="{{ asset('static/inspinia/css/plugins/touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet">
@endsection

<!-- 页面内容 -->
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h3>JS、CSS代码压缩 <small>Code Minify</small></h3>
                    <div class="ibox-tools">
                        <span class="label label-warning-light float-right">:)</span>
                    </div>
                </div>
                <div class="ibox-content" style="padding: 0 20px;">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5 class="m-t-none m-b">原代码：</h5>
                            <form role="form" method="post">
                                <div class="form-group row">
                                    <textarea class="form-control" name="code" placeholder="在此填入未压缩的代码..." rows="20" required></textarea>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success  dim btn-block" ><i class="fa fa-upload"></i> &nbsp&nbsp; 开始压缩</button>
                                </div>
                            </form>
                            <p>使用手册：</p>
                        </div>
                        <div class="col-sm-6">
                            <h5 class="m-t-none m-b">压缩后的代码</h5>
                            <div class="text-center">
                                <div class="form-group row">
                                    <textarea class="form-control" id="preview_string" placeholder="压缩后的代码..." rows="20"></textarea>
                                </div>
                            </div>
                            <p>压缩率：<text id="minify_ratio"></text></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('page_js')
    <!-- 页面中引入page js -->


<script type="text/javascript">

        function form_before(res) {
            // console.log(res)
            // $('#images_none').show()
            // $('#compressor_res_box').hide()
        }
        function form_after(res) {
            $('#preview_string').val(res.data.min_str);
            $('#minify_ratio').text(res.data.minify_ratio);
        }


</script>
@endsection
