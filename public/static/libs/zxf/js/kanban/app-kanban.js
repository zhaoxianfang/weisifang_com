/*=========================================================================================
    File Name: kanban.js
    Description: kanban plugin
    ----------------------------------------------------------------------------------------
    Item Name: Frest HTML Admin Template
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// $(document).ready(function () {
var Kanban =  {
  config:{
    addItemButton: '+ 添加一项',
    urls:{
      addBoard:'/kanban/index/addBoard',// 添加看板
      editBoardTitle:'/kanban/index/setBoardTitle',//修改看板标题
      deleteBoard:'/kanban/index/delBoard', // 删除看板
      addBoardItem:'/kanban/index/addBoardItem', // 添加看板 item
      editBoardItem:'/kanban/index/updateBoardItem',// 编辑/更新 看板 item
      deleteBoardItem:'/kanban/index/delBoardItem',// 删除 看板 item
      uploadImg:'',// 上传图片
    }
  },
  obj:{
    quill:null,
  },
  init:function (kanban_board_data,options) {

     this.config = $.extend({}, this.config, options)

      var kanban_curr_el, kanban_curr_item_id, kanban_item_title, kanban_data, kanban_item, kanban_users;

      // Kanban Board and Item Data passed by json
      // var kanban_board_data = [{
      //     id: "kanban-board-1",
      //     title: "Marketing",
      //     item: [{
      //         id: "11",
      //         title: "Facebook Campaign 😎",
      //         border: "success",
      //         dueDate: "Feb 6",
      //         comment: 1,
      //         attachment: 3,
      //         image: FREST_PATH + "/images/banner/banner-21.jpg",
      //         badgeContent: "AK", // 一般 badgeContent 与 users 二选一
      //         users: [
      //           FREST_PATH + "/images/portrait/small/avatar-s-11.jpg",
      //           FREST_PATH + "/images/portrait/small/avatar-s-12.jpg"
      //         ]
      //       },
      //       {
      //         id: "15",
      //         title: "Twitter Marketing",
      //         border: "secondary"
      //       }
      //     ]
      //   },
      // ];

      // Kanban Board
      var KanbanExample = new jKanban({
        element: "#kanban-wrapper", // selector of the kanban container
        buttonContent: this.config.addItemButton , //"+ 添加一项", // text or html content of the board button

        // click on current kanban-item 点击看板item
        click: function (el) {
          console.log('点击看板item / 编辑item',el)
          // kanban-overlay and sidebar display block on click of kanban-item
          $(".kanban-overlay").addClass("show");
          $(".kanban-sidebar").addClass("show");

          // Set el to var kanban_curr_el, use this variable when updating title
          kanban_curr_el = el;

          // Extract  the kan ban item & id and set it to respective vars
          // kanban_item_title = $(el).contents()[0].data;
          kanban_curr_item_id = $(el).attr("data-eid");
          var kanban_curr_border = $(el).attr("data-border");
          var kanban_curr_duedate = $(el).attr("data-duedate");
          var kanban_curr_users = $(el).attr("data-users") || '';
          var kanban_curr_image = $(el).attr("data-image") || '';
          var kanban_curr_content = $(el).attr("data-content") || '';

          // kanban_item_title = $(el).contents()[0].data || $(el).contents()[1].data
          // zhaoxianfang
          for (_node in $(el).contents())
          {
            if($(el).contents()[_node].nodeType == 3){ // 3 : text
              kanban_item_title = $(el).contents()[_node].data
            }
          }
          // set edit title
          $(".edit-kanban-item .edit-kanban-item-title").val(kanban_item_title);
          $(".edit-kanban-item .edit-kanban-item-date").val(kanban_curr_duedate);

          $(".edit-kanban-item #edit-kanban-item-badge").val(kanban_curr_border);
          // change bg color of select form-control
          $(".edit-kanban-item #edit-kanban-item-badge")
              .removeClass($(this).attr("class"))
              .addClass($(":selected", ".edit-kanban-item #edit-kanban-item-badge").attr("class") + " form-control text-white");
          
          // item 里面涉及到的用户头像
          $('#edit-kanban-item-users').empty();
          var users = kanban_curr_users.length > 0 ? kanban_curr_users.split(",") : [];
          var usersImg = ''
          for (var user_img in users){
            usersImg += '<img src="'+users[user_img]+'" height="36" width="36" alt="avtar img holder">'
          }
          $('#edit-kanban-item-users').html(usersImg);

          // $('.snow-container .compose-editor').html(kanban_curr_content);
          console.log(kanban_curr_content)
          // Kanban.obj.quill.insertText(0, 'Hello', 'link', 'https://world.com');
          // Kanban.obj.quill.insertEmbed(10, 'image', 'https://quilljs.com/images/cloud.png');
          Kanban.obj.quill.pasteHTML(kanban_curr_content);
          // $('#coverAttach').attr('src',kanban_curr_image);
          // $('#coverAttach').val(kanban_curr_image);
        },

        buttonClick: function (el, boardId) {
          
          console.log('点击 添加 看板item add 按钮',el, boardId)
          // create a form to add add new element
          var formItem = document.createElement("form");
          formItem.setAttribute("class", "itemform");
          formItem.innerHTML =
            '<div class="form-group">' +
            '<textarea class="form-control add-new-item" rows="2" autofocus required placeholder="请输入.."></textarea>' +
            "</div>" +
            '<div class="form-group">' +
            '<button type="submit" class="btn btn-primary btn-sm mr-50">提交</button>' +
            '<button type="button" id="CancelBtn" class="btn btn-sm btn-danger">取消</button>' +
            "</div>";

          // add new item on submit click
          KanbanExample.addForm(boardId, formItem);
          formItem.addEventListener("submit", function (e) {

            console.log('点击 添加 看板item add 按钮 里面的提交按钮 ',el, boardId)
            e.preventDefault();
              var text = e.target[0].value;
              // KanbanExample.addElement(boardId, {
              //   title: text + ' test',
              // });
            
            Kanban.request(Kanban.config.urls.addBoardItem,{board_id:boardId,title:text},function(succ) {
              console.log('添加成功',succ)

              
              KanbanExample.customizeAddElement(boardId, succ);
              formItem.parentNode.removeChild(formItem);
            })

          });
          $(document).on("click", "#CancelBtn", function () {
            $(this).closest(formItem).remove();
          })
        },
        addItemButton: true, // add a button to board for easy item creation
        boards: kanban_board_data // data passed from defined variable
      });

      // Add html for Custom Data-attribute to Kanban item
      var board_item_id, board_item_el;
      // Kanban board loop
      for (kanban_data in kanban_board_data) {
        // Kanban board items loop
        for (kanban_item in kanban_board_data[kanban_data].item) {
          var board_item_details = kanban_board_data[kanban_data].item[kanban_item]; // set item details
          board_item_id = $(board_item_details).attr("id"); // set 'id' attribute of kanban-item

          (board_item_el = KanbanExample.findElement(board_item_id)), // find element of kanban-item by ID
          (board_item_users = board_item_dueDate = board_item_comment = board_item_attachment = board_item_image = board_item_badge =
            " ");

          // check if users are defined or not and loop it for getting value from user's array
          if (typeof $(board_item_el).attr("data-users") !== "undefined" && $(board_item_el).attr("data-users") !== "" ) {
            for (kanban_users in kanban_board_data[kanban_data].item[kanban_item].users) {
              board_item_users +=
                '<li class="avatar pull-up my-0">' +
                '<img class="media-object rounded-circle" src=" ' +
                kanban_board_data[kanban_data].item[kanban_item].users[kanban_users] +
                '" alt="Avatar" height="24" width="24">' +
                "</li>";
            }
          }
          // check if dueDate is defined or not
          if (typeof $(board_item_el).attr("data-dueDate") !== "undefined" && $(board_item_el).attr("data-dueDate") !== "" ) {
            board_item_dueDate =
              '<div class="kanban-due-date d-flex align-items-center mr-50">' +
              '<i class="bx bx-time-five font-size-small mr-25"></i>' +
              '<span class="font-size-small">' +
              $(board_item_el).attr("data-dueDate") +
              "</span>" +
              "</div>";
          }
          // check if comment is defined or not
          if (typeof $(board_item_el).attr("data-comment") !== "undefined" && $(board_item_el).attr("data-comment") !== "" ) {
            board_item_comment =
              '<div class="kanban-comment d-flex align-items-center mr-50">' +
              '<i class="bx bx-message font-size-small mr-25"></i>' +
              '<span class="font-size-small">' +
              $(board_item_el).attr("data-comment") +
              "</span>" +
              "</div>";
          }
          // check if attachment is defined or not
          if (typeof $(board_item_el).attr("data-attachment") !== "undefined" && $(board_item_el).attr("data-attachment") !== "" ) {
            board_item_attachment =
              '<div class="kanban-attachment d-flex align-items-center">' +
              '<i class="bx bx-link-alt font-size-small mr-25"></i>' +
              '<span class="font-size-small">' +
              $(board_item_el).attr("data-attachment") +
              "</span>" +
              "</div>";
          }

          // check if Image is defined or not
          if (typeof $(board_item_el).attr("data-image") !== "undefined" && $(board_item_el).attr("data-image") !== "" ) {
            board_item_image =
              '<div class="kanban-image mb-1">' +
              '<img class="img-fluid" src=" ' +
              kanban_board_data[kanban_data].item[kanban_item].image +
              '" alt="kanban-image">';
            ("</div>");
          }
          // check if Badge is defined or not
          if (typeof $(board_item_el).attr("data-badgeContent") !== "undefined" && $(board_item_el).attr("data-badgeContent") !== "" ) {
            board_item_badge =
              '<div class="kanban-badge">' +
              '<div class="badge-circle badge-circle-sm badge-circle-light-' +
              kanban_board_data[kanban_data].item[kanban_item].badgeColor +
              ' font-size-small font-weight-bold">' +
              kanban_board_data[kanban_data].item[kanban_item].badgeContent +
              "</div>";
            ("</div>");
          }
          // add custom 'kanban-footer'
          if (
            typeof (
              $(board_item_el).attr("data-dueDate") ||
              $(board_item_el).attr("data-comment") ||
              $(board_item_el).attr("data-users") ||
              $(board_item_el).attr("data-attachment")
            ) !== "undefined" && (
              $(board_item_el).attr("data-dueDate") != '' &&
              $(board_item_el).attr("data-comment") != '' &&
              $(board_item_el).attr("data-users") != '' &&
              $(board_item_el).attr("data-attachment")
            )

          ) {
            $(board_item_el).append(
              '<div class="kanban-footer d-flex justify-content-between mt-1">' +
              '<div class="kanban-footer-left d-flex">' +
              board_item_dueDate +
              board_item_comment +
              board_item_attachment +
              "</div>" +
              '<div class="kanban-footer-right">' +
              '<div class="kanban-users">' +
              board_item_badge +
              '<ul class="list-unstyled users-list m-0 d-flex align-items-center">' +
              board_item_users +
              "</ul>" +
              "</div>" +
              "</div>" +
              "</div>"
            );
          }
          // add Image prepend to 'kanban-Item'
          if (typeof $(board_item_el).attr("data-image") !== "undefined") {
            $(board_item_el).prepend(board_item_image);
          }
        }
      }

      // Add new kanban board
      //---------------------
      var addBoardDefault = document.getElementById("add-kanban");
      var i = 1;
      addBoardDefault.addEventListener("click", function () {
        console.log('添加看板')

        Kanban.request(Kanban.config.urls.addBoard,{},function(succ) {
          console.log('添加成功',succ)
        

            // KanbanExample.addBoards([{
            //   id: "kanban-" + i, // generate random id for each new kanban
            //   title: "Default Title"
            // }]);
            KanbanExample.addBoards([succ]);

            // var kanbanNewBoard = KanbanExample.findBoard("kanban-" + i)
            var kanbanNewBoard = KanbanExample.findBoard(succ.id)

            if (kanbanNewBoard) {
              $(".kanban-title-board").on("mouseenter", function () {
                Kanban.addBoard(this)
              });
              kanbanNewBoardData =
                '<div class="dropdown">' +
                '<div class="dropdown-toggle cursor-pointer" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></div>' +
                '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"> ' +
                '<a class="dropdown-item kanban-copylink" href="#"><i class="bx bx-link mr-50"></i>复制链接</a>' +
                '<a class="dropdown-item kanban-delete" id="kanban-delete" href="#"><i class="bx bx-trash mr-50"></i>删除</a>' +
                "</div>" + "</div>";
              var kanbanNewDropdown = $(kanbanNewBoard).find("header");
              $(kanbanNewDropdown).append(kanbanNewBoardData);
            }
            i++;
        })

      });

      // Delete kanban board
      //---------------------
      $(document).on("click", ".kanban-delete", function (e) {

        var $id = $(this)
          .closest(".kanban-board")
          .attr("data-id");

        console.log('删除看板',$id)

        addEventListener("click", function () {
          // KanbanExample.removeBoard($id);
          Kanban.request(Kanban.config.urls.deleteBoard,{board_id:$id},function(suss) {
            KanbanExample.removeBoard($id);
          })
        });
      });

      // copy kanban board
      //---------------------
      $(document).on("click", ".kanban-copylink", function (e) {

        var $id = $(this)
          .closest(".kanban-board")
          .attr("data-id");

          console.log('复制看板链接',$id)

          // addEventListener("click", function () {
          //   KanbanExample.removeBoard($id);
          // });
      });

      // Kanban board dropdown
      // ---------------------

      var kanban_dropdown = document.createElement("div");
      kanban_dropdown.setAttribute("class", "dropdown");

      dropdown();

      function dropdown() {
        kanban_dropdown.innerHTML =
          '<div class="dropdown-toggle cursor-pointer" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></div>' +
          '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"> ' +
          '<a class="dropdown-item kanban-copylink" href="#"><i class="bx bx-link-alt mr-50"></i>复制链接</a>' +
          '<a class="dropdown-item kanban-delete" id="kanban-delete" href="#"><i class="bx bx-trash mr-50"></i>删除</a>' +
          "</div>";
        if (!$(".kanban-board-header div").hasClass("dropdown")) {
          $(".kanban-board-header").append(kanban_dropdown);
        }
      }

      // Kanban-overlay and sidebar hide
      // --------------------------------------------
      $(
        // zhaoxianfang 删除 【.kanban-sidebar .update-kanban-item,】
        // ".kanban-sidebar .delete-kanban-item, .kanban-sidebar .close-icon, .kanban-sidebar .update-kanban-item, .kanban-overlay"
        ".kanban-sidebar .delete-kanban-item, .kanban-sidebar .close-icon, .kanban-overlay"
      ).on("click", function () {
        $(".kanban-overlay").removeClass("show");
        $(".kanban-sidebar").removeClass("show");
      });

      // Updating Data Values to Fields
      // -------------------------------
      $(".update-kanban-item").on("click", function (e) {
        e.preventDefault();
        console.log('点击 右侧抽屉 保存按钮',kanban_curr_el,e)

        var $edit_title = $(".edit-kanban-item .edit-kanban-item-title").val();
        var $edit_badge = $(".edit-kanban-item #edit-kanban-item-badge").val();
        var $edit_dueDate = $(".edit-kanban-item .edit-kanban-item-date").pickadate('picker').get('highlight', 'yyyy-mm-dd');
        var $quill_editor = $(".edit-kanban-item .ql-editor")[0].innerHTML
        var $image = $('#coverAttach').get(0).files[0]

        var formdata = {
          item_id:$(kanban_curr_el).data('eid'),
          title:$edit_title,
          badge:$edit_badge,
          dueDate:$edit_dueDate,
          content:$quill_editor,
          image:$image
        };
        
        // $(kanban_curr_el).text($edit_title);
        // 修改dom
        Kanban.request(Kanban.config.urls.editBoardItem,formdata,function(succ) {
          $(kanban_curr_el).text($edit_title);

          // 成功时候取消show
          $(".kanban-overlay").removeClass("show");
          $(".kanban-sidebar").removeClass("show");
        })
        // $(kanban_curr_el).text($edit_title);
        // console.log($edit_title)
       
      });

      // Delete Kanban Item
      // -------------------
      $(".delete-kanban-item").on("click", function () {
        $delete_item = kanban_curr_item_id;
        // zhaoxianfang 注释了下面一行
        // addEventListener("click", function () {
          console.log('点击 右侧抽屉 删除item 按钮',kanban_curr_item_id)

          // KanbanExample.removeElement($delete_item);

          Kanban.request(Kanban.config.urls.deleteBoardItem,{item_id:kanban_curr_item_id},function(succ) {
            KanbanExample.removeElement($delete_item);
          })
        // });
      });

      // Kanban Quill Editor
      // -------------------
      // var composeMailEditor = new Quill(".snow-container .compose-editor", {
      Kanban.obj.quill = new Quill(".snow-container .compose-editor", {
        modules: {
          toolbar: ".compose-quill-toolbar"
        },
        placeholder: "可在此输入... ",
        theme: "snow"
      });

      // Making Title of Board editable
      // ------------------------------
      $(".kanban-title-board").on("mouseenter", function () {
        // zhaoxianfang change begin
        Kanban.addBoard(this)
      });

      // kanban Item - Pick-a-Date
      $(".edit-kanban-item-date").pickadate({
        weekdaysShort: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
        monthsFull: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        formatSubmit: 'yyyy/mm/dd',
        format: 'yyyy/mm/dd',
        today: '今天',
        clear: '清除',
        close: '关闭',
        selectMonths: true,
        selectYears: true
      });

      // Perfect Scrollbar - card-content on kanban-sidebar
      if ($(".kanban-sidebar .edit-kanban-item .card-content").length > 0) {
        new PerfectScrollbar(".card-content", {
          wheelPropagation: false
        });
      }

      // select default bg color as selected option
      $("select").addClass($(":selected", this).attr("class"));

      // change bg color of select form-control
      $("select").change(function () {
        $(this)
          .removeClass($(this).attr("class"))
          .addClass($(":selected", this).attr("class") + " form-control text-white");
      });
  },
  addBoard:function(obj) {
    // var _this = this

      var _contents = $(obj).html();
      console.log('111',_contents)
      $(obj).blur(function() {
          if (_contents!=$(obj).html()){
              contents = $(obj).html();

              console.log('编辑看板',contents, $(obj).parent().parent().data('id'))

              // my.throttle(function() {
                Kanban.request(Kanban.config.urls.editBoardTitle,{
                  board_id:$(obj).parent().parent().data('id'),
                  board_title:$(obj).html()
                },function(suss) {
                  // console.log('ok',suss,_contents)
                  // 修改标题
                  // $(obj).html(contents);
                },function(res) {
                  // console.log('失败',res,_contents)
                  // 失败 不修改新标题
                  $(obj).html(_contents);
                })
              // }, 1000)

          }
      });
      // 方式二
      $(obj).bind('input propertychange', function() {
        console.log('编辑看板 二 ',$(obj).html(), $(obj).parent().parent().data('id'))
        my.throttle(function() {
          Kanban.request(Kanban.config.urls.editBoardTitle,{
            board_id:$(obj).parent().parent().data('id'),
            board_title:$(obj).html()
          },function(suss) {
            // 修改标题
            // $(obj).html(contents);
          },function(res) {
            // 失败 不修改新标题
            $(obj).html(_contents);
          })
        }, 2000)
      })
      // zhaoxianfang change end

      // 是否可以编辑输入
      $(obj).attr("contenteditable", "true");
      $(obj).addClass("line-ellipsis");
  },
  // 网络请求
  request:function(url,formdata,successFun, errorFun){
    my.ajax(url, formdata, function(succ) {
            //返回数据 非200：失败 ；200：成功
            if (succ.code == 200) {
                my.msg(succ.msg);
                successFun && successFun(succ.data);
            } else {
                //内容,标题
                if (succ.code == '500' && !succ.msg) {
                    my.msg('系统异常:请与管理员联系');
                } else {
                    my.msg(succ.msg);
                }
                errorFun && errorFun(succ);
            }


            // if (typeof (succ.url) != "undefined") {
            //   setInterval(function() {
            //       //跳转
            //       window.location.href = succ.url;
            //   }, succ.wait * 1000);
            // }
        }, function(jqXHR, textStatus, errorThrown) {
            // console.log(jqXHR.responseText)
            // console.log(textStatus)
            /*弹出jqXHR对象的信息*/
            var errorRes = my.delhtmltag(jqXHR.responseText)
            my.msg(errorRes);
            try {
                if(typeof(errorRes) == 'string') { // json 解析
                    errorRes= JSON.parse(errorRes);
                }
            } catch (err) {
            }
            errorFun && errorFun(succ);
        },'POST');
  }
}
// });

// window.Kanban = Kanban
