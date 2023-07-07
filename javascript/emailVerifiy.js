"use strict";
$('#verify-button').click(function(){
    newVerificationEmail();
});

function newVerificationEmail() {
    let email = $('#email-input').val();
    if (email == '' | email.length === 0) {
        // Fehlermeldung, das Email Input leer ist
        return;
    }
    $.ajax({
        type: "POST",
        url: "../phpScripts/sendEmail.php",
        data: {type: 'verificationEmailResend', email: email},
        success: function (response) {
            let header = new Headers();
            alert("Sie erhalten innerhalb der nächsten 5 Minuten eine Email zur Bestätigung Ihrer Email Adresse.");
            header.append('Location', '../login');
        }
})

};