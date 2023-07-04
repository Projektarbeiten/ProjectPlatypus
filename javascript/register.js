"use strict"; 
$("#register-form").submit(function (e) { 
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "./phpFunctions/email_package.php",
        data: "data",
        dataType: "dataType",
        success: function (response) {
            
        }
    });
    
});