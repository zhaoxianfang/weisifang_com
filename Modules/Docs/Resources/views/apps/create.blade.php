@extends('docs::layouts.docs')

@section('title', '新建文档')
@section('load_docs_left_menu', 'false')

@section('content')
    <h1>新建文档</h1>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>提示： <small>填写并提交下面的表单，即可创建一个应用文档.</small></h5>
                    <div class="ibox-tools"></div>
                </div>
                <div class="ibox-content">
                    <form method="post">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">文档名称<font color="#FF0000">*</font></label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="app_name" data-rule="required" /></div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row"><label class="col-sm-2 col-form-label">接口域名</label>
                            <div class="col-sm-10">

                                <div class="row url_item" style="margin:0;">
                                    <input type="text" class="form-control col-sm-3" id="url_base_alias" value="" name="urls[alias][]" placeholder="例如:测试环境" autocomplete="off" data-tips title="url前缀 别名 例如：测试环境" />
                                    <input type="text" class="form-control col-sm-7" id="url_base" value="" name="urls[url_prefix][]" placeholder="例如:http://api.xxx.com/" autocomplete="off" data-tips title="以 http(s)开头，'/'结尾的url地址，例如：http://api.xxx.com/" />
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-success" id='plus_app_url' ><i class="fa fa-plus" aria-hidden="true"></i></button>
                                        <button type="button" class="btn btn-danger trash_app_url" style="display: none;" ><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </div>
                                </div>

                                <div id="add_url_prefix"></div>
                                <span class="form-text m-b-none">该项主要用于API文档接口,若无则忽略本项.</span>
                            </div>

                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">封面图</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input id="location-app_cover" value="" class="form-control" onclick="$('#app_cover').click();" placeholder="未上传封面图的使用默认图" data-tips="" title="#app_cover_img_box">
                                    <label class="input-group-btn">
                                        <input type="button" value="请选择上传图片" class="btn btn-primary upload-file-btn" onclick="$('#app_cover').click();">
                                    </label>
                                </div>
                            </div>
                            <input type="file" name="app_cover" id='app_cover' value="" accept=".jpg, .jpeg, .png" onchange="$('#location-app_cover').val($('#app_cover').val());" style="display: none">

                            <div id="app_cover_img_box" style="display:none;">
                                <span>图片预览:155*200px</span>
                                <br />
                                <img src="/static/apidoc/images/apidoc_bg.jpeg" id="app_cover_img" style="width: 155px;height: 200px;" alt="文档封面图">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">文档简介<font color="#FF0000">*</font></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="description" placeholder="介绍一下本文档..." rows="4" data-rule="required"></textarea>
                                <span class="form-text m-b-none">好的简介更容易体现文档的主题和核心</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">文档公开类型 <br/>
                                <small class="text-navy">默认公开</small>
                            </label>
                            <div class="col-sm-10">
                                <div>
                                    <label>
                                        <input type="radio" checked="" value="1" name="open_type">
                                        公开[有助于别人发现您的文档][所有人可见]
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="radio"  value="2" name="open_type">
                                        仅添加到本文档的成员可见[内部文档]
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">文档标记时间 <br/>
                                <small class="text-navy">标记在指定天数内被修改的文档</small>
                            </label>
                            <div class="col-sm-10">
                                <select class="form-control m-b" name="mark_day">
                                    <option value="1">1天</option>
                                    <option value="2">2天</option>
                                    <option value="3" selected>3天[推荐]</option>
                                    <option value="4">4天</option>
                                    <option value="5">5天</option>
                                    <option value="7">一周</option>
                                    <option value="10">10天</option>
                                    <option value="15">半个月</option>
                                    <option value="30">一个月</option>
                                </select>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">团队名称/创作者<font color="#FF0000">*</font></label>
                            <div class="col-sm-10"><input type="text" name="team_name" class="form-control" data-rule="required"/></div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row"><label class="col-sm-2 col-form-label">文档状态</label>
                            <div class="col-sm-10">
                                <label> <input type="radio" name="status" value="1" checked> 正常 </label>
                                <label> <input type="radio" name="status" value="0" > 停用[仅文档创建人可见] </label>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="reset" class="btn btn-w-m btn-white">暂不创建</button>
                                <button type="submit" class="btn btn-w-m btn-primary">提交并创建文档</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <!-- 页面中引入page js -->
    <script type="text/javascript">
        $(function(){
            my.listenFileChangeURL('#app_cover',function (src,ele) {
                $('#app_cover_img').attr("src",src);
            });

        });

        // =========================
        // 添加/删除域名列表 begin
        $(".trash_app_url").off()
        $("#plus_app_url").off()
        $('.trash_app_url').click(function(){
            $(this).parent().parent('.url_item').remove();
        });
        $('#plus_app_url').click(function(){
            var child = $(".url_item").parent().children(".url_item:first-child").clone(true);
            //清除克隆的数据
            child.find(":input").each(function(i){
                $(this).val("");
            });
            child.find('.trash_app_url').show();
            child.find('#plus_app_url').remove();
            $("#add_url_prefix").before(child);
        });
        // 添加/删除域名列表 end
        // =========================
    </script>
@endsection
