<!-- 继承home模板 -->
@extends('home::layouts.home')

<!-- 设置页面标题 -->
@section('title', 'MySQL数据字典生成器')

<!-- 引入当前页面的css样式文件 -->
@section('head_css')
    @parent
    <!-- 当前页面中的css -->

    <link href="{{ asset('static/inspinia/css/plugins/ionRangeSlider/ion.rangeSlider.css') }}" rel="stylesheet">
    <style type="text/css">
        .custom-file-label::after {
            content: "选择文件";
        }
        .irs--flat .irs-bar {
            background-color: unset;
        }
    </style>

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
                            <h1>MySQL 数据字典生成器 <small>MySQL data dictionary generator</small></h1>
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
                                    <h3 class="m-t-none m-b">参数设置：</h3>


                                    <form role="form" method="post">


                                        <div class="form-group row has-success">
                                            <label class="col-lg-2 col-form-label">连接地址：<br /><strong>&nbsp;</strong></label>
                                            <div class="col-lg-10">
                                                <input type="label" name="db_host" placeholder="例如:8.8.8.8" class="form-control" required>
                                                <div class="m-b-sm">
                                                    <small ><strong>提示:</strong>(host)连接mysql的ip或者域名</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row has-success">
                                            <label class="col-lg-2 col-form-label">端口号：<br /><strong>&nbsp;</strong></label>
                                            <div class="col-lg-10">
                                                <input type="label" name="db_port" value="3306" placeholder="例如:3306" class="form-control" required>
                                                <div class="m-b-sm">
                                                    <small ><strong>提示:</strong>(port)连接mysql的端口号</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row has-success">
                                            <label class="col-lg-2 col-form-label">用户名<br /><strong>&nbsp;</strong></label>
                                            <div class="col-lg-10">
                                                <input type="label" name="db_username" value="root" placeholder="例如:root" class="form-control" required>
                                                <div class="m-b-sm">
                                                    <small ><strong>提示:</strong>连接mysql的用户名</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row has-success">
                                            <label class="col-lg-2 col-form-label">密码<br /><strong>&nbsp;</strong></label>
                                            <div class="col-lg-10">
                                                <input type="label" name="db_password" placeholder="连接mysql的密码" class="form-control" >
                                                <div class="m-b-sm">
                                                    <small ><strong>提示:</strong>连接mysql的密码</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row has-success">
                                            <label class="col-lg-2 col-form-label">数据库名<br /><strong>&nbsp;</strong></label>
                                            <div class="col-lg-10">
                                                <input type="label" name="db_database" placeholder="数据库名，例如：demo" class="form-control" required>
                                                <div class="m-b-sm">
                                                    <small ><strong>提示:</strong>数据库名称</small>
                                                </div>
                                            </div>
                                        </div>


                                        <div>
                                            <button type="submit" class="btn btn-success  dim btn-block" ><i class="fa fa-upload"></i> &nbsp;&nbsp; 开始生成数据库字典</button>
                                        </div>
                                    </form>

                                    <p>使用手册：</p>


                                </div>
                                <div class="col-sm-6">
                                    <h4>预览效果图</h4>
                                    <p>生成的代码:</p>
                                    <p class="text-center">
                                        <pre class="m-t-sm" id="preview_table_pre" style="max-height: 400px;"></pre>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-sm-12 b-r">
                                    <div id="preview_table"></div>
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

        }
        function form_after(res) {
            $('#preview_table_pre').text(res.data.table_str);
            $('#preview_table').html(res.data.table_str);
        }


</script>
@endsection
