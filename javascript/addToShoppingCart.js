"use strict";

$("#cart-button").click(function () {
    var datastring = $("#bestell-form").serialize();
    $.ajax({
        type: "POST",
        url: "../phpScripts/addToSession.php",
        data: datastring,
        success: function (response) {
            var flash;
            if (response.includes("201") || response.includes("200")) { // Überprüft Response
                flash = $(".flash_green"); // Beeinflusst Flash Message
                $(".flash__body_g").html("Erfolgreich zum Warenkorb hinzugefügt");
                //alert('Success: '+response);
            } else {
                flash = $(".flash_red"); // Beeinflusst Flash Message
                $(".flash__body_r").html('Es ist ein Fehler aufgetreten!');
                //alert('Error: '+response);
            }
            flash.addClass("animate--drop-in-fade-out"); // Lässt Flash Message erscheinen
            setTimeout(function () {
                flash.removeClass("animate--drop-in-fade-out"); // Lässt Flash Message verschwinden nach 3500 ms
            }, 3500);
        }
    });
});