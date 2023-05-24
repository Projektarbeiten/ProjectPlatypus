"use strict";

window.addEventListener("load", function () {
  $("#search-bar").keypress(function (event) {
    let keycode = event.keyCode ? event.keyCode : event.which;
    if (keycode == "13") {
      $("#search-form").submit();
    }
  });
});
