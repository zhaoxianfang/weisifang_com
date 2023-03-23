<!-- 继承home模板 -->
@extends('home::layouts.home')

<!-- 设置页面标题 -->
@section('title', '在线生成二维码、条形码')

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
                            <h1>在线生成二维码/条形码 <small>Generate QR codes or barcodes online</small></h1>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-sm-6 b-r">
                                    <h3 class="m-t-none m-b">参数设置：</h3>



                                    <div class="tabs-container">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li><a class="nav-link active" data-toggle="tab" href="#tab-1"> 生成二维码</a></li>
                                            <li><a class="nav-link" data-toggle="tab" href="#tab-2">生成条形码</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div role="tabpanel" id="tab-1" class="tab-pane active">
                                                <div class="panel-body">


                                                    <form role="form" method="post">

                                                        <input type="hidden" name="create_type" value="QrCode">

                                                        <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">二维码内容<br /><strong>&nbsp;</strong></label>
                                                            <div class="col-lg-10">
                                                                <textarea class="form-control" id="text" name="text" rows="6"  placeholder="填入文字或者url" required=""></textarea>
                                                                <div class="m-b-sm">
                                                                    <small ><strong>提示:</strong>填写二维码内容，例如网址或者文字内容 </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">label<br /><strong>&nbsp;</strong></label>
                                                            <div class="col-lg-10">
                                                                <input type="label" name="label" placeholder="例如:xxx公众号" class="form-control" >
                                                                <div class="m-b-sm">
                                                                    <small ><strong>提示:</strong>显示在二维码下方的文字 </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row has-success">
                                                            <label class="col-sm-2 col-form-label">
                                                                容错率
                                                            </label>

                                                            <div class="col-sm-10">

                                                                <label>
                                                                    <input type="radio" value="high" name="level">
                                                                    高(30%)
                                                                </label>
                                                                &nbsp;&nbsp;
                                                                 <label>
                                                                    <input type="radio" value="quartile" name="level">
                                                                    1/4(25%)
                                                                </label>
                                                                &nbsp;&nbsp;
                                                                <label>
                                                                    <input type="radio" checked="" value="medium" name="level">
                                                                    中(15%)
                                                                </label>
                                                                &nbsp;&nbsp;
                                                                <label>
                                                                    <input type="radio" value="low" name="level">
                                                                    低(7%)
                                                                </label>

                                                            </div>
                                                        </div>



                                                       <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">边距</label>
                                                            <div class="col-lg-10">
                                                                <input type="text" id="padding" name="padding" placeholder="边距" class="form-control" required="">
                                                                <span class="form-text m-b-none">&nbsp;</span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">二维码大小</label>
                                                            <div class="col-lg-10">
                                                                <input type="text" id="size" name="size" placeholder="二维码大小" class="form-control" required="">
                                                                <span class="form-text m-b-none">生成二维码图片大小</span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">文字字体大小</label>
                                                            <div class="col-lg-10">
                                                                <input type="text" id="font_size" name="font_size" placeholder="文字字体大小" class="form-control" required="">
                                                                <span class="form-text m-b-none">文字字体大小</span>
                                                            </div>
                                                        </div>

                                                        <div>

                                                            <button type="submit" class="btn btn-success  dim btn-block" ><i class="fa fa-upload"></i> &nbsp;&nbsp; 生成 条形码/二维码</button>
                                                        </div>
                                                    </form>


                                                </div>
                                            </div>
                                            <div role="tabpanel" id="tab-2" class="tab-pane">
                                                <div class="panel-body">


                                                    <form role="form" method="post">

                                                        <input type="hidden" name="create_type" value="BarCode">

                                                        <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">条形码类型<br /><strong>&nbsp;</strong></label>
                                                            <div class="col-lg-10">
                                                                <select class=" form-control" name="code_type">
                                                                    <option value="CINcodabar">Codabar</option>
                                                                    <option value="CINean8">Ean8</option>
                                                                    <option value="CINcode128" selected>Code128(通用)</option>
                                                                    <option value="CINcode11">Code11</option>
                                                                    <option value="CINcode39">Code39</option>
                                                                    <option value="CINcode39extended">Code39Extended</option>
                                                                    <option value="CINean13">Ean13</option>
                                                                    <option value="Ean128">Ean128</option>
                                                                    <option value="CINgs1128">Gs1128</option>
                                                                    <option value="CINi25">I25</option>
                                                                    <option value="CINisbn" >Isbn</option>
                                                                    <option value="CINmsi">Msi</option>
                                                                    <option value="CINpostnet">Postnet</option>
                                                                    <option value="CINs25">S25</option>
                                                                    <option value="CINupca">Upca</option>
                                                                    <option value="CINupce">Upce</option>
                                                                    <option value="CINupcext2">Upcext2</option>
                                                                    <option value="CINupcext5">Upcext5</option>
                                                                    <option value="CINintelligentmail">Intelligentmail</option>

                                                                </select>
                                                                <div class="m-b-sm">
                                                                    <small ><strong>提示:</strong>填写二维码内容，例如网址或者文字内容 </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">内容<br /><strong>&nbsp;</strong></label>
                                                            <div class="col-lg-10">
                                                                <input type="text" name="text" placeholder="填入条形码内容 例如:9787115279460" class="form-control" required="">
                                                                <div class="m-b-sm">
                                                                    <small ><strong>提示:</strong>填入条形码内容 例如：9787115279460 </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">label<br /><strong>&nbsp;</strong></label>
                                                            <div class="col-lg-10">
                                                                <input type="label" name="label" placeholder="例如:code128码" class="form-control" >
                                                                <div class="m-b-sm">
                                                                    <small ><strong>提示:</strong>显示在二维码下方的文字 </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">字体大小</label>
                                                            <div class="col-lg-10">
                                                                <input type="text" id="font_size_2" name="font_size" placeholder="字体大小" class="form-control" required="">
                                                                <span class="form-text m-b-none">字体大小</span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">分辨率</label>
                                                            <div class="col-lg-10">
                                                                <input type="text" id="scale" name="scale" placeholder="设置分辨率" class="form-control" required="">
                                                                <span class="form-text m-b-none">设置分辨率</span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row has-success">
                                                            <label class="col-lg-2 col-form-label">厚度/高度</label>
                                                            <div class="col-lg-10">
                                                                <input type="text" id="thickness" name="thickness" placeholder="设置厚度或高度" class="form-control" required="">
                                                                <span class="form-text m-b-none">设置厚度或高度</span>
                                                            </div>
                                                        </div>

                                                        <div>

                                                            <button type="submit" class="btn btn-success  dim btn-block" ><i class="fa fa-upload"></i> &nbsp;&nbsp; 生成 条形码/二维码</button>
                                                        </div>
                                                    </form>


                                                </div>
                                            </div>
                                        </div>


                                    </div>



                                    <p>使用手册：先填写左边的参数，然后点击提交，然后就可以生成 条形码/二维码图片图片啦.</p>

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
        $(window).bind('hashchange', function() {
            // 获取锚点的值 并选中某个tab
            var tab = my.getAnchorPoint('tab');
            $("a[href='" + '#tab-'+(tab || 1) + "']").tab("show");
        });

        // 获取锚点的值 并选中某个tab
        var tab = my.getAnchorPoint('tab');

        $(function(){
            $("a[href='" + '#tab-'+(tab || 1) + "']").tab("show");
    　　});

        $("#size").ionRangeSlider({
            skin: "flat",
            min: 50,
            max: 500,
            step: 1,
            from: 200,
            postfix: " px",
            grid: true,
            // type: 'single',
            // prettify: false,
            // hasGrid: true
        });
        $("#padding").ionRangeSlider({
            skin: "flat",
            min: 0,
            max: 20,
            step: 1,
            from: 10,
            postfix: " px",
            grid: true,
        });
        $("#font_size").ionRangeSlider({
            skin: "flat",
            min: 5,
            max: 40,
            step: 1,
            from: 16,
            postfix: " px",
            grid: true,
        });
        $("#font_size_2").ionRangeSlider({
            skin: "flat",
            min: 5,
            max: 40,
            step: 1,
            from: 10,
            postfix: " px",
            grid: true,
        });
        $("#scale").ionRangeSlider({
            skin: "flat",
            min: 1,
            max: 5,
            step: 1,
            from: 2,
            postfix: "",
            grid: true,
        });
        $("#thickness").ionRangeSlider({
            skin: "flat",
            min: 15,
            max: 50,
            step: 1,
            from: 25,
            postfix: "",
            grid: true,
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
