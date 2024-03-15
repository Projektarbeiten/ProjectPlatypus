"use strict";
let newVal;
let fieldVal;
let field;
let fieldId;
let inputField;
let attributeTarget;
let updateMessage;
let updateInterval;
let lever = true;

// Das Skript wandelt die <p> Tags mit der Klasse editable-field in Inputfelder (Gewählte Tags in Selectlisten oder Datumsfelder) um.
$(".editable-field").on("click", function (e) {
    if (lever == true) {
        lever = false;
        fieldVal = $(this).html();
        field = $(this);
        fieldId = $(this).attr("id");

        if (fieldId == "konto-anrede") {
            inputField = $.parseHTML(`<select class="in-edit-input editable-field" id="${fieldId}">
                            <option value="Divers">Divers</option>
                            <option value="Herr">Herr</option>
                            <option value="Frau">Frau</option>
                            </select>`)
        } else if (fieldId == "konto-titel") {
            inputField = $.parseHTML(` <select class="in-edit-input editable-field" id="${fieldId}">
                            <option value=""></option>
                            <option value="Doktor">Doktor</option>
                            <option value="Professor">Professor</option>
                        </select>`);
        } else if (fieldId == "konto-geburtsdatum") {
            inputField = $.parseHTML(` <input class="in-edit-input editable-field" id="${fieldId}" type="date">`);

        } else {
            inputField = $.parseHTML(`<input class="in-edit-input editable-field" id="${fieldId}" placeholder="Alter Wert: ${fieldVal}" type="text">`);
        }

        $(field).parent().append(inputField);
        $(`#${fieldId}`).focus();
        $(field).remove();

        // Beim Drücken von Enter, wird von dem Fokussierte Element der neue Wert abgespeichert und weitergeleitet
        $(".editable-field").on("keypress", function (e) {
            if (e.which == 13) {
                checkNormalField();
            }
        });

        $(".editable-field").on("blur", function (e) {
            checkNormalField();
        });

        $("select").on("change", function (e) {
            field = $(this);
            newVal = $(`#${fieldId} option:selected`).text();
            fieldId = $(this).attr("id");
            attributeTarget = fieldId.split("-")[1];
            sendAndSwitchToP();
        });
    }
});

// Erstellt eine Lightbox mit einem Formular, um das Passwort zurückzusetzen. Bei Änderungen zuerst an @GCoder wenden
$("#password-change").click(function (event) {
    event.preventDefault();
    let pwTitle;
    let pwRegex;

    pwTitle = "Minimum 8 characters including 1 upper and lower case character + 1 special character or number";
    pwRegex = "^(?=.*[A-Z])(?=.*[a-z])(?=.*[@$!%*?&\\d]).{8,128}$";


    // HTML der Lightbox
    let overlay = $.parseHTML("<div class='container'><div class='row'<div class=col-6><div id='lightbox__overlay'></div></div></div></div>");
    let container = $.parseHTML("<div id='lightbox__overlay-container'></div>");
    let displayUser = $.parseHTML(
        `<div id='lightbox__overlay-password-change' class='lightbox'>
            <form id='password-change-form' method='POST' action='?pwChange'>
                <h1>Change password</h1>
                <label for='old-password'>Altes Passwort: </label>
                <input type='password' id='old-password' name='old-password' required>
                <label for='add-new-password'>Neues Passwort: </label>
                <input type='password' id='add-new-password' name='add-new-password' title='${pwTitle}' required pattern='${pwRegex}'>
                <label for='repeat-new-password'>Neues Passwort wiederholen: </label>
                <input type='password' id='repeat-new-password' name='repeat-new-password' required>
                <button id='change-password-btn' class='btn' type='submit'>Bestätigen</button>
                <button class='back-btn'>Zurück</Button>
            </form>
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
});

function checkNormalField() {
    field = $(`#${fieldId}`);
    newVal = $(`#${fieldId}`).val();
    fieldId = $(`#${fieldId}`).attr("id");
    attributeTarget = fieldId.split("-")[1];
    sendAndSwitchToP();
}

// Sendet den neuen Wert des Feldes an php zur weiteren Datenverarbeitung und gibt dem User eine Meldung zurück
function sendAndSwitchToP() {
    try {
        $.ajax({
            type: "POST",
            url: "kontopage.php",
            data: { value: newVal, dataTarget: attributeTarget },
            success: function () {
                updateMessage = $.parseHTML(`<span id="${fieldId}-update-message" style="display: block; text-align: center; color: forestgreen;">Feld erfolgreich geupdated!<span>`);
                inputField = $.parseHTML(`<p class='editable-field id='${fieldId} '>${newVal}</p>`);
                $(field).parent().append(inputField);
                $(inputField).parent().append(updateMessage);
                $(field).remove();
                let updateOpacity = 1
                setTimeout(() => {
                    updateInterval = setInterval(() => {
                        $(`#${fieldId}-update-message`).css("opacity", updateOpacity);
                        updateOpacity -= 0.01
                    }, 20);
                    setTimeout(() => {
                        $(`#${fieldId}-update-message`).remove();
                        lever = true;
                        clearInterval(updateInterval);
                    }, 2500);
                }, 1500);
            }
        });
    } catch (error) {
        console.warn("Daten konnte nicht an den Server weitergeleitet werden. Lade bitte die Seite neu und versiche es nochmal.");
    }
}
