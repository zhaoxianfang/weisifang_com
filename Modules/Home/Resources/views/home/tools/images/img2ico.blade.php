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
                        <h1>图片转ico <small>Image to ICO</small></h1>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-6 b-r">
                                <h3 class="m-t-none m-b">参数设置：</h3>


                                <form role="form" method="post">


                                    <div class="form-group row has-success">
                                        <label class="col-sm-2 col-form-label">
                                            生成图片大小
                                        </label>

                                        <div class="col-sm-10">
                                            <label>
                                                <input type="radio" value="16" name="size">16x16
                                            </label>

                                            <label>
                                                <input type="radio" value="32" name="size" checked="">32x32
                                            </label>

                                            <label>
                                                <input type="radio" value="48" name="size">64x64
                                            </label>

                                            <label>
                                                <input type="radio" value="128" name="size">128x128
                                            </label>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">选择目标图片</label>
                                        <div class="col-lg-10">
                                            <div class="custom-file">
                                                <input id="images_file" name="images"  type="file" class="custom-file-input"  required="">
                                                <label for="images_file" class="custom-file-label">选择图片...</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <button type="submit" class="btn btn-success  dim btn-block" ><i class="fa fa-upload"></i> 开始生成图片</button>
                                    </div>
                                </form>

                                <p>使用手册：</p>
                                <p><strong>...</strong></p>

                            </div>
                            <div class="col-sm-6">
                                <h4>预览效果图</h4>
                                <p>效果图:</p>
                                <p class="text-center">
                                <div id="compressor_res_box" style="display: none;">
                                    <img src="" id="preview_img" alt="预览图片" style="width: auto;max-width: 100%;margin-bottom: 25px;">
                                </div>
                                <a href="javascript:;" id='images_none'>
                                    <i class="fa fa-download"></i>
                                    下载
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
    <script>
        var base64Img = '';
        $('#images_none').hide()
        function downloadFile(fileName, content) {
            let aLink = document.createElement('a')
            let blob = this.base64ToBlob(content) // new Blob([content]);
            let evt = document.createEvent('HTMLEvents')
            evt.initEvent('click', true, true)// initEvent 不加后两个参数在FF下会报错  事件类型，是否冒泡，是否阻止浏览器的默认行为
            aLink.download = fileName
            aLink.href = URL.createObjectURL(blob)
            // aLink.dispatchEvent(evt);
            aLink.click()
        }
        // base64转blob
        function base64ToBlob(code) {
            let parts = code.split(';base64,')
            let contentType = parts[0].split(':')[1]
            let raw = window.atob(parts[1]) // 解码base64得到二进制字符串
            let rawLength = raw.length
            let uInt8Array = new Uint8Array(rawLength) // 创建8位无符号整数值的类型化数组
            for (let i = 0; i < rawLength; ++i) {
                uInt8Array[i] = raw.charCodeAt(i) // 数组接收二进制字符串
            }
            return new Blob([uInt8Array], {type: contentType})
        }
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
            $(this).next('.custom-file-label').addClass("selected").html(fileName);

            form_before({})

        });
        function form_before(res) {
            base64Img = ''
            $('#images_none').hide()
            $('#compressor_res_box').hide()
        }

        function form_after(res) {
            // console.log(res)
            $('#images_none').show()
            $('#preview_img').attr("src",res.data.base64_str);
            $('#compressor_res_box').show()
            // $('#preview_img_area').val(res.data.base64_str);
            // $('#preview_img_pre').text(res.data.base64_str);
            base64Img = res.data.base64_str
        }
        $("#images_none").click(function(){
            downloadFile('favicon.ico',base64Img)
        });
    </script>
@endsection
