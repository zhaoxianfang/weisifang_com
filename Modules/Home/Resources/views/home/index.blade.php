@extends('home::layouts.home')

@section('title', '')
@section('module_name', 'docs')

@section('head_css')
    @parent
    <!-- 当前页面中的css -->
    <link href="{{ asset('static/apidoc/css/home.css') }}" rel="stylesheet">

    <link href="{{ asset('static/libs/zxf/css/my.min.css') }}" rel="stylesheet">
    <style>
        .contact-box{
            min-height: 190px;
        }
    </style>
@endsection

@section('content')

    <div class="">

        <ul class="sortable-list  agile-list" >
            <li class="success-element">
                人生在勤，不索何获。
            </li>
        </ul>

        <div class="row">
            <div class="col-lg-4">
                <div class="contact-box">
                    <a class="row" href="/docs">
                        <div class="col-4 center">
                            <div class="text-center">
                                <div class="rounded-circle m-t-xs img-fluid">
                                    <i class="fa fa-book fa-4x "></i>
                                </div>
                                <div class="m-t-xs font-bold">Docs</div>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3><strong>在线文档(Docs)</strong></h3>
                            <address>
                                <strong>简单、方便、可控</strong><br>
                                可以编写markdown文档、富文本文档、api接口文档；且支持设置文档权限、文档保护和成员管理。是一个简单易用、绿色的在线文档系统<br>
                            </address>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-box">
                    <a class="row" href="javascript:;">
                        <div class="col-4 center">
                            <div class="text-center">
                                <div class="rounded-circle m-t-xs img-fluid">
                                    <i class="fa fa-linode fa-4x "></i>
                                </div>
                                <div class="m-t-xs font-bold">WMS</div>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3><strong>仓库管理系统(WMS)</strong></h3>
                            <address>
                                <strong>简单、方便、可控</strong><br>
                                一键入库出库、出库单/入库单核对;库存管理、库存预警
                            </address>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-box">
                    <a class="row" href="javascript:;">
                        <div class="col-4 center">
                            <div class="text-center">
                                <div class="rounded-circle m-t-xs img-fluid">
                                    <i class="fa fa-puzzle-piece fa-4x "></i>
                                </div>
                                <div class="m-t-xs font-bold">企业办公</div>
                            </div>
                        </div>
                        <div class="col-8">
                            <h3><strong>企业办公(OA)</strong></h3>
                            <address>
                                <strong>简单、方便、高效</strong><br>
                                集合了供应商管理、供应链管理、客户关系管理、人事管理、企业资源规划管理等
                            </address>
                        </div>
                    </a>
                </div>
            </div>

        </div>



        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> 规划
                    </div>
                    <div class="panel-body">
                        <p> 我们正在逐步规划与完善以下几个系统：<br />
                            1、在线文档系统(Docs)<br />
                            2、仓库管理系统(WMS)<br />
                            3、客户关系管理系统(CRM)<br />
                            4、采购系统(SRM)<br />
                            5、在线办公系统(OA)<br />
                            6、企业资源规划管理(ERP)<br />
                            7、在线投票/问卷系统<br />
                            </p>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="ibox">
                    <div class="ibox-content">
                        <h2>
                            合作&建议
                        </h2>
                        <p>
                            非常感谢大家对本站的大力支持与贡献，我们诚挚邀请各单位和所有市民与我们进行密切合作，我们配套各项功能开发和定制服务，如果您有什么建议或者意见也可以与我们取得联系，邮箱:1748331509@qq.com
                        </p>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <!-- 页面中引入page js -->

    <script src="{{ asset('static/libs/zxf/js/my.min.js') }}" my-init='true' ></script>
    <script type="text/javascript">

    </script>
@endsection
