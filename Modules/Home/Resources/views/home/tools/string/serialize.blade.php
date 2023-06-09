<!-- 继承home模板 -->
@extends('home::layouts.home')

<!-- 设置页面标题 -->
@section('title', '系列化和反系列化serialize、unserialize')

<!-- 引入当前页面的css样式文件 -->
@section('head_css')
    @parent
    <!-- 当前页面中的css -->

    <link href="{{ asset('static/inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/inspinia/css/plugins/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">

@endsection

<!-- 页面内容 -->
@section('content')
<div class="article">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h1>系列化和反系列化 <small>serialize、unserialize</small></h1>
                    <div class="ibox-tools">
                        <span class="label label-warning-light float-right">:)</span>
                    </div>
                </div>
                <div class="ibox-content" style="padding: 0 20px;">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="m-t-none m-b">系列化或反系列化前的代码：</h3>
                            <form role="form" method="post">
                                <div class="form-group row">
                                    <textarea class="form-control" name="code" placeholder="系列化或反系列化前的字符串..." rows="20" required></textarea>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success  dim btn-block" ><i class="fa fa-upload"></i> 系列化/反系列化</button>
                                </div>
                            </form>
                            <p>使用手册：</p>
                        </div>
                        <div class="col-sm-6">
                            <h3 class="m-t-none m-b">转换结果</h3>
                            <div class="text-center">
                                <div class="form-group row">
                                    <textarea class="form-control" id="preview_string" placeholder="转换后的结果..." rows="20"></textarea>
                                </div>
                            </div>
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
        }
        function form_after(res) {
            if(res.data.fn === "serialize"){
                $('#preview_string').val(res.data.result);
            }else{
                let str = my.formatJson(res.data.result);
                if(str.substr(0, 11) === "json数据格式有误:"){
                    $('#preview_string').val(res.data.result);
                }else{
                    $('#preview_string').val(str);
                }
            }
        }

    </script>
@endsection
