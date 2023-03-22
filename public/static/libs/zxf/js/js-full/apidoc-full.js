// 设置跑马灯的循环时间 开始
const my_marquee = document.querySelector('.my-marquee p');
if(my_marquee != null ){
  let my_marquee_timer = Math.ceil( (my_marquee.offsetWidth)/150);
  my_marquee.style.animation = "myMarqueeMove "+(my_marquee_timer > 10 ? my_marquee_timer:10)+"s linear 50ms infinite normal both";
}


// 设置跑马灯的循环时间 结束


// 首页文档列表展示
$(function(){
    var apidocItemTimer = 0;
    $(".apidoc-list li").hover(
      function(){
        var that=this;  
        apidocItemTimer=setTimeout(function(){
          $(that).find("div").animate({"top":0,"height":204},300,function(){
            $(that).find("p").fadeIn(200);
          });
        },100);
      },
      function(){
        var that=this;  
        clearTimeout(apidocItemTimer);
        $(that).find("p").fadeOut(200);
        $(that).find("div").animate({"top":120,"height":50},300);
      }
    )
});
// 首页文档列表展示 结束

// apidoc 页面js

// console.log(doc_app_id,can_edit_doc)
/**
 * apidoc 左侧目录页面
 * @Author   ZhaoXianFang
 * @DateTime 2020-10-13
 */
$(".apidoc-menu-item").click(function(e){
  var app_id = $(this).data('api_id')
  // window.location.href="/apidoc/document/api/"+app_id;
});

// 添加文档组
$('.add-group').click(function(e){
    new $.flavr({
      content : '添加文档组', 
      dialog : 'prompt',
      prompt : { placeholder: '输入文档组名称' }, 
      onConfirm : function( $container, $prompt){
        // console.log('回车了',$prompt.val())
        return false; 
      }, 
      onCancel : function(){ 
        console.log('取消')
      },
      buttons : {
        close : { 
          text: '取消', 
          style: 'danger', 
          action: function($container, $prompt){ 
            // console.log($container, $prompt,$prompt.val())
            return true; 
          } 
        }, 
        success : { 
          text: '确定', 
          style: 'success', 
          action:function($container, $prompt){
            if(! $prompt.val() ){
              flavr_info('请填写文档组名称',3,false)
              return false
            }
            console.log('添加组',$prompt.val())
            add_group($prompt.val())
            return false; 
          } 
        }
      }

    });
});

// 将文档用户移出正式成员
$(".move_out_doc_user").click(function(){
    var username = $(this).data('username')
    var userId = $(this).data('userid')

    new $.flavr({ 
      content : '请确认是否将用户【'+username+'】移出本文档？',
      dialog : 'confirm', 
      onConfirm : function( $container, $prompt){
        // console.log('回车了',$prompt.val())
        return false; 
      }, 
      onCancel : function(){ 
        // console.log('取消')
      },
      buttons : {
        success : { 
          text: '确定', 
          style: 'success', 
          action:function($container, $prompt){
            req_http("/apidoc/move_out_doc_user",{user_id:userId,app_id:doc_app_id})
            return false; 
          } 
        },
        close : { 
          text: '取消', 
          style: 'danger', 
          action: function($container, $prompt){
            return true; 
          } 
        },
      }
    });
});

// 拒绝用户加入到文档中
$(".refuse_join").click(function(){
    var username = $(this).data('username')
    var userId = $(this).data('userid')

    new $.flavr({ 
      content : '请确认是否驳回用户【'+username+'】加入本文档？',
      dialog : 'confirm', 
      onConfirm : function( $container, $prompt){
        // console.log('回车了',$prompt.val())
        return false; 
      }, 
      onCancel : function(){ 
        // console.log('取消')
      },
      buttons : {
        success : { 
          text: '确定', 
          style: 'success', 
          action:function($container, $prompt){
            req_http("/apidoc/refuse_user_join_doc",{user_id:userId,app_id:doc_app_id})
            return false; 
          } 
        },
        close : { 
          text: '取消', 
          style: 'danger', 
          action: function($container, $prompt){
            return true; 
          } 
        },
      }
    });
});


