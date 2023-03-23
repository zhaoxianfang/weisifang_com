<!-- 继承home模板 -->
@extends('home::layouts.home')

<!-- 设置页面标题 -->
@section('title', '图片在线压缩裁剪')

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
<div class="article">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h1>图片在线压缩裁剪 <small>Image compression and cropping online</small></h1>
                    <div class="ibox-tools">
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-6 b-r">
                            <h3 class="m-t-none m-b">参数设置：</h3>

                            <form role="form" method="post">

                               <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">压缩比例<br /><strong>模式一</strong></label>
                                    <div class="col-lg-10">
                                        <input type="text" id="proportion" name="proportion" placeholder="压缩比例" class="form-control" required="">
                                        <span class="form-text m-b-none">
                                            <strong>提示:</strong>默认值为1；值为1表示原图尺寸，值越小，图片宽高尺寸越小
                                        </span>
                                    </div>
                                </div>

                                <!-- <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">压缩级别</label>
                                    <div class="col-lg-10">
                                        <input type="text" id="compress" name="compress" placeholder="压缩级别" class="form-control" required="">
                                        <div class="m-b-sm">
                                            <small ><strong>提示（仅jpg图片有效）: </strong>压缩级别，级别越高就图片越小也就越模糊  </small>
                                        </div>
                                    </div>
                                </div> -->


                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">指定宽高<br /><strong>模式二</strong></label>
                                    <div class="col-lg-5">
                                        <input type="text" name="width" placeholder="指定宽度(px)" class="form-control">
                                        <div class="m-b-sm">
                                            <small ><strong>提示:</strong>指定宽度(px)  </small>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <input type="text" name="height" placeholder="指定高度(px)" class="form-control">
                                        <div class="m-b-sm">
                                            <small ><strong>提示:</strong>设置图片高度(px)  </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">选择目标图片</label>
                                    <div class="col-lg-10">
                                        <div class="custom-file">
                                            <input id="images_file" name="images_file"  type="file" class="custom-file-input"  required="">
                                            <label for="images_file" class="custom-file-label">选择图片...</label>
                                        </div>
                                    </div>

                                </div>

                                <div>
                                    <button type="submit" class="btn btn-success  dim btn-block" ><i class="fa fa-upload"></i> &nbsp;&nbsp; 裁剪/压缩 图片</button>
                                </div>
                            </form>

                            <p>使用手册：先修改下面到图片裁剪/压缩参数，然后点击右侧的图片选择组件，然后就可以在线裁剪/压缩图片啦.</p>
                            <p><strong>1、【压缩比例】和【设置固定宽高】 二者只能选择一种模式</strong></p>
                            <p><strong>2、如果填写了设置固定宽高则使用【设置固定宽高】配置模式</strong></p>
                            <p><strong>3、如果 固定宽高的值都为空 则使用 【压缩比例】 模式</strong></p>
                            <p><strong>4、模式二中如果有一个值为0另一个值不为0则保留图片的原始比例进行压缩</strong></p>

                        </div>
                        <div class="col-sm-6">
                            <h4>预览效果图</h4>
                            <p>效果图:</p>
                            <p class="text-center">

                                <div id="compressor_res_box" style="display: none;">

                                    <img src="" id="preview_img" alt="预览图片" style="width: auto;max-width: 100%;margin-bottom: 25px;">

                                    <p>base64 文件:</p>
                                    <!-- <textarea class="form-control" id="preview_img_area" placeholder="提交后生成的base64图片"></textarea> -->
                                    <pre class="m-t-sm" id="preview_img_pre" style="max-height: 200px;"></pre>
                                </div>
                                <a href="javascript:;" id='images_none'>
                                    <i class="fa fa-file-image-o big-icon"></i>
                                </a>
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

    <!-- IonRangeSlider -->
    <script src="{{ asset('static/inspinia/js/plugins/ionRangeSlider/ion.rangeSlider.min.js') }}"></script>



<script type="text/javascript">

        $("#proportion").ionRangeSlider({
            skin: "flat",
            min: 0.1,
            max: 1,
            step: 0.1,
            from: 1,
            postfix: " %",
            grid: true,
            // type: 'single',
            // prettify: false,
            // hasGrid: true
        });
        $("#compress").ionRangeSlider({
            skin: "flat",
            min: 0,
            max: 9,
            step: 0.1,
            from: 7.5,
            postfix: "",
            grid: true,
        });

        $('.custom-file-input').on('change', function() {
           let fileName = $(this).val().split('\\').pop();
           $(this).next('.custom-file-label').addClass("selected").html(fileName);
           $(this).next('.custom-file-label').addClass("selected").html(fileName);

           form_before({})

        });
        function form_before(res) {
            $('#images_none').show()
            $('#compressor_res_box').hide()
        }
        function form_after(res) {
            // console.log(res)
            $('#images_none').hide()
            $('#compressor_res_box').show()
            $('#preview_img').attr("src",res.data.base64_str);
            // $('#preview_img_area').val(res.data.base64_str);
            $('#preview_img_pre').text(res.data.base64_str);
        }


</script>
@endsection
