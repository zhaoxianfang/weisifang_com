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

        <div class="wrapper wrapper-content  animated fadeInRight article">

            <!-- <div class="row justify-content-md-center">
            </div> -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h1>json字符串格式化 <small>JSON string formatting</small></h1>
                            <div class="ibox-tools">
                                <!-- <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a> -->
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <!-- <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a> -->
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-sm-6 b-r">
                                    <h3 class="m-t-none m-b">JSON 字符串：</h3>


                                    <!-- <form role="form" method="post" class="unbind-form" > -->
                                    <div role="form" method="post" class="unbind-form" >


                                       <div class="form-group row">
                                            <textarea class="form-control" id="json_old_str" name="code" placeholder="在此填入json字符串" rows="20" required></textarea>
                                        </div>

                                        <!-- <div>
                                            <button type="submit" class="btn btn-success  dim btn-block" ><i class="fa fa-upload"></i> &nbsp;&nbsp; 格式化</button>
                                        </div> -->
                                    <!-- </form> -->
                                    </div>

                                    <p>使用手册：</p>

                                </div>
                                <div class="col-sm-6">
                                    <h3 class="m-t-none m-b">格式化后的JSON：</h3>
                                    <p class="text-center">

                                        <div class="form-group row">
                                            <textarea class="form-control" id="preview_string" placeholder="格式化后的json" rows="20"></textarea>
                                        </div>

                                    </p>
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
    });


</script>
@endsection
