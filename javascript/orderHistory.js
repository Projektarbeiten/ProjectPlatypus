"use strict";
let dropdownStatus;
let opacity;

$("#order-filter-button").click(function (e) {

    e.preventDefault();

});

$("#order-search").keyup(function (e) {
});

$(".order-dropdown-arrow").click(function (e) {
    dropdownStatus = $(this).children().attr("class").split(' ')[1];
    if (dropdownStatus == "left") {
        $(this).children().removeClass("left").addClass("down");
        $(this).parent().parent().parent().find(".order-dropdown-open").css("display", "block");
        $(this).parent().parent().parent().find(".order-dropdown-closed").css("display", "none");

    } else {
        $(this).children().removeClass("down").addClass("left");
        $(this).parent().parent().parent().find(".order-dropdown-closed").css("display", "block");
        $(this).parent().parent().parent().find($(".order-dropdown-open")).css("display", "none");
    }
});

$(".order-search").onChange(function (){
	timespan = $(this).attr("value");
	loadOrderHistorie()

});

function loadOrderHistorie(timespan) {
	$.ajax({
			type: "POST",
            url: "loadOrderHistory.php",
            success: function () {

			}

	});
}