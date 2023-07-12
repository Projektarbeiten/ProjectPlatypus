"use strict";
$('#verify-button').click(function(){
    newVerificationEmail();
});

function newVerificationEmail() {
    let email = $('#email-input').val();
    if (email == '' | email.length === 0) {
        alert("Bitte Email eingeben");
        return;
    }
    $.ajax({
        type: "POST",
        url: "../phpScripts/sendEmail.php",
        data: {type: 'verificationEmailResend', email: email},
        success: function (response) {
            if(response.includes("1")){
            alert("Sie erhalten innerhalb der nächsten 5 Minuten eine Email zur Bestätigung Ihrer Email Adresse von uns.");
            setTimeout(() => {window.location.href = "login";}, 3000);
            }else{ //TODO: Anpassen/Styling, damit die Rückmeldung in diesem Kasten angezeigt wird
                let overlay = $.parseHTML("<div class='container'><div class='row'<div class=col-6><div id='lightbox__overlay'></div></div></div></div>");
                let container = $.parseHTML("<div id='lightbox__overlay-container'></div>");
                let displayUser = $.parseHTML(
                    `<div id='lightbox__overlay-verify-error' class='lightbox'>
                    <p class='center'>Ihre Email Adresse wurde bei uns noch nicht registriert</p>
                    <p class='center'><a href='register'>Noch nicht Registriert?</a></p>
                    <button class='back-btn'>Zurück</Button>
                    </div`);

                $("body").append(overlay);
                $(overlay).append(container);
                $(container).append(displayUser);

                $(".back-btn").click(function () {
                    $(this).remove();
                    $(overlay).remove();
                });

                $(document).keydown(function (event) {
                    if (event.key == "Escape") {
                        $(container).remove();
                        $(overlay).remove();
                    };
                });
            }
        }
})

};