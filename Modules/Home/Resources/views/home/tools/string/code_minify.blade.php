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
<div class="article">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h1>JS、CSS代码压缩 <small>Code Minify</small></h1>
                    <div class="ibox-tools">
                        <span class="label label-warning-light float-right">:)</span>
                    </div>
                </div>
                <div class="ibox-content" style="padding: 0 20px;">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="m-t-none m-b">原代码：
                                <select class="form-control" id="code_type_select" style="width: 150px;float: right;margin-right: -15px;">
                                    <option value="auto" >自动识别</option>
                                    <option value="js" >JS 代码</option>
                                    <option value="css" >CSS代码</option>
                                </select>
                            </h3>
                            <form role="form" method="post">
                                <input type="hidden" name="code_type" value="auto" id="code_type">
                                <div class="form-group row">
                                    <textarea class="form-control" name="code" placeholder="在此填入未压缩的代码..." rows="20" required></textarea>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success dim btn-block" id="submit_btn"><i class="fa fa-suitcase"></i> &nbsp&nbsp; 开始压缩</button>
                                </div>
                            </form>
                            <p>使用说明：将你的<code>js</code> 或 <code>css</code> 代码放在左侧输入框中，然后点击「开始压缩」按钮进行压缩。</p>
                        </div>
                        <div class="col-sm-6">
                            <h3 class="m-t-none m-b">压缩后的代码</h3>
                            <div class="text-center">
                                <div class="form-group row">
                                    <textarea class="form-control" id="preview_string" placeholder="压缩后的代码..." rows="20"></textarea>
                                </div>
                            </div>
                            <p>原始大小：<text id="old_len"></text></p>
                            <p>压缩大小：<text id="new_len"></text></p>
                            <p>压缩率：<text id="minify_ratio"></text></p>
                        </div>
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
            $('code_type').val($('code_type_select').val());
            $('#submit_btn').attr("disabled",true).html('<i class="fa fa-spinner"></i> &nbsp&nbsp; 正在压缩中...');
        }
        function form_after(res) {
            $('#submit_btn').attr("disabled",false).html('<i class="fa fa-suitcase"></i> &nbsp&nbsp; 开始压缩');
            $('#preview_string').val(res.data.min_str);
            $('#minify_ratio').text(res.data.minify_ratio);
            $('#old_len').text(res.data.old_len);
            $('#new_len').text(res.data.new_len);
        }


</script>
@endsection
