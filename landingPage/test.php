<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inline Editing</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .editable {
            cursor: pointer;
            display: inline-block;
            min-width: 100px;
            padding: 5px;
            border: 1px solid transparent;
        }
        .editable:hover {
            border: 1px dashed #000;
        }
    </style>
</head>
<body>

    <span class="editable" data-id="1">Click to Edit</span>

    <script>
        $(document).ready(function () {
            $(document).on("click", ".editable", function () {
                let currentText = $(this).text();
                let inputField = $("<input>", {
                    type: "text",
                    value: currentText,
                    class: "edit-input"
                });

                $(this).replaceWith(inputField);
                inputField.focus();

                // Handle save on focus out or Enter key
                inputField.on("blur keypress", function (event) {
                    if (event.type === "blur" || (event.type === "keypress" && event.which === 13)) {
                        let newValue = $(this).val();
                        let element = $(this);

                        // Save via AJAX
                        $.ajax({
                            url: "save.php",
                            type: "POST",
                            data: { id: element.data("id"), value: newValue },
                            success: function (response) {
                                element.replaceWith(`<span class="editable" data-id="1">${newValue}</span>`);
                            }
                        });
                    }
                });
            });
        });
    </script>

</body>
</html>


<script>


$(document).on("click", ".addList", function(e){
    let listName = "Untitled list"
    $.ajax({
        type: "POST",
        url: './config/server.php',
        data: {
            addList: true,
            listName: listName
        },
        success: function(res) {
            let response = JSON.parse(res);
            console.log(response);
            if(response.success){
                let  html = `<li class="flex p-2 rounded-md hover:bg-gray-200 cursor-pointer right-click justify-between items-center" data-id="${response.data['id']}">
                                <i class="fas fa-bars mr-1"></i>
                                <span class="hidden listSpan${response.data['id']} listSpan">${response.data['list_name']}</span>
                                <input class="listInput${response.data['id']} listInput listInputActive bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" name="list_name" value="${listName}"> 
                            </li>`;
                $(".addListSection").append(html);
                $(".activeInput").attr("data-id",response.data['id']);
                $(".listInput"+response.data['id']).focus().select();
            }
        },
        error: function(res) {
            console.log("this is errror")
        }
    })
})

$(document).click(function(event) {
    // Check if the clicked element is NOT inside .exclude
    if (!$(event.target).closest('.listInputActive').length) {
        let activeInput = $('.activeInput').data('id');
        $('.listInput').addClass('hidden');
        $('.listSpan').removeClass('hidden');
        $(".listInput"+activeInput).focus().select();
        console.log("Clicked outside the excluded area");
        // Run your code here
    }
});


</script>
