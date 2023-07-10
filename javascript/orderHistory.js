"use strict";
let dropdownStatus;
let opacity;

let orders = [];
let ordersDate = [];

$("#order-filter-button").click(function (e) {
  e.preventDefault();
  if ($("#order-filter-section")) {
    $("#order-filter-section").toggle();
  }
});

$("#order-search").keyup(function (e) {});

$(".order-search").change(function () {
  let timespan = $(this).attr("value");
  loadOrderHistory(timespan);
});

$(window).on("load", function () {
  loadOrderHistory();
});

$(document).ready(function () {
  $("#loader").show();
  console.log("Show");
});

function loadOrderHistory(timespan) {
  $.ajax({
    type: "POST",
    url: "../phpScripts/loadOrderHistory.php",
    data: { timespan: timespan },
    async: false,
    success: function (response) {
      $("#loader").remove();
      $("#orders").append($.parseHTML(response));
      $(".order-dropdown-arrow").on("click", function (e) {
        dropdownStatus = $(this).children().attr("class").split(" ")[1];
        if (dropdownStatus == "left") {
          $(this).children().removeClass("left").addClass("down");
          $(this)
            .parent()
            .parent()
            .parent()
            .find(".order-dropdown-open")
            .css("display", "block");
          $(this)
            .parent()
            .parent()
            .parent()
            .find(".order-dropdown-closed")
            .css("display", "none");
        } else {
          $(this).children().removeClass("down").addClass("left");
          $(this)
            .parent()
            .parent()
            .parent()
            .find(".order-dropdown-closed")
            .css("display", "block");
          $(this)
            .parent()
            .parent()
            .parent()
            .find($(".order-dropdown-open"))
            .css("display", "none");
        }
      });
      $(".order-card").each(function () {
        orders.push(this);
        ordersDate.push($(this).find(".order-date").html());
      });

    

      $("#neueste").on("change", function () {
            $(".order-card").remove();
            orders = orders.reverse()
            orders.forEach(function (order) {
                console.log(order)
              $("#orders").append(order);
            });

      });
    },
  });
}
