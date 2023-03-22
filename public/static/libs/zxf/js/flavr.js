/**
 * 封装flavr，方便使用
 */
var Flavr = {
    obj: null,
    //animates 动画数组
    animates: [
        // Attention Seekers
        "bounce", "flash", "pulse", "rubberBand", "shake", "swing", "tada", "wobble",
        // Bouncing Entrances
        "bounceIn", "bounceInDown", "bounceInLeft", "bounceInRight", "bounceInUp",
        // Bouncing Exits
        "bounceOut", "bounceOutDown", "bounceOutLeft", "bounceOutRight", "bounceOutUp",
        // Fading Entrances
        "fadeIn", "fadeInDown", "fadeInDownBig", "fadeInLeft", "fadeInLeftBig", "fadeInRight", "fadeInRightBig", "fadeInUp", "fadeInUpBig",
        // Fading Exits
        "fadeOut", "fadeOutDown", "fadeOutDownBig", "fadeOutLeft", "fadeOutLeftBig", "fadeOutRight", "fadeOutRightBig", "fadeOutUp", "fadeOutUpBig",
        // Flippers
        "flip", "flipInX", "flipInY", "flipOutX", "flipOutY",
        // Lightspeed
        "lightSpeedIn", "lightSpeedOut",
        // Rotating Entrances
        "rotateIn", "rotateInDownLeft", "rotateInDownRight", "rotateInUpLeft", "rotateInUpRight",
        // Rotating Exits
        "rotateOut", "rotateOutDownLeft", "rotateOutDownRight", "rotateOutUpLeft", "rotateOutUpRight",
        // Sliders
        "slideInDown", "slideInLeft", "slideInRight", "slideOutLeft", "slideOutRight", "slideOutUp",
        // Specials
        "hinge", "rollIn", "rollOut"
    ],
    //获取随机动画
    getAnimate: function () {
        return this.animates[Math.floor(Math.random() * this.animates.length)];
    },
    closeAll: function () {
        this.obj.exit();
    },
    close: function (instance) {
        instance ? instance.close() : this.obj.close();
    },
    /**
     * 提示信息
     * @param {String} 信息内容
     */
    msg: function (msg, timer = 3) {
        this.obj = new $.flavr({
            modal: false, // 是否模态的对话框
            position: 'custom-center',// 'flavr-center','custom-center','top-right','top-left','bottom-right','bottom-mid'
            autoclose: true, // 是否自动关闭

            // Esc 按键关闭，不需要时候删除下面2行
            closeOverlay: true,
            closeEsc: true,

            timeout: timer * 1000,
            buttons: {},
            // opacity:0.5,

            content: msg,
            // 入场和退出动画
            animateEntrance: Flavr.getAnimate(),
            animateClosing: Flavr.getAnimate(),
        });
    },
    /**
     * 确认信息
     * @param {String} 信息内容
     * @param {Function} 确认回调函数
     * @param {Function} 取消回调函数
     */
    confirm: function (msg, okBack, cancelBack) {
        this.obj = new $.flavr({
            dialog: 'confirm',
            animateEntrance: Flavr.getAnimate(),
            content: msg,
            onConfirm: function () {
                okBack && okBack();
            }, onCancel: function () {
                cancelBack && cancelBack();
            }
        });
    },
    /**
     * 弹窗信息
     * @param {String} 信息内容
     */
    alert: function (msg) {
        this.obj = new $.flavr({
            buttons: {
                OK: {
                    text: '确认',
                    style: 'default',
                    addClass: null,
                    action: null
                }
            },
            animateEntrance: Flavr.getAnimate(),
            content: msg
        });
    },
    /**
     * 输入确认框
     *
     * @param title
     * @param placeholder
     * @param okBack
     * @param cancelBack
     */
    prompt: function (title, placeholder, okBack, cancelBack) {
        this.obj = new $.flavr({
            content: title || '请在下方填写?',
            dialog: 'prompt',
            prompt: {
                placeholder: placeholder || 'Enter something'
            },
            onConfirm: function ($container, $prompt) {
                return okBack($prompt.val(), this)
                // return false; // false 不关闭 true 关闭
            }
            , onCancel: function () {
                // console.log('取消了');
                cancelBack && cancelBack(this)
                return true;
            },
            // buttons: {
            //     OK: {
            //         text: '确认',
            //         style: 'danger',
            //         addClass: null,
            //         action: function ($container, $prompt) {
            //             // 引导动画
            //             // this.swing();
            //             // this.shake();
            //             // this.wobble();
            //             // this.flash();
            //             // this.tada();
            //             // this.pulse();
            //             // this.bounce();
            //             return okBack($prompt.val())
            //             // return false; // false 不关闭 true 关闭
            //         }
            //     },
            //     CANCEL: {
            //         text: '取消',
            //         addClass: null,
            //         action: cancelBack
            //     }
            // },
        });
    },
    /**
     * 水平 多按钮
     *
     * @demo multipleBtn('Title',{
     *                 primary: {
     *                     text: 'Primary', style: 'primary', action: function () {
     *                         alert('Primary button');
     *                         return false;
     *                     }
     *                 }, success: {
     *                     text: 'Success', style: 'success', action: function () {
     *                         alert('Mission succeed!');
     *                         return false;
     *                     }
     *                 }, info: {
     *                     text: 'Info',
     *                     style: 'info', action: function () {
     *                         alert('For your information');
     *                         return false;
     *                     }
     *                 }, warning: {
     *                     text: 'Warning',
     *                     style: 'warning',
     *                     action: function () {
     *                         alert('No good captain!');
     *                         return false;
     *                     }
     *                 }, danger: {
     *                     text: 'Danger',
     *                     style: 'danger',
     *                     action: function () {
     *                         alert('Mission failed!');
     *                         return false;
     *                     }
     *                 }, close: {
     *                     style: 'default'
     *                 })
     */
    multipleBtn: function (title, btnList) {
        this.obj = new $.flavr({
            content: title || '请选择',
            buttons: btnList
        });
    },
    /**
     * 垂直按钮列表
     * @demo stackedBtn('测试title',{
     *     confirm: {style: 'info',text:'按钮一'},
     *     remove: {style: 'danger'},
     *     close: {style: 'default'}
     * })
     */
    stackedBtn: function (title, btnList) {
        this.obj = new $.flavr({
            buttonDisplay: 'stacked',
            content: title || '请选择',
            buttons: btnList
        });
    },
    /**
     * 显示网页
     * @param title
     * @param html
     */
    html: function (title, html) {
        this.obj = new $.flavr({
            title: title || '',
            content: html,
            // Esc 按键关闭，不需要时候删除下面2行
            closeOverlay: true,
            closeEsc: true,
            onShow: function () {
                this.fullscreen()
            },
            buttons: {
                close: {
                    text: '关闭',
                }
            }
        });
    },
    /**
     * iframe
     * @param url
     */
    iframe: function (url, width, height) {
        this.obj = new $.flavr({
            // title: 'Charlie bit my finger',
            title: false,
            // Esc 按键关闭，不需要时候删除下面2行
            closeOverlay: true,
            closeEsc: true,
            content: '<iframe width="100%" height="100%" src="' + url + '" frameborder="0" allowfullscreen style="height: calc( 100vh - 10px );"></iframe>',
            onShow: function () {
                if (width && width) {
                    this.resize(width, width);
                } else {
                    this.fullscreen();
                }
                // this.fullscreen();
                // this.resize(700, 300);
                // this.revert();
                // this.content('<p>Thisis the new content</p><br/><p>New line</p>');
            },
            buttons: {
                close: {
                    text: '关闭',
                }
            }
        });
    },
    /**
     * form 表单
     * @param title
     * @param options
     * @param tips
     * @demo
     *          Flavr.form('登录',[
     *             {
     *                 type:'text',
     *                 name:'aha',
     *                 value:'aha',
     *                 placeholder:"请输入。。。",
     *                 required:true
     *             },{
     *                 type:'select',
     *                 name:'aha',
     *                 value:'key2',
     *                 placeholder:"请输入。。。",
     *                 options:{
     *                     'key':"value",
     *                     'key2':"value2",
     *                     'key3':"value3",
     *                 }
     *             },
     *             {
     *                 type:'password',
     *                 name:'aha2',
     *                 placeholder:"请输入密码。。。"
     *             },
     *             {
     *                 type:'checkbox',
     *                 name:'aha2',
     *                 value:'key3',
     *                 list:{
     *                     'key':"value",
     *                     'key2':"value2",
     *                     'key3':"value3",
     *                 }
     *             },{
     *                 type:'radio',
     *                 name:'aha2',
     *                 value:'key3',
     *                 list:{
     *                     'key':"value",
     *                     'key2':"value2",
     *                     'key3':"value3",
     *                 }
     *             },
     *
     *         ]);
     */
    form: function (title, options, onSubmitBack, method = 'post', tips = '') {
        var html = '';
        for (const item of options) {
            switch (item.type) {
                case 'text':
                case 'password':
                case 'hidden':
                    html += '<div class="form-row">' +
                        '<input type="' + (item.type || 'text') + '" name="' + item.name + '" placeholder="' + (item.placeholder || '') + '" value="' + (item.value || '') + '" required="' + (item.required || false) + '"/>' +
                        ' </div>';
                    break;
                case 'select':
                    var optionEle = '';
                    for (const key in item.options) {
                        optionEle += '<option value="' + key + '"' + (((item.value || '') == key) ? ' selected ' : '') + '>' + item.options[key] + '</option>';
                    }
                    html += '<div class="form-row">' +
                        '<select class="flavr-select" name="' + item.name + '">' +
                        optionEle +
                        '</select>';
                    break;
                case 'checkbox':
                    for (const key in item.list) {
                        html += '<div class="form-row">' +
                            ' <input type="checkbox" name="' + item.name + '" id="checkbox_' + item.name + key + '_check" value="' + key + '"' + (((item.value || '') == key) ? ' checked ' : '') + ' />' +
                            ' <label for="checkbox_' + item.name + key + '_check">' + item.list[key] + '</label>' +
                            ' </div>' +
                            ' </div>';
                    }
                    break;
                case 'radio':
                    for (const key in item.list) {
                        html += '<div class="form-row">' +
                            ' <input type="radio" name="' + item.name + '" id="radio_' + item.name + key + '_check" value="' + key + '"' + (((item.value || '') == key) ? ' checked ' : '') + '/>' +
                            ' <label for="radio_' + item.name + key + '_check">' + item.list[key] + '</label>' +
                            ' </div>' +
                            ' </div>';
                    }
                    break;
                default:
                // 默认代码块
            }
        }
        this.obj = new $.flavr({
            title: title || '表单提交'
            // , position: 'custom-center'
            , position: ''
            // , iconPath : 'flavr/images/icons/'
            // , icon : 'email.png'
            , content: tips || ''
            , dialog: 'form'
            , form: {
                content: html || ''
                , method: method || 'post'
            }, onSubmit: function ($container, $form) {
                // console.log($form.serialize());
                onSubmitBack && onSubmitBack($form.serialize(), $form)
                return false;
            }
        });
    }
};
