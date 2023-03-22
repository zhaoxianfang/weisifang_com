/*=========================================================================================
    File Name: kanban.js
    Description: kanban plugin
    ----------------------------------------------------------------------------------------
    Item Name: Frest HTML Admin Template
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(document).ready(function () {
  var kanban_curr_el, kanban_curr_item_id, kanban_item_title, kanban_data, kanban_item, kanban_users;

  // Kanban Board and Item Data passed by json
  var kanban_board_data = [{
      id: "kanban-board-1",
      title: "Marketing",
      item: [{
          id: "11",
          title: "Facebook Campaign 😎",
          border: "success",
          dueDate: "Feb 6",
          comment: 1,
          attachment: 3,
          users: [
            FREST_PATH + "/images/portrait/small/avatar-s-11.jpg",
            FREST_PATH + "/images/portrait/small/avatar-s-12.jpg"
          ]
        },
        {
          id: "12",
          title: "Type Something",
          border: "info",
          image: FREST_PATH + "/images/banner/banner-21.jpg",
          dueDate: "Feb 10"
        },
        {
          id: "13",
          title: "Social Media Graphics",
          border: "warning",
          dueDate: "Jan 3",
          comment: 23,
          attachment: 4,
          users: [
            FREST_PATH + "/images/portrait/small/avatar-s-1.jpg",
            FREST_PATH + "/images/portrait/small/avatar-s-18.jpg"
          ]
        },
        {
          id: "14",
          title: "Book newspaper ads online in popular newspapers.",
          border: "danger",
          comment: 56,
          attachment: 2,
          users: [
            FREST_PATH + "/images/portrait/small/avatar-s-26.jpg",
            FREST_PATH + "/images/portrait/small/avatar-s-16.jpg"
          ]
        },
        {
          id: "15",
          title: "Twitter Marketing",
          border: "secondary"
        }
      ]
    },
    {
      id: "kanban-board-2",
      title: "UI Designing",
      item: [{
          id: "21",
          title: "Flat UI Kit Design",
          border: "secondary"
        },
        {
          id: "22",
          title: "Drag people onto a card to indicate that.",
          border: "info",
          dueDate: "Jan 1",
          comment: 8,
          users: [
            FREST_PATH + "/images/portrait/small/avatar-s-24.jpg",
            FREST_PATH + "/images/portrait/small/avatar-s-14.jpg"
          ]
        },
        {
          id: "23",
          title: "Application Design",
          border: "warning"
        },
        {
          id: "24",
          title: "BBQ Logo Design 😱",
          border: "primary",
          dueDate: "Jan 6",
          comment: 10,
          attachment: 6,
          badgeContent: "AK",
          badgeColor: "danger"
        }
      ]
    },
    {
      id: "kanban-board-3",
      title: "Developing",
      item: [{
          id: "31",
          title: "Database Management System (DBMS) is a collection of programs",
          border: "warning",
          dueDate: "Mar 1",
          comment: 10,
          users: [
            FREST_PATH + "/images/portrait/small/avatar-s-20.jpg",
            FREST_PATH + "/images/portrait/small/avatar-s-22.jpg",
            FREST_PATH + "/images/portrait/small/avatar-s-13.jpg"
          ]
        },
        {
          id: "32",
          title: "Admin Dashboard 🙂",
          border: "success",
          dueDate: "Mar 6",
          comment: 7,
          badgeContent: "AD",
          badgeColor: "primary"
        },
        {
          id: "33",
          title: "Fix bootstrap progress bar with & issue",
          border: "primary",
          dueDate: "Mar 9",
          users: [
            FREST_PATH + "/images/portrait/small/avatar-s-1.jpg",
            FREST_PATH + "/images/portrait/small/avatar-s-2.jpg"
          ]
        }
      ]
    }
  ];

  // Kanban Board
  var KanbanExample = new jKanban({
    element: "#kanban-wrapper", // selector of the kanban container
    buttonContent: "+ Add New Item", // text or html content of the board button

    // click on current kanban-item 点击看板item
    click: function (el) {
      console.log('点击看板item / 编辑item',el)
      // kanban-overlay and sidebar display block on click of kanban-item
      $(".kanban-overlay").addClass("show");
      $(".kanban-sidebar").addClass("show");

      // Set el to var kanban_curr_el, use this variable when updating title
      kanban_curr_el = el;

      // Extract  the kan ban item & id and set it to respective vars
      kanban_item_title = $(el).contents()[0].data;
      kanban_curr_item_id = $(el).attr("data-eid");

      // set edit title
      $(".edit-kanban-item .edit-kanban-item-title").val(kanban_item_title);
    },

    buttonClick: function (el, boardId) {
      console.log('点击 添加 看板item add 按钮',el, boardId)
      // create a form to add add new element
      var formItem = document.createElement("form");
      formItem.setAttribute("class", "itemform");
      formItem.innerHTML =
        '<div class="form-group">' +
        '<textarea class="form-control add-new-item" rows="2" autofocus required></textarea>' +
        "</div>" +
        '<div class="form-group">' +
        '<button type="submit" class="btn btn-primary btn-sm mr-50">Submit</button>' +
        '<button type="button" id="CancelBtn" class="btn btn-sm btn-danger">Cancel</button>' +
        "</div>";

      // add new item on submit click
      KanbanExample.addForm(boardId, formItem);
      formItem.addEventListener("submit", function (e) {

        console.log('点击 添加 看板item add 按钮 里面的提交按钮 ',el, boardId)

        e.preventDefault();
        var text = e.target[0].value;
        KanbanExample.addElement(boardId, {
          title: text
        });
        formItem.parentNode.removeChild(formItem);
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
      if (typeof $(board_item_el).attr("data-users") !== "undefined") {
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
      if (typeof $(board_item_el).attr("data-dueDate") !== "undefined") {
        board_item_dueDate =
          '<div class="kanban-due-date d-flex align-items-center mr-50">' +
          '<i class="bx bx-time-five font-size-small mr-25"></i>' +
          '<span class="font-size-small">' +
          $(board_item_el).attr("data-dueDate") +
          "</span>" +
          "</div>";
      }
      // check if comment is defined or not
      if (typeof $(board_item_el).attr("data-comment") !== "undefined") {
        board_item_comment =
          '<div class="kanban-comment d-flex align-items-center mr-50">' +
          '<i class="bx bx-message font-size-small mr-25"></i>' +
          '<span class="font-size-small">' +
          $(board_item_el).attr("data-comment") +
          "</span>" +
          "</div>";
      }
      // check if attachment is defined or not
      if (typeof $(board_item_el).attr("data-attachment") !== "undefined") {
        board_item_attachment =
          '<div class="kanban-attachment d-flex align-items-center">' +
          '<i class="bx bx-link-alt font-size-small mr-25"></i>' +
          '<span class="font-size-small">' +
          $(board_item_el).attr("data-attachment") +
          "</span>" +
          "</div>";
      }
      // check if Image is defined or not
      if (typeof $(board_item_el).attr("data-image") !== "undefined") {
        board_item_image =
          '<div class="kanban-image mb-1">' +
          '<img class="img-fluid" src=" ' +
          kanban_board_data[kanban_data].item[kanban_item].image +
          '" alt="kanban-image">';
        ("</div>");
      }
      // check if Badge is defined or not
      if (typeof $(board_item_el).attr("data-badgeContent") !== "undefined") {
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
        ) !== "undefined"
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
    KanbanExample.addBoards([{
      id: "kanban-" + i, // generate random id for each new kanban
      title: "Default Title"
    }]);
    var kanbanNewBoard = KanbanExample.findBoard("kanban-" + i)

    if (kanbanNewBoard) {
      $(".kanban-title-board").on("mouseenter", function () {
        $(this).attr("contenteditable", "true");
        $(this).addClass("line-ellipsis");
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

  });

  // Delete kanban board
  //---------------------
  $(document).on("click", ".kanban-delete", function (e) {

    var $id = $(this)
      .closest(".kanban-board")
      .attr("data-id");

      console.log('删除看板',$id)

    addEventListener("click", function () {
      KanbanExample.removeBoard($id);
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
    ".kanban-sidebar .delete-kanban-item, .kanban-sidebar .close-icon, .kanban-sidebar .update-kanban-item, .kanban-overlay"
  ).on("click", function () {
    $(".kanban-overlay").removeClass("show");
    $(".kanban-sidebar").removeClass("show");
  });

  // Updating Data Values to Fields
  // -------------------------------
  $(".update-kanban-item").on("click", function (e) {
    console.log('点击 右侧抽屉 保存按钮',kanban_curr_el,e)
    // var $edit_title = $(".edit-kanban-item .edit-kanban-item-title").val();
    // $(kanban_curr_el).txt($edit_title);
    e.preventDefault();
  });

  // Delete Kanban Item
  // -------------------
  $(".delete-kanban-item").on("click", function () {
    $delete_item = kanban_curr_item_id;
    // zhaoxianfang 注释了下面一行
    // addEventListener("click", function () {
      console.log('点击 右侧抽屉 删除item 按钮',kanban_curr_item_id)
      KanbanExample.removeElement($delete_item);
    // });
  });

  // Kanban Quill Editor
  // -------------------
  var composeMailEditor = new Quill(".snow-container .compose-editor", {
    modules: {
      toolbar: ".compose-quill-toolbar"
    },
    placeholder: "Write a Comment... ",
    theme: "snow"
  });

  // Making Title of Board editable
  // ------------------------------
  $(".kanban-title-board").on("mouseenter", function () {
    // zhaoxianfang change begin
    var _this = this
    var contents = $(this).html();
    $(this).blur(function() {
        if (contents!=$(this).html()){
            contents = $(this).html();

            console.log('编辑看板',contents, $(_this).parent().parent().data('id'))

        }
    });
    // 方式二
    $(_this).bind('input propertychange', function() {
      console.log('编辑看板 二 ',$(_this).html(), $(_this).parent().parent().data('id'))
        // var a = $(".box1").val();
        // $(".box2").val(a);
    })
    // zhaoxianfang change end

    // 是否可以编辑输入
    $(this).attr("contenteditable", "true");
    $(this).addClass("line-ellipsis");
  });

  // kanban Item - Pick-a-Date
  $(".edit-kanban-item-date").pickadate();

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
});