// 修改用户在本文档中的角色
$(".edit_user_role").click(function(){
    var username = $(this).data('username')
    var userId = $(this).data('userid')

    new $.flavr({ 
      content : '请设置用户【'+username+'】在本文档中的角色',
      // dialog : 'confirm', 
      buttonDisplay : 'stacked',
      onConfirm : function( $container, $prompt){
        // console.log('回车了',$prompt.val())
        return false; 
      }, 
      onCancel : function(){ 
        // console.log('取消')
      },
      buttons : {
        primary : { 
          text: '文档管理员', 
          style: 'primary', 
          action:function($container, $prompt){
            req_http("/apidoc/edit_user_role_of_doc",{user_id:userId,app_id:doc_app_id,role:5})
            return false; 
          } 
        },
        success : { 
          text: '开发员 / 编辑', 
          style: 'success', 
          action:function($container, $prompt){
            req_http("/apidoc/edit_user_role_of_doc",{user_id:userId,app_id:doc_app_id,role:3})
            return false; 
          } 
        },
        confirm  : { 
          text: '测试员', 
          style: 'info', 
          action:function($container, $prompt){
            req_http("/apidoc/edit_user_role_of_doc",{user_id:userId,app_id:doc_app_id,role:2})
            return false; 
          } 
        },
        remove : { 
          text: '参与者(可阅读或评论)', 
          style: 'warning', 
          action:function($container, $prompt){
            req_http("/apidoc/edit_user_role_of_doc",{user_id:userId,app_id:doc_app_id,role:1})
            return false; 
          } 
        },
        close : { 
          text: '取消', 
          style: 'danger', 
          action: function($container, $prompt){
            return true; 
          } 
        },
      }
    });
});

function req_http(url,data) {
  $.post(url,data,function(result){
      //内容,标题
      if (result.code == '500' && !result.msg) {
          my.msg('系统异常:请与管理员联系');
      } else {
          my.msg(result.msg);
      }
      result.wait = result.wait? result.wait : 3;
      //关闭弹出层
      
      setInterval(function() {
        my.closeAllFlavr()
        if (typeof (result.url) != "undefined") {
            //跳转
            window.location.href = result.url;
        }
      }, result.wait * 1000);
  });
  return false;
}

function flavr_info(message='',timer = 3,closeAll = false) {
  timer = timer?timer:3
  closeAll = closeAll?true:false
  new $.flavr({
    modal : false,
    content : message,
    position : 'bottom-mid', 
    autoclose : true, 
    timeout : timer*1000, 
    animateEntrance: 'fadeIn', 
    animateClosing : 'fadeOut',
    buttons : {},
    onLoad : function(){
        closeAll && this.closeAll()
    }, 
    onBuild : function(){ }, 
    onShow : function(){ }, 
    onHide : function(){}, 
    onClose : function(){} 
  });
}

/**
 * 去除html 标签
 * @Author   ZhaoXianFang
 * @DateTime 2018-06-19
 * @param    {[type]}     argument [description]
 * @return   {[type]}              [description]
 */
function delhtmltag(str) {
    return str ? str.replace(/<\/?.+?>/g, "") : '';
}

/**
 * ajax 请求
 * @Author   ZhaoXianFang
 * @DateTime 2018-06-14
 * @param    {[type]}     url        [请求地址]
 * @param    {[type]}     data       [请求数据]
 * @param    {[type]}     successFun [成功回调]
 * @param    {[type]}     errorFun   [失败回调]
 * @return   {[type]}                [description]
 */
function ajax_request(url, data, successFun, errorFun, reqtype) {
    flavr_info('请稍候...',10);
    reqtype = reqtype ? (reqtype.toUpperCase() == 'GET' ? 'GET' : 'POST') : 'POST';
    var contentTypeVal = 'application/x-www-form-urlencoded'; // 是否包含文件提交
    var processDataVal = true;
    if (data.__proto__.hasOwnProperty('append')) {
        contentTypeVal = false;
        processDataVal = false;
    }
    $.ajax({
        async: false,
        cache: false,
        url: url,
        type: reqtype,
        timeout: 30000, // 设置超时时间30秒
        contentType: contentTypeVal, //禁止false 表示ajax设置编码方式
        processData: processDataVal, //false禁止ajax将数据类型转换为字符串
        data: data,
        dataType: "json",
        error: function(jqXHR, textStatus, errorThrown) {
            // $.flavr.closeAll();
            errorFun && errorFun(jqXHR, textStatus, errorThrown);
        },
        success: function(suc) {
            // $.flavr.closeAll();
            // $('body').find('.flavr-container').each(function() {
            //     var a = $(this).data('flavr').instance;
            //     a.close()
            // });

            // new $.flavr({ closeOverlay : true, action:function(){ this.exit(); } 
            // });

            // new $.flavr({
            //   closeAll()
            // })
            successFun && successFun(suc);
        }
    });

}
function add_group(group_name) {
    flavr_info('请稍候...');
    ajax_request('/apidoc/document/group/add/'+doc_app_id, {'row[name]':group_name}, function(succ) {
          if (succ.code == 200) {
              flavr_info(succ.msg);
          } else {
              //内容,标题
              if (succ.code == '500' && !succ.msg) {
                  flavr_info('系统异常:请与管理员联系');
              } else {
                  // toastr.error(succ.msg, '出错啦');
                  flavr_info(succ.msg);
              }
          }
          if (typeof succ.url != undefined) {
              setInterval(function() {
                  //跳转
                  window.location.href = succ.url;
              }, succ.wait * 1000);
          }
      }, function(jqXHR, textStatus, errorThrown) {
          // console.log(jqXHR.responseText)
          // console.log(textStatus)
          /*弹出jqXHR对象的信息*/
          flavr_info(delhtmltag(jqXHR.responseText));
      },'post');
}

