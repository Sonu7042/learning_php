
const contextMenu = document.getElementById("contextMenu");
$(document).on("contextmenu", ".rightClick", function (event) {
  event.preventDefault();
  contextMenu.style.top = `${event.clientY}px`;
  contextMenu.style.left = `${event.clientX}px`;
  contextMenu.style.display = "block";
  let id = $(this).attr("id");
  $('.duplicateTask').attr("id",id);
  $('.deleteTask').attr("id",id);
  $('.editTask').attr("id",id);
});


$(document).on("click", function () {
  contextMenu.style.display = "none";
});





