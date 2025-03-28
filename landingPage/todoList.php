    <?php
    include './config/config.php';
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>To-Do App</title>
        <script src="./assets/css/tailwind.js"></script>

        <!-- <link rel="stylesheet" type="text/css" href="./assets/fontAwesome/index.css"> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="style.css">
        <style>
            .scrollbar::-webkit-scrollbar {
                display: none;
            }

            .context-menu {
                display: none;
                position: absolute;
                background: white;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
                border-radius: 5px;
                min-width: 150px;
                z-index: 1000;
                padding: 10px 0;
            }

            .active-list {
                padding: 10px;
                background-color: #e5e7eb;
            }
        </style>
    </head>

    <body class="bg-gray-100 relative">
        <div class="context-menu px-4" id="contextMenu">
            <ul>
                <li class="py-2 px-4 hover:bg-slate-100 duplicateTask" onclick="duplicate()">✏️ Duplicate List</li>
                <li class="py-2 px-4 hover:bg-slate-100 editTask" onclick="editTask()"><span><i class="fa-regular fa-pen-to-square ml-1"></i></span> Edit Task</li>
                <li class="py-2 px-4 hover:bg-slate-100 deleteTask" onclick="deleteTask()">🗑 Delete Tassk</li>
            </ul>
        </div>

        <!-- Main Container -->
        <div class="flex h-screen">
            <!-- Sidebar (Hidden on Small Screens) -->
            <aside class="w-64 bg-white p-2  shadow-md hidden md:block relative">
                <div class="flex items-center space-x-3">
                    <!-- <img src="https://via.placeholder.com/40" alt="User Avatar" class="w-10 h-10 rounded-full"> -->
                    <div>
                        <h2 class="text-lg font-semibold heading"><?php echo $_SESSION['loginUserData']['name']; ?></h2>
                        <p class="text-sm text-gray-500"><?php echo $_SESSION['loginUserData']['email']; ?></p>
                    </div>
                </div>

                <!-- use for search -->
                <form action="" method="post" id="seachSumit">
                    <input type="text" placeholder="Search" onchange="" id="search" name="search" class="w-full p-2 mt-4 border-b-2 border-black-600 outline-none">
                    <button class="hidden" type="submit">Submit</button>
                </form>

                <nav class="mt-5 border-b-2  ">
                    <ul class="space-y-2 defaultList">
                        <li class="active-list p-2 rounded-md hover:bg-gray-200 cursor-pointer defaultListItem" data-id="myDay">📅 My Day</li>
                        <li class=" p-2 rounded-md hover:bg-gray-200 cursor-pointer defaultListItem" data-id="important">⭐ Important</li>
                        <li class=" p-2 rounded-md hover:bg-gray-200 cursor-pointer defaultListItem" data-id="plan">📆 Planned</li>
                        <li class=" p-2 rounded-md hover:bg-gray-200 cursor-pointer defaultListItem" data-id="completed">✅  Completed</li>
                        <li class=" p-2 rounded-md hover:bg-gray-200 cursor-pointer defaultListItem" data-id="all">📋 All</li>
                        <li class=" p-2 rounded-md hover:bg-gray-200 cursor-pointer defaultListItem" data-id="tasks">📋 Tasks</li>
                    </ul>
                </nav>

                <nav class="mt-2 h-[30vh] overflow-y-auto scrollbar">
                    <input type="hidden" class="activeInput">
                    <ul class="space-y-2 optionList">

                    </ul>
                </nav>

                <div class="absolute bottom-0 bg-gray-200 -left-1 p-1 w-full shadow-md">
                    <div class="flex justify-center items-center gap-2 addList cursor-pointer">
                        <span class="text-2xl">+</span>
                        <p>New List</p>
                    </div>
                </div>

            </aside>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col bg-cover bg-center p-5"
                style="background-image: url('https://source.unsplash.com/random/1600x900');">
                <!-- Header -->
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold">My Day</h1>
                    <div class="space-x-2">
                        <button class="p-2 rounded-full bg-gray-200 hover:bg-gray-300">➕</button>
                        <button class="p-2 rounded-full bg-gray-200 hover:bg-gray-300">💡</button>
                    </div>
                </div>
                <!-- Task List -->
                <div id="taskList" class="mt-5 p-3 w-full overflow-auto scrollBar">
                    <!-- <div class="flex items-center space-x-2 w-full ">
                        <input type="checkbox" class="w-5 h-5">
                        <p class="text-lg my-2">Hello</p>
                    </div> -->
                </div>

                <!-- Add Task Input -->
                <div class="mt-auto w-full flex justify-center items-center ">
                    <form action="./config/server.php" id="addTask" method="POST" class="w-full flex gap-2">
                        <input type="hidden" name="addTask" placeholder="Add Task" class="w-full hidden">
                        <input type="hidden" name="userId" value="<?php echo $_SESSION['loginId'] ?? ''; ?>">
                        <input type="text" name="task" id="taskInput" placeholder="Add a task"
                            class="w-full p-3 rounded-lg shadow-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <button type="submit" id="add" class="w-[80px]  bg-blue-500 tex-white p-2 rounded-md hover:bg-blue-600">
                            Add
                        </button>
                    </form>
                </div>

            </main>

            <!-- sidebar  -->
            <div class="w-92 bg-white shadow-lg relative rounded-lg p-4 update hidden">
                <span id="closeBtn" class="absolute right-5"><i class="fa-solid fa-xmark"></i></span>
                <div class="flex items-center space-x-2 border-b pb-2">
                    <form action="./config/server.php" method="POST" id="updateForm" class="flex items-center space-x-2 border-b pb-2">
                        <input type="hidden" name="update" id="update" value="1">
                        <input type="checkbox" class="w-5 h-5">
                        <input type="text" value="" name="updateText" id="showData" class="w-full px-2 border-none focus:ring-0 outline-none text-lg font-semibold">
                        <button type="submit" id="updateData" class="hidden">update</button>
                    </form>
                </div>
                <button class="text-blue-500 text-sm mt-2">+ Add step</button>
                <div class="mt-4 bg-blue-100 text-blue-500 px-2 py-1 rounded-md flex justify-between items-center">
                    <span>Added to My Day</span>
                    <button class="text-gray-500">&times;</button>
                </div>
                <div class="mt-4 space-y-2">
                    <button class="flex items-center w-full p-2 rounded-md hover:bg-gray-100">
                        🔔 Remind me
                    </button>
                    <button class="flex items-center w-full p-2 rounded-md hover:bg-gray-100">
                        📅 Add due date
                    </button>
                    <button class="flex items-center w-full p-2 rounded-md hover:bg-gray-100">
                        📆 Repeat
                    </button>
                </div>
                <button class="flex items-center w-full p-2 mt-4 rounded-md border border-gray-300 hover:bg-gray-100">
                    📎 Add file
                </button>
                <textarea class="w-full mt-2 p-2 border rounded-md" placeholder="Add note"></textarea>
                <div class="text-gray-500 text-sm mt-4 flex justify-between">
                    <span>Created 15 minutes ago</span>
                    <button class="text-red-500">🗑</button>
                </div>
            </div>
        </div>

        <script src="./assets/js/jquery-3.6.4.min.js"></script>
        <script src="./assets/js/custom.js"></script>

        <script>
            $(document).ready(function() {
                fetchTodo()
                fetchAllLists()

                let activeListId = $('.active-list').data('id')
                fetchTodo(activeListId)

                let defaultListId
                $(document).on('click', '.defaultListItem', function() {
                    $('.defaultListItem').removeClass('active-list')
                    $(this).addClass('active-list')
                    defaultListId = $(this).attr('data-id');
                    fetchTodo(defaultListId)
                })


                let cacheData = []
                $(document).on('click', '#add', function(e) {
                    e.preventDefault();
                    let form = $('#addTask');
                    let url = form.attr('action');
                    let method = form.attr('method');
                    var formData = form.serialize();
                    formData += "&id=" + defaultListId;
                    $.ajax({
                        type: method,
                        url: url,
                        data: formData,
                        success: function(response) {
                            let res = JSON.parse(response)
                            console.log(res)
                            renderData(res.taskList)
                            $("#taskInput").val("")
                            cacheData.push(res.taskList)
                        },
                        error: function(response) {
                            console.log("this is error in this code")
                        }
                    })
                })


                function fetchTodo(id = "") {   
                    let request = {
                        todoList: true,
                    }
                    if (id != "") {
                        request.id = id
                    }
                    $.ajax({
                        type: 'POST',
                        url: './config/server.php',
                        data: request,
                        success: function(response) {
                            let res = JSON.parse(response)
                            if (res.success) {
                                renderData(res.tasks)
                                cacheData.push(res.tasks)
                            }
                        },
                        error:function(response) {
                            console.log("this is error in this code")
                        }
                    })
                }


                function renderData(data) {
                    let taskList = document.getElementById('taskList');
                    taskList.innerHTML = ``;
                    if (data.length > 0) {
                        data.map((item, index) => {

                            let is_imp
                            if(item.is_imp==1){
                                is_imp="fa-solid"
                            }else{
                                is_imp="fa-regular"
                            }

                            let card = document.createElement('div');

                            card.classList = `flex items-center w-full removeImp${item.id}`;

                            let newTask = document.createElement('div');
                            newTask.classList = "flex items-center justify-between w-full mt-2 bg-white p-3 rounded-lg shadow-md";

                            newTask.innerHTML = `<div class="flex items-center space-x-2">
                                                    <input type="checkbox" class="w-5 h-5 toggleCheckBox iscomp${item.id}" data-id="${item.id}" is_comp="${item.is_comp}" >
                                                    <p class="text-lg text-gray-800">${item.task}</p>
                                                </div>`;

                            let actionBtn = document.createElement('div');
                            actionBtn.classList = "text-red-500 hover:text-red-700 flex gap-5 cursor-pointer p-2";
                            actionBtn.innerHTML = `<i class="fa-solid fa-trash delete" name=${item.id}></i>
                                                   <i class="fa-solid fa-pen-to-square edit" name=${item.id}></i>
                                                   <i class="taskStar isImp${item.id}  fa fa-star"  data-id="${item.id}" data-imp="${item.is_imp}"></i>`;
                                                   
                            newTask.appendChild(actionBtn);
                            card.appendChild(newTask);
                            taskList.appendChild(card);
                        });
                    }
                }


                // add to important task list
                $(document).on('click', '.taskStar', function() {
                    $(this).toggleClass('fa-regular fa-solid');
                    let id = $(this).attr('data-id');
                    let imp = $(this).attr('data-imp');
                    $.ajax({
                        type: 'POST',
                        url: './config/server.php',
                        data:{
                            updateImp: true,
                            id:id,
                            imp:imp
                        },
                        success:function(res){
                            let response= JSON.parse(res)
                            // console.log(response)
                            let is_imp
                            if(response.success){
                                if(imp==0){
                                    is_imp="fa-regular"
                                    $('.isImp'+id).removeClass('fa-regular').addClass('fa-solid')
                                    $('.isImp'+id).attr('data-imp', 1)
                                }else{
                                    is_imp="fa-solid"
                                    let isImp = $('.defaultListItem').attr('data-id')
                                    $('.isImp'+id).addClass('fa-regular').removeClass('fa-solid')
                                    $('.isImp'+id).attr('data-imp', 0)
                                    let getId= $('.active-list').attr('data-id')
                                    if(getId==="important"){
                                        $('.removeImp'+id).remove()

                                    }
                                }
                            }
                        }
                    })
                });


                // check box code 
                $(document).on('click', '.toggleCheckBox', function(){
                    let id= $(this).attr('data-id');
                    let imp= $(this).attr('is_comp');
                    $.ajax({
                        type: 'POST',
                        url: './config/server.php',
                        data:{ 
                            updateComp: true,
                            id:id,
                            imp:imp
                        },
                        success:function(res){
                            let response = JSON.parse(res)
                            console.log(response)
                        },
                        error:function(xhr, status, error){
                            console.log(xhr.responseText);
                        }
                    })
                    
                })






                $(document).on("click", ".delete", function() {
                    let id = $(this).attr('name');
                    $.ajax({
                        type: "POST",
                        url: './config/server.php',
                        data: {
                            delete: true,
                            id: id
                        },
                        success: function(res) {
                            let response = JSON.parse(res)
                            fetchTodo()
                        },
                        error: function(res) {
                            console.log("this is errror")
                        }
                    })
                })


                let id
                $(document).on("click", ".edit", function(e) {
                    $(".update").removeClass('hidden')
                    id = $(this).attr('name')
                    cacheData[0].filter((item, index) => {
                        if (item.id == id) {
                            let task = item.task
                            document.querySelector('#showData').value = task
                        }
                    })
                })


                $(document).on("click", "#closeBtn", function(e) {
                    $(".update").addClass('hidden')
                })


                $(document).on("click", "#updateData", function(e) {
                    e.preventDefault()
                    let form = $('#updateForm')
                    let url = form.attr('action')
                    let method = form.attr('method')
                    // let id = $('.edit').attr('name')
                    $.ajax({
                        type: method,
                        url: url,
                        data: {
                            id: id,
                            task: $('#showData').val(),
                            update: $('#update').val()
                        },
                        success: function(res) {
                            let response = JSON.parse(res)
                            fetchTodo()
                        },
                        error: function() {
                            console.log("this is error")
                        }
                    })
                })
















                //start left-bottom functionaliy 
                function getMaxOrZero(arr) {
                    return arr.length ? Math.max(...arr) : 0;
                }

                // addListOptions 
                $(document).on("click", '.addList', function(e) {
                    let allList = $('.listSpan');
                    let temp_list = 'Untitled List';
                    let list = 'Untitled List';
                    let list_no = 0;
                    if (allList.length > 0) {
                        let arrTemp = []
                        allList.each(function(item, index) {
                            let temp_list = $(this).attr('temp_list');
                            let subTempList = temp_list.slice(0, 13)
                            let list_no = parseInt($(this).attr('list_no'));
                            if (subTempList === 'Untitled List') {
                                arrTemp.push(list_no);
                            }
                        });
                        let maxNo = getMaxOrZero(arrTemp)
                        list_no = parseInt(maxNo) + 1;
                        list = 'Untitled List (' + list_no + ')';
                        temp_list = 'Untitled List(' + list_no + ')';
                    }
                    $.ajax({
                        type: 'Post',
                        url: 'config/server.php',
                        data: {
                            addList: true,
                            listName: list,
                            temp_list: temp_list,
                            list_no: list_no
                        },
                        success: function(res) {
                            let response = JSON.parse(res)
                            unlistRender(response.allLists, 1)
                        },
                        error: function(error) {
                            console.log("this is errror")
                        }
                    })
                })

                // fetch all list enter by user on first render 
                function fetchAllLists() {
                    $.ajax({
                        type: 'Post',
                        url: 'config/server.php',
                        data: {
                            fetchAllLists: true
                        },
                        success: function(res) {
                            let response = JSON.parse(res)
                            unlistRender(response.data)
                        },
                        error: function(error) {
                            console.log("this is error")
                        }
                    })
                }

                function unlistRender(data, listInput = "") {
                    let span = listInput == 1 ? "hidden" : "";
                    let input = listInput == 1 ? "" : "hidden";
                    if (data != undefined) {
                        if (data.length > 0) {
                            data.forEach((element, index) => {
                                let html = `
                                    <li id="${element.id}" class="p-2 rounded-md hover-gray-200 flex gap-4 items-center cursor-pointer rightClick list${element.id}">
                                        <i class="fa-solid fa-bars"></i>
                                        <span class="${span} addTask${element.id} listSpan" contenteditable="false" temp_list="${element.temp_list}" list_no="${element.list_no}">${element.list_name}</span>
                                        <input class="${input} listInput${element.id} listInputActive listInput listInputActive bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" name="list_name" value="${element.list_name}"> 
                                    </li> 
                                    `;
                                $('.optionList').append(html);
                                $(".activeInput").attr("data-id", element.id);
                                $(".listInput" + element.id).focus().select();
                            });
                        }
                    }
                }

                // $(document).click(function(event) {
                //     // Check if the clicked element is NOT inside .exclude
                //     if (
                //         !$(event.target).closest('.listInputActive').length &&
                //         !$(event.target).closest('.addList').length &&
                //         !$(event.target).closest('.context-menu').length
                //     ) {
                //         let activeInput = $('.activeInput').data('id');
                //         $('.listInput').addClass('hidden');
                //         $('.listSpan').removeClass('hidden');
                //         $(".listInput" + activeInput).focus().select();
                //         let listId = $('.editTask').attr('id');
                //         let inputValue = $('.listInput' + listId).val()
                //         $.ajax({
                //             type: "POST",
                //             url: './config/server.php',
                //             data: {
                //                 "updateList": true,
                //                 id: listId,
                //                 listName: inputValue
                //             },
                //             success: function(response) {
                //                 let res = JSON.parse(response);
                //                 $('.optionList').empty("")
                //                 fetchAllLists()
                //             },
                //             error: function(res) {
                //                 console.log("This is an error");
                //             }
                //         });
                //     }
                // });


                window.deleteTask = function() {
                    let element = $(".deleteTask");
                    let listId = element.attr("id");
                    $.ajax({
                        type: "POST",
                        url: './config/server.php',
                        data: {
                            "deleteList": true,
                            id: listId
                        },
                        success: function(response) {
                            let res = JSON.parse(response)
                            $('.optionList').html("")
                            fetchAllLists()
                        },
                        error: function(response) {
                            console.log("this is error", response)
                        }
                    })
                }


                $(document).on('click', '.editTask', function(e) {
                    let id = $(this).attr('id');
                    let inputField = $('.listInput' + id);
                    let listName = inputField.val();
                    console.log(id, listName)

                    inputField.removeClass('hidden');
                    $('.addTask' + id).addClass('hidden');
                    inputField.focus().select();
                    $('#contextMenu').fadeOut('fast');

                    // if (listName == 'Untitled List') {
                    //     return false;
                    // }

                    inputField.on('keypress', function(e) {
                        if (e.which === 13) { // 13 is the Enter key
                            alert("hello")
                            updateList(id, listName);
                        }
                    });
                });


                function updateList(id, listName) {
                    $.ajax({
                        type: "POST",
                        url: './config/server.php',
                        data: {
                            "updateList": true,
                            id: id,
                            listName: listName
                        },
                        success: function(response) {
                            let res = JSON.parse(response);
                            // fetchAllLists();
                        },
                        error: function(res) {
                            console.log("This is an error");
                        }
                    });
                }


            })
        </script>

    </body>

    </html>