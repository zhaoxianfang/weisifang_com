<!-- 继承home模板 -->
@extends('home::layouts.home')

<!-- 设置页面标题 -->
@section('title', 'Unicode转码')

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
                    <h1>Unicode转换 <small>Unicode encoding conversion</small></h1>
                    <div class="ibox-tools">
                        <span class="label label-warning-light float-right">:)</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="m-t-none m-b">Unicode转中文汉字、ASCII转换Unicode ：</h3>
                            <div role="form" method="post" class="unbind-form" >
                                <div class="form-group row">
                                    <textarea class="form-control" id="json_old_str" name="code" placeholder="此处填入unicode字符串" rows="20" required></textarea>
                                </div>
                            </div>
                            <p>使用说明：在左侧输入区填写 Unicode 字符串 或 在右侧填写汉字字符串 都会自动完成转换！</p>
                        </div>
                        <div class="col-sm-6">
                            <h3 class="m-t-none m-b">汉字转Unicode：</h3>
                            <div class="text-center">
                                <div class="form-group row">
                                    <textarea class="form-control" id="preview_string" placeholder="此处填入汉字或其他普通字符串" rows="20"></textarea>
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

    // Unicode转中文汉字、ASCII转换Unicode
    function reconvert(str){
        str = str.replace(/(\\u)(\w{1,4})/gi,function($0){
            return (String.fromCharCode(parseInt((escape($0).replace(/(%5Cu)(\w{1,4})/g,"$2")),16)));
        });
        str = str.replace(/(&#x)(\w{1,4});/gi,function($0){
            return String.fromCharCode(parseInt(escape($0).replace(/(%26%23x)(\w{1,4})(%3B)/g,"$2"),16));
        });
        str = str.replace(/(&#)(\d{1,6});/gi,function($0){
            return String.fromCharCode(parseInt(escape($0).replace(/(%26%23)(\d{1,6})(%3B)/g,"$2")));
        });
        return str;
    }
    // unicode 转中文
    function toZhCN(str) {
        var res = [];
        for ( var i=0; i<str.length; i++ ) {
            res[i] = ( "00" + str.charCodeAt(i).toString(16) ).slice(-4);
        }
        return "\\u" + res.join("\\u");
    }

    $(function(){
        $('#json_old_str').bind('input propertychange', function(){
          if($(this).val() != ""){
            var t = reconvert($(this).val());
            $('#preview_string').val(t);
          }else{
            $('#preview_string').val('');
          };
        });

        $('#preview_string').bind('input propertychange', function(){
          if($(this).val() != ""){
            var t = toZhCN($(this).val());
            $('#json_old_str').val(t);
          }else{
            $('#json_old_str').val('');
          };
        });
    });
</script>
@endsection
