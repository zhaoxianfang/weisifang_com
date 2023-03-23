<!-- 继承home模板 -->
@extends('home::layouts.home')

<!-- 设置页面标题 -->
@section('title', 'json字符串格式化')

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
                    <h1>json字符串格式化 <small>JSON string formatting</small></h1>
                    <div class="ibox-tools">
                        <span class="label label-warning-light float-right">:)</span>
                    </div>
                </div>
                <div class="ibox-content" style="padding: 0 20px;">
                    <div class="row">
                        <div class="col-sm-6 b-r">
                            <h3 class="m-t-none m-b">JSON 字符串：</h3>
                            <div role="form" method="post" class="unbind-form" >
                                <div class="form-group row">
                                    <textarea class="form-control" id="json_old_str" name="code" placeholder="在此填入json字符串" rows="20" required></textarea>
                                </div>
                            </div>
                            <p>使用手册：在左侧输入区输入字符串后会自动完成转换！</p>
                        </div>
                        <div class="col-sm-6">
                            <h3 class="m-t-none m-b">格式化后的JSON：</h3>
                            <div class="text-center">
                                <div class="form-group row">
                                    <textarea class="form-control" id="preview_string" placeholder="格式化后的json" rows="20"></textarea>
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
    $(function(){
        $('#json_old_str').bind('input propertychange', function(){
          if($(this).val() != ""){
            var t = my.formatJson($(this).val());
            $('#preview_string').val(t);
          }else{
            $('#preview_string').val('');
          };
        });
        $('#preview_string').bind('input propertychange', function(){
            if($(this).val() != ""){
                var jsonStr = JSON.stringify($.parseJSON($(this).val()));
                $('#json_old_str').val(jsonStr);
            }else{
                $('#json_old_str').val('');
            };
        });
    });
</script>
@endsection
