@extends('test::layouts.table')

@section('table_css')
    <!-- 使用table -->
    @parent
@endsection


@section('head_css')
    @parent
    <!-- 当前页面中的css -->
@endsection


@section('content')
    <!-- 页面内容 -->
    <div class="row">
         <div class="callout callout-success">
            <h4>温馨提示!</h4>
            规则名称请仔细填写，包含大小写的区别
         </div>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <!-- <h3 class="box-title">权限列表</h3> -->
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div id="toolbar">

                        <button class="btn btn-info btn-primary btn-refresh btn-sm">
                            <i class="fa fa-refresh"></i>刷新
                        </button>

                        <button class="btn btn-success  btn-add btn-sm" data-area="full">
                            <i class="fa fa-plus"></i>添加
                        </button>

                        <button class="btn btn-success  btn-add-markdown btn-sm" >
                            <i class="fa fa-plus"></i>添加MD文章
                        </button>

                        <button class="btn btn-success  btn-edit btn-sm">
                            <i class="fa fa-pencil"></i>编辑
                        </button>

                        <button class="btn btn-danger btn-del btn-sm" >
                            <i class="fa fa-trash"></i>删除
                        </button>

                        <!-- table 监听 .btn-multi -->
                        <!-- table 监听 toolbarOptions（.toolbar-options） 是否display -->
                        <!-- <div class="dropdown btn-group btn-sm ">
                            <a class="toolbar-options  btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown"><i class="fa fa-cog"></i> 更多</a>
                            <ul class="dropdown-menu text-left" role="menu">
                                <li><a class="btn btn-link btn-multi btn-disabled disabled" href="javascript:;"  data-params="status=1"><i class="fa fa-eye"></i> 状态设置为正常</a></li>
                                <li><a class="btn btn-link btn-multi btn-disabled disabled" href="javascript:;"  data-params="status=0"><i class="fa fa-eye"></i> 状态设置为停用</a></li>
                                <li><a class="btn btn-link btn-multi btn-disabled disabled" href="javascript:;"  data-params="ismenu=1"><i class="fa fa-eye"></i> 设置为菜单</a></li>
                                <li><a class="btn btn-link btn-multi btn-disabled disabled" href="javascript:;"  data-params="ismenu=0"><i class="fa fa-eye"></i> 设置为不是菜单</a></li>
                            </ul>
                        </div> -->


                    </div>
                    <table id="table" class="table table-bordered table-hover"
                           data-operate-del="{:$auth->check('system/authrule/del')}"
                           data-operate-edit="{:$auth->check('system/authrule/edit')}">
                    </table>
                </div>

                <!-- /. box -->
            </div>
        </div>
    </div>
@endsection


@section('table_js')
    <!-- 使用table -->
    @parent
@endsection


@section('page_js')
    <!-- 引入当前页面的js文件 -->
    <script>
        $(function () {

            // 导入按钮事件
            $('#toolbar').on('click', '.btn-add-markdown', function() {
                console.log('markdown',$.fn.bootstrapTable.defaults.extend.add_markdown_url,)
                // var searchData = $('.form-commonsearch').serializeJsonObject();
                // console.log(my.jsonToUrl(searchData))
                // console.log(searchData)
                // window.open('/admin/poster/excel'+encodeURI(my.jsonToUrl(searchData)))
                let url = $.fn.bootstrapTable.defaults.extend.add_markdown_url
                var options = table.bootstrapTable('getOptions');
                // my.open(url, '新增markdown 文档', options.layerOptions || $(this).data());
                my.open(url, '新增markdown 文档', {area:["100%","100%"]});
            });

            //初始化一些操作（非必须） 该方法一定要写在 Table.api.init 之前
            // 初始化表格参数配置
            Table.api.init({
                pk:'id',
                sortName:'id',
                extend: {
                    index_url: '/test/table/get_list',
                    add_url: '/admin/article.index/add',
                    add_markdown_url: '/admin/article.index/add_markdown',
                    edit_url: '/admin/article.index/edit',
                    del_url: '/admin/article.index/del',
                    multi_url: '/admin/article.index/multi', // 批量操作和change都是用它
                    dragsort_url:'',
                    text:{
                        'add_text':'',
                        'edit_text':''
                    }
                }
            });
            var table = $('#table');

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName:'id',
                searchFormVisible: false, //是否始终显示搜索表单
                commonSearch:true,
                pagination:true,
                search:false,
                // showPaginationSwitch: true,
                // pageSize:50,//每页显示条数
                showExport:false,
                showColumns:false,
                columns: [
                    {field: 'state', checkbox: true,},
                    {field: 'id', title: 'ID'},
                    {field: 'title', title: '标题', align: 'left', formatter: formatter.auth_title},
                    {field: 'classify_name', title: '分类'},
                    {field: 'author_nickname', title: '创建人', align: 'left', },
                    {
                        field: 'create_time',
                        title: '创建时间',
                        align: 'center',
                        sortable: true,
                        formatter: Table.api.formatter.datetime,
                        operate: 'RANGE',
                        addclass: 'datetimerange',
                    },
                    {field: 'status', title: '状态', formatter: Table.api.formatter.status,searchList: {"1":'正常',"0":'待审核','2':'冻结'}},
                    {
                        field: 'content_type',
                        title: '类型',
                        align: 'center',
                        formatter: formatter.content_type
                    },
                    {
                        field: 'operate',
                        width:180,
                        title: '操作',
                        table: table,
                        events: Table.api.events.operate,
                        formatter: Table.api.formatter.operate
                    }
                ],
                queryParams: function (params) {
                    return params;
                },
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

        });
        var formatter={
            content_type: function (value, row, index) {
                var icon = value ==1 ? 'fa fa-html5' : 'fa fa-hashtag' ;
                var text = value ==1 ? 'html5' : 'markdown' ;
                return '<span class="text-muted"><i class="' + icon + '"></i>'+ text +'</span>';
            },

        }
    </script>
@endsection
