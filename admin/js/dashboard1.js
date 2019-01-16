$(document).ready(function() {
  "use strict";
  // toat popup js
  refresh();
  if (window.location.href.includes("loggedin"))
    $.toast({
      heading: "Welcome back!",
      position: "top-center",
      loaderBg: "#fff",
      icon: "success",
      hideAfter: 3500,
      stack: 6
    });

  $(".counter").counterUp({
    delay: 100,
    time: 1200
  });

  var sparklineLogin = function() {
    $("#sparklinedash").sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
      type: "bar",
      height: "30",
      barWidth: "4",
      resize: true,
      barSpacing: "5",
      barColor: "#7ace4c"
    });
    $("#sparklinedash2").sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
      type: "bar",
      height: "30",
      barWidth: "4",
      resize: true,
      barSpacing: "5",
      barColor: "#7460ee"
    });
    $("#sparklinedash3").sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
      type: "bar",
      height: "30",
      barWidth: "4",
      resize: true,
      barSpacing: "5",
      barColor: "#11a0f8"
    });
    $("#sparklinedash4").sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
      type: "bar",
      height: "30",
      barWidth: "4",
      resize: true,
      barSpacing: "5",
      barColor: "#f33155"
    });
  };
  var sparkResize;
  $(window).on("resize", function(e) {
    clearTimeout(sparkResize);
    sparkResize = setTimeout(sparklineLogin, 500);
  });
  sparklineLogin();
});

function refresh() {
  $.get("../php/ops.php?getAll", data => {
    $("tbody").html("");
    data.forEach(el => {
      $("tbody").append(fillText(el));
    });
  });
}

const fillText = data => `<tr>
                      <td>${data.id}</td>
                      <td class="txt-oflo">${data.name}</td>
                      <td>${data.views}</td>
                      <td class="txt-oflo">${data.date}</td>
                      <td>
                        <button onclick="edit(${data.id})">Edit</button>
                        <button onclick="confirm(${data.id})">Delete</button>
                      </td>
                  </tr>`;
function edit(id) {
  $.get("../php/ops.php?getID=" + id, res => {
    $("#editModal #name").val(res.data.name);
    $("#editModal #tags").val(res.data.tags.join(","));
    $("#editModal #description").val(res.data.description);
    $("#editModal #id").val(res.data.id);
    $("#editModal").modal("show");
  });
}

function remove(el) {
  let id = $(el).data("id");
  $.get("../php/ops.php?deleteID=" + id, res => {
    if (res.status == "success") {
      $.toast({
        heading: "Success",
        text: "Project has been removed.",
        position: "top-right",
        loaderBg: "#fff",
        icon: "success",
        hideAfter: 3500,
        stack: 6
      });
    } else {
      $.toast({
        heading: "Failure",
        text: "An error occured while removing the project.",
        position: "top-right",
        loaderBg: "#fff",
        icon: "warning",
        hideAfter: 3500,
        stack: 6
      });
    }
    refresh();
    $("#confirmModal").modal("hide");
  });
}

function confirm(id) {
  $("#confirmModal .btn-danger").attr("data-id", id);
  $("#confirmModal").modal("show");
}
