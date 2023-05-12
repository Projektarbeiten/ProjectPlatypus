"use strict";

$("#bt-remove-product").click(function() {
    var datastring = $("#bestell-form").serialize();
    $.ajax({
        type: "POST",
        url: "../phpScripts/removeProductFromSession.php",
        data: datastring,
        success: function (response) {
            if (response == "success") {
                            var parentElement = document.getElementById("bt-remove-product").parentElement;
                            parentElement.setAttribute("display", "none");
                        }
        }
    });
});
