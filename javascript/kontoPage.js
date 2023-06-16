"use strict";
$(".editable-field").click(function (e) {
    let fieldVal = $(this).html();
    let field = $(this);
    let fieldId = $(this).attr("id");
    console.log(fieldId)
    let inputField;
if(fieldId != "konto-anrede" || fieldId != "konto-titel" ||fieldId != "konto-geburtsdatum") {
    inputField = $.parseHTML(`<input class="in-edit-input editable-field" id="${fieldId}-edit" placeholder="Alter Wert: ${fieldVal}" type="text">`);
} if(fieldId == "konto-anrede") {
    inputField = $.parseHTML(`sel`)
}
    
    $(field).parent().prepend(inputField);
    $(`#${fieldId}-edit`).focus();
    $(field).remove();
    
    e.preventDefault();

    
});