function delete_group(group_id) {
  new $.flavr({ 
      content : '请确认是否删除该接口组？',
      dialog : 'confirm', 
      onConfirm : function( $container, $prompt){
        // console.log('回车了',$prompt.val())
        return false; 
      }, 
      onCancel : function(){ 
        // console.log('取消')
      },
      buttons : {
        success : { 
          text: '确定', 
          style: 'success', 
          action:function($container, $prompt){
            flavr_info('请稍候...');
            ajax_request('/apidoc/document/group/delete/'+doc_app_id+'/'+group_id, {}, function(succ) {
                  if (succ.code == 200) {
                      flavr_info(succ.msg);
                  } else {
                      //内容,标题
                      if (succ.code == '500' && !succ.msg) {
                          flavr_info('系统异常:请与管理员联系');
                      } else {
                          // toastr.error(succ.msg, '出错啦');
                          flavr_info(succ.msg);
                      }
                  }
                  if (typeof succ.url != undefined) {
                      setInterval(function() {
                          //跳转
                          window.location.href = succ.url;
                      }, succ.wait * 1000);
                  }
              }, function(jqXHR, textStatus, errorThrown) {
                  // console.log(jqXHR.responseText)
                  // console.log(textStatus)
                  /*弹出jqXHR对象的信息*/
                  flavr_info(delhtmltag(jqXHR.responseText));
              },'post');
            return false; 
          } 
        },
        close : { 
          text: '取消', 
          style: 'danger', 
          action: function($container, $prompt){
            return true; 
          } 
        },
      }
    });
    
}

