"use strict";
let newVal;
let fieldVal;
let field;
let fieldId;
let inputField;
let attributeTarget;
let updateMessage;
let updateInterval;
$(".editable-field").on("click", function (e) {
    fieldVal = $(this).html();
    field = $(this);
    fieldId = $(this).attr("id");
    console.log(fieldId)

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

    $(".editable-field").keypress(function (e) {
        if (e.which == 13) {
            field = $(this);
            newVal = $(this).val();
            fieldId = $(this).attr("id");
            attributeTarget = fieldId.split("-")[1];
            $.ajax({
                type: "POST",
                url: "kontopage.php",
                data: { value: newVal, dataTarget: attributeTarget },
            });
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
                    clearInterval(updateInterval);
                }, 2500);
            }, 1500);
        }
    });

    $("select").on("change", function (e) {
        field = $(this);
        newVal = $(`#${fieldId} option:selected`).text();
        fieldId = $(this).attr("id");
        attributeTarget = fieldId.split("-")[1];

        $.ajax({
            type: "POST",
            url: "kontopage.php",
            data: { value: newVal, dataTarget: attributeTarget },
        });
        updateMessage = $.parseHTML(`<span id="${fieldId}-update-message" style="display: block; text-align: center; color: forestgreen">Feld erfolgreich geupdated!<span>`);
        inputField = $.parseHTML(`<p class='editable-field' id='${fieldId}'>${newVal}</p>`);

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
                clearInterval(updateInterval);
            }, 2500);
        }, 1000);
    });
});

$("#password-change").click(function (event) {
    event.preventDefault();
    let pwTitle;
    let pwRegex;

    pwTitle = "Minimum 8 characters including 1 upper and lower case character + 1 special character or number";
    pwRegex = "^(?=.*[A-Z])(?=.*[a-z])(?=.*[@$!%*?&\d]).{8,128}$";
    

    // Creates the lightbox
    let overlay = $.parseHTML("<div class='container'><div class='row'<div class=col-6><div id='lightbox__overlay'></div></div></div></div>");
    let container = $.parseHTML("<div id='lightbox__overlay-container'></div>");
    let displayUser = $.parseHTML(
        `<div id='lightbox__overlay-password-change' class='lightbox'>
            <form id='password-change-form' method='POST'>
                <h1>Change password</h1>
                <label for='old-password'>Old password: </label>
                <input type='password' id='old-password' name='old-password' required>
                <label for='add-new-password'>New password: </label>
                <input type='password' id='add-new-password' name='add-new-password' title='${pwTitle}' required pattern='${pwRegex}'>
                <label for='repeat-new-password'>Repeat new password: </label>
                <input type='password' id='repeat-new-password' name='repeat-new-password' required>
                <button id='change-password-btn' class='btn' type='submit'>Confirm new password</button>
                <button class='back-btn'>Discard</Button>
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

