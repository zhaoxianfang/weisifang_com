<!-- 继承home模板 -->
@extends('home::layouts.home')

<!-- 设置页面标题 -->
@section('title', '字符串生成图片')

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


        <div class="wrapper wrapper-content  animated fadeInRight article">

            <!-- <div class="row justify-content-md-center">
            </div> -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h1>字符串生成图片 <small>Generate pictures with strings</small></h1>
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


                                    <form role="form" name="str_to_img" class="unbind-form" method="post" onsubmit="return createImg()">


                                       <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">图片内容</label>
                                            <div class="col-lg-10">
                                                <input type="text" id="text" name="text" placeholder="填入字符串" value="hello!" class="form-control" required="">
                                                <span class="form-text m-b-none">
                                                    <strong>提示:</strong>填写数字、字母等
                                                </span>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">指定宽高<br /></label>
                                            <div class="col-lg-5">

                                                <input class="touchspin_input" type="text" value="500" name="width">
                                                <div class="m-b-sm">
                                                    <small ><strong>提示:</strong>指定宽度(px)  </small>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <input class="touchspin_input" type="text" value="300" name="height">
                                                <div class="m-b-sm">
                                                    <small ><strong>提示:</strong>设置图片高度(px)  </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">图片颜色<br /></label>
                                            <div class="col-lg-5">
                                                <input type="text" class="form-control color_plugins" name="color" value="#FFFFFF" />
                                                <div class="m-b-sm">
                                                    <small ><strong>文字颜色 提示:</strong>例如:FFFFFF  </small>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <input type="text" class="form-control color_plugins" name="bg_color" value="#0000FF" />
                                                <div class="m-b-sm">
                                                    <small ><strong>图片背景色 提示:</strong>例如:0000FF </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">文字字体</label>
                                            <div class="col-lg-10">
                                                <select class="select2_font form-control" name="font">
                                                    <option value="yuanti">圆体</option>
                                                    <option value="diandain">点点像素体-方形</option>
                                                    <option value="diandain_yt">点点像素体-圆形</option>
                                                    <option value="diandain_lx">点点像素体-菱形</option>
                                                    <option value="lishu" selected="">隶书</option>
                                                    <option value="qiuhong">秋鸿楷体</option>
                                                    <option value="taiwan_lishu">台湾隶书</option>
                                                    <option value="xingshu">行书</option>
                                                    <option value="code">代码体</option>
                                                    <option value="caoshu">草书</option>
                                                    <option value="kaiti">方正楷体简体</option>
                                                    <option value="fangsong">方正仿宋简体</option>
                                                    <option value="oppo">OPPO官方字体</option>
                                                    <option value="ali_puhui">阿里巴巴普惠体2.0</option>
                                                    <option value="baotuxiaobai">包图小白体</option>
                                                    <option value="heiti">方正黑体简体</option>
                                                    <option value="honglei">鸿雷板书简体</option>
                                                    <option value="haoshenti">优设好身体</option>
                                                    <option value="myshouxie">沐瑶软笔手写体</option>
                                                    <option value="foxi">佛系体</option>
                                                    <option value="wzny">亡者农药体</option>
                                                    <option value="sj_sjjt">三极瘦金简体</option>
                                                    <option value="sh_jjt">三极尖叫体</option>
                                                    <option value="sj_qyxz">三极秦韵小篆</option>
                                                    <option value="a_kspy">Aa楷书拼音</option>
                                                    <option value="fzkt">方正楷体</option>
                                                    <option value="sj_kdt">三极空叠体</option>
                                                    <option value="sj_ltjhjt">三极立体极黑简体</option>
                                                </select>
                                                <span class="form-text m-b-none">
                                                    <strong>提示:</strong>填写数字、字母等
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">允许文字换行</label>
                                            <div class="col-lg-10">
                                                <select class="select2_font form-control" name="allow_wrap">
                                                    <option value="1">允许</option>
                                                    <option value="0">禁止</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">文字旋转角度</label>
                                            <div class="col-lg-10">
                                                <input type="text" value="10" name="rotate" class="dial dial-rotate m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" data-min="0" data-max="360"/>
                                                <span class="form-text m-b-none">
                                                    <strong>提示:</strong>点击或者滚动鼠标
                                                </span>
                                            </div>
                                        </div>

                                        <div>

                                            <button type="submit" class="btn btn-success  dim btn-block" ><i class="fa fa-upload"></i> &nbsp;&nbsp; 生成 图片</button>
                                        </div>
                                    </form>

                                    <p>使用手册：</p>

                                </div>
                                <div class="col-sm-6">
                                    <h4>预览效果图</h4>
                                    <p>效果图:</p>
                                    <p class="text-center">

                                        <div id="compressor_res_box" style="display: none;">


                                            <img src="/tools/text2png/通过左侧配置项生成此效果图:demo/500/300/ffffff/0000ff/28/a_kspy/0.html" id="preview_img" alt="预览图片" style="width: auto;max-width: 100%;margin-bottom: 25px;">

{{--                                            <p>base64 文件:</p>--}}
{{--                                            <!-- <textarea class="form-control" id="preview_img_area" placeholder="提交后生成的base64图片"></textarea> -->--}}
{{--                                            <pre class="m-t-sm" id="preview_img_pre" style="max-height: 200px;"></pre>--}}

                                        </div>

{{--                                        <a href="javascript:;" id='images_none'>--}}
{{--                                            <i class="fa fa-file-image-o big-icon"></i>--}}
{{--                                        </a>--}}
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
   <!-- JSKnob -->
   <script src="{{ asset('static/inspinia/js/plugins/jsKnob/jquery.knob.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('static/inspinia/js/plugins/select2/select2.full.min.js') }}"></script>

    <!-- Color picker -->
    <script src="{{ asset('static/inspinia/js/plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <!-- TouchSpin -->
    <script src="{{ asset('static/inspinia/js/plugins/touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
<script type="text/javascript">

        $(".dial").knob();

        $('.color_plugins').colorpicker();

        $(".select2_font").select2({
                theme: 'bootstrap4',
                placeholder: "请选择一种字体",
                // allowClear: true
        });

        $(".touchspin_input").TouchSpin({
                min: 0,
                max: 3000,
                step: 1,
                decimals: 0, // 小数点
                boostat: 5,
                maxboostedstep: 10,
                postfix: 'px',
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

        function createImg(e) {
            $('#preview_img').attr("src",'');
             // document.forms["str_to_img"]["fname"].value + "!"
             console.log(e)
             var text = document.forms["str_to_img"]['text'].value || 'hello';
             var width = document.forms["str_to_img"]['width'].value;
             var height = document.forms["str_to_img"]['height'].value;
             var color = (document.forms["str_to_img"]['color'].value).substr(1);
             var bg_color = (document.forms["str_to_img"]['bg_color'].value).substr(1);
             var font = document.forms["str_to_img"]['font'].value;
             var rotate = document.forms["str_to_img"]['rotate'].value;
             var allow_wrap = document.forms["str_to_img"]['allow_wrap'].value;

             // console.log(document.forms["str_to_img"])

             let protocol = window.location.protocol, host = window.location.host;
             let url_domain = `${protocol}//${host}`;


             var url = url_domain+'/tools/text2png/'+text+'/'+width+'/'+height+'/'+color+'/'+bg_color+'/'+rotate+'/'+font+'/'+allow_wrap+'.html';
             console.log(url)
             $('#images_none').hide()
                $('#compressor_res_box').show()
                $('#preview_img').attr("src",url);

             return false
        }
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
        $('#compressor_res_box').show()


</script>
@endsection