function delete_api(api_id) {

    new $.flavr({ 
      content : '请确认是否删除该接口？',
      dialog : 'confirm', 
      onConfirm : function( $container, $prompt){
        // console.log('回车了',$prompt.val())
        return false; 
      }, 
      onCancel : function(){ 
        // console.log('取消')
      },
      buttons : {
        success : { 
          text: '确定', 
          style: 'success', 
          action:function($container, $prompt){
            flavr_info('请稍候...');
              ajax_request('/apidoc/document/api/delete/'+doc_app_id+'/'+api_id, {}, function(succ) {
                    if (succ.code == 200) {
                        flavr_info(succ.msg);
                    } else {
                        //内容,标题
                        if (succ.code == '500' && !succ.msg) {
                            flavr_info('系统异常:请与管理员联系');
                        } else {
                            // toastr.error(succ.msg, '出错啦');
                            flavr_info(succ.msg);
                        }
                    }
                    if (typeof succ.url != undefined) {
                        setInterval(function() {
                            //跳转
                            window.location.href = succ.url;
                        }, succ.wait * 1000);
                    }
                }, function(jqXHR, textStatus, errorThrown) {
                    // console.log(jqXHR.responseText)
                    // console.log(textStatus)
                    /*弹出jqXHR对象的信息*/
                    flavr_info(delhtmltag(jqXHR.responseText));
                },'post');
            return false; 
          } 
        },
        close : { 
          text: '取消', 
          style: 'danger', 
          action: function($container, $prompt){
            return true; 
          } 
        },
      }
    });
    
}
function copy_api(api_id) {

    new $.flavr({ 
      content : '请确认是否复制该接口/文档？',
      dialog : 'confirm', 
      onConfirm : function( $container, $prompt){
        // console.log('回车了',$prompt.val())
        return false; 
      }, 
      onCancel : function(){ 
        // console.log('取消')
      },
      buttons : {
        success : { 
          text: '确定', 
          style: 'success', 
          action:function($container, $prompt){
            flavr_info('请稍候...');
              ajax_request('/apidoc/document/api/copy/'+doc_app_id+'/'+api_id, {}, function(succ) {
                    if (succ.code == 200) {
                        flavr_info(succ.msg);
                    } else {
                        //内容,标题
                        if (succ.code == '500' && !succ.msg) {
                            flavr_info('系统异常:请与管理员联系');
                        } else {
                            // toastr.error(succ.msg, '出错啦');
                            flavr_info(succ.msg);
                        }
                    }
                    if (typeof succ.url != undefined) {
                        setInterval(function() {
                            //跳转
                            window.location.href = succ.url;
                        }, succ.wait * 1000);
                    }
                }, function(jqXHR, textStatus, errorThrown) {
                    // console.log(jqXHR.responseText)
                    // console.log(textStatus)
                    /*弹出jqXHR对象的信息*/
                    flavr_info(delhtmltag(jqXHR.responseText));
                },'post');
            return false; 
          } 
        },
        close : { 
          text: '取消', 
          style: 'danger', 
          action: function($container, $prompt){
            return true; 
          } 
        },
      }
    });
    
}
// 修改文档组
function edit_group(group_id,group_name) {
    // console.log(group_id,group_name)

    new $.flavr({
      content : '修改文档组', 
      dialog : 'prompt',
      prompt : { placeholder: '输入文档组名称', value:group_name }, 
      onConfirm : function( $container, $prompt){
        // console.log('回车了',$prompt.val())
        return false; 
      }, 
      onCancel : function(){ 
        // console.log('取消')
      },
      buttons : { 
        close : { 
          text: '取消', 
          style: 'danger', 
          action: function($container, $prompt){ 
              console.log(group_id, $prompt,$prompt.val())

            return true; 
          } 
        }, 
        success : { 
          text: '确定', 
          style: 'success', 
          action:function($container, $prompt){ 
            // console.log($container, $prompt,$prompt.val())

            flavr_info('请稍候...');
            ajax_request('/apidoc/document/group/edit/'+doc_app_id+'/'+group_id, {'row[name]':$prompt.val()}, function(succ) {
                  if (succ.code == 200) {
                      flavr_info(succ.msg);
                  } else {
                      //内容,标题
                      if (succ.code == '500' && !succ.msg) {
                          flavr_info('系统异常:请与管理员联系');
                      } else {
                          // toastr.error(succ.msg, '出错啦');
                          flavr_info(succ.msg);
                      }
                  }
                  if (typeof succ.url != undefined) {
                      setInterval(function() {
                          //跳转
                          window.location.href = succ.url;
                      }, succ.wait * 1000);
                  }
              }, function(jqXHR, textStatus, errorThrown) {
                  // console.log(jqXHR.responseText)
                  // console.log(textStatus)
                  /*弹出jqXHR对象的信息*/
                  flavr_info(delhtmltag(jqXHR.responseText));
              },'post');

            return false; 
          } 
        }
      }

    });

}


$(function () {
if(typeof can_edit_doc != "undefined" || ( typeof can_edit_doc == "string" && can_edit_doc != '' ) ){
  $('.left-container').mouseRight({
      ele:'.menu-header',
      menu: [{
          itemName: "添加API接口",
          icon:"fa fa-plus",
          callback: function(dom_obj,e) {
            // console.log(dom_obj.data('group_id'),dom_obj['0'].innerText )
            window.location.href = '/apidoc/document/api/add/'+ doc_app_id +'/'+dom_obj.data('group_id')
          }
        }, {
          itemName: "添加GraphQL接口",
          icon:"fa fa-plus",
          callback: function(dom_obj,e) {
            // console.log(dom_obj.data('group_id'),dom_obj['0'].innerText )
            window.location.href = '/apidoc/document/api/add_graphql/'+ doc_app_id +'/'+dom_obj.data('group_id')
          }
        }, {
          itemName: "添加文档接口",
          icon:"fa fa-file-word-o",
          callback: function(dom_obj,e) {
            //跳转
            // console.log(dom_obj.data('group_id'),dom_obj['0'].innerText )
            window.location.href = '/apidoc/document/api/addmd/'+ doc_app_id +'/'+dom_obj.data('group_id')
          }
        },{
          itemName: "修改接口组",
          icon:"fa fa-pencil",
          callback: function(dom_obj,e) {
            edit_group(dom_obj.data('group_id'),dom_obj['0'].innerText)
          }
        },{
          itemName: "删除接口组",
          icon:"fa fa-trash",
          callback: function(dom_obj,e) {
            console.log(dom_obj.data('group_id'),dom_obj['0'].innerText )
            delete_group(dom_obj.data('group_id'))
          }
    }]});

    $('.left-container').mouseRight({
      ele:'.apidoc-menu-item',
      menu: [
        {
          itemName: "修改",
          icon:"fa fa-pencil",
          callback: function(dom_obj,e) {
            console.log(dom_obj.data('api_id'),dom_obj['0'].innerText )
            window.location.href = '/apidoc/document/api/edit/'+ doc_app_id +'/'+dom_obj.data('api_id')
          }
        },
        {
          itemName: "删除",
          icon:"fa fa-trash",
          callback: function(dom_obj,e) {
            console.log(dom_obj.data('api_id'),dom_obj['0'].innerText )
            delete_api(dom_obj.data('api_id'))
          }
        },
        {
          itemName: "复制",
          icon:"fa fa-copy",
          callback: function(dom_obj,e) {
            console.log(dom_obj.data('api_id'),dom_obj['0'].innerText )
            copy_api(dom_obj.data('api_id'))
          }
        }
        
    ]});
  }
})

/**
 * api 文档 动态添加移除 header和body 开始
 */
$(".append_headers_templata").off()
$(".btn-remove-header").off()
$(".append_body_templata").off()
$(".btn-remove-body").off()

// 追加header
$(".append_headers_templata").click(function () {
    // clone(true); 连同事件一起
    var child = $(".selestlist_headers").parent().children(".selestlist_headers:first-child").clone(true);
    //清除克隆的数据
    child.find(":input").each(function(i){
        $(this).val("");
    });
    child.find("select").each(function(i,j){
        //选择第一个
        var options = $(j).find("option");
        options.attr("selected", false);
        options.first().attr("selected", true);
    });

    // child.find('.change-type').val('default');
    child.find('.remove').show();

    child.find(".header-lable").text("");
    
    $("#add_headers_config").before(child);
});
//移除heder
$(document).on("click", ".btn-remove-header", function () {
    var row_length = $("#header-form-box").children(".selestlist_headers").length;
    if(row_length > 1){
        $(this).parent().parent('.row').remove();
    }else{
      my.msg('只剩最后一个啦')
    }
});

// 追加body
$(".append_body_templata").click(function () {
    // clone(true); 连同事件一起
    var child = $(".selestlist_body").parent().children(".selestlist_body:first-child").clone(true);
    //清除克隆的数据
    child.find(":input").each(function(i){
        $(this).val("");
    });
    child.find("select").each(function(i,j){
        //选择第一个
        var options = $(j).find("option");
        options.attr("selected", false);
        options.first().attr("selected", true);
    });

    // child.find('.change-type').val('default');
    child.find('.remove').show();

    child.find(".body-lable").text("");
    
    $("#add_body_config").before(child);
});
//移除body
$(document).on("click", ".btn-remove-body", function () {
    var row_length = $("#body-form-box").children(".selestlist_body").length;
    if(row_length > 1){
        $(this).parent().parent('.row').remove();
    }else{
      my.msg('只剩最后一个啦')
    }
});

/**
 * api 文档 动态添加移除 header和body 结束
 */


   $(function(){ 
　　 let menu_stat= $('#open_close_menu').data("stat");
     $('#open_close_menu').css("left",menu_stat? $('.left-container').width()+52 : 40 )
　　 

    $('#open_close_menu').click(function(){
      var stat = $(this).data('stat')
      if(stat){
        // console.log('关闭左侧')
        $(this).data('stat',0)
        $(".left-container").addClass("close_left_menu");
        $(".right-container").addClass("container_full_to_left");
        
      }else{
        // console.log('打开左侧')
        $(this).data('stat',1)
        $(".left-container").removeClass("close_left_menu");
        $(".right-container").removeClass("container_full_to_left");

      }
      
      $('#open_close_menu').css("left",stat? 40 : $('.left-container').width()+52 )
      
    });

  }); 
