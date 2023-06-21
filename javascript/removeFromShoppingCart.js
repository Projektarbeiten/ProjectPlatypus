"use strict";
let debug = false;


$(function () {
    updatePrices();
});

$(".sc-bt-remove-product").click(function () {
    let articleElement = $(this).parentsUntil('.sc-product-cart').parent();
    let productId = articleElement.get(0).id;
    console.log("product id " + productId);
    $.ajax({
        type: "POST",
        url: "../phpScripts/removeProductFromSession.php",
        data: { 'produkt_id': productId },
        dataType: "json",
        success: function (response) {
            if (response.responseStatus == "200") {
                $('#' + productId).remove();
                $('#' + productId).next().remove();
                updatePrices();
                checkShoppingCart();
            } else {
                alert('Error: ' + response);
            }
        }
    });
});

$("#buy-more").click(function () {
    history.back();
});




$(document).ready(function () {
    $('.sc-mengenauswahl').change(function () {
        // get Product id for later use
        let menge = $(this).val()
        let articleElement = $(this).parentsUntil('.sc-product-cart').parent();
        let productId = articleElement.get(0).id;
        if (debug) {
            console.log("selected articleElement: " + articleElement.get(0).nodeName);
            console.log("selected ProductID: " + productId);
            console.log("selected Menge: " + menge);
            console.log("mengenauswahl selected $(this): " + this.nodeName);
        }
        $.ajax({
            type: "POST",
            url: "../phpScripts/addToSession.php",
            data: {
                produkt_id: productId,
                mengenauswahl: menge
            },
            success: function (response) {
                if (response.includes("201") || response.includes("200")) {
                    updatePrices();
                }
            }
        });
    });
});

function checkShoppingCart() {
    if ($('.sc-product-cart').length) {

    } else {
        $('.artikel-container').remove();
        $('.sc-box-between-endprice-and-productcard').remove();
        $('#zur-kasse').remove();
        let newContent = $(" <div class='container artikel-container'>\
        <div style='display: flex;'>\
            <div class='boxlinks' style='background-color: grey; opacity: 90%'>\
                <h3>\
                    Warenkorb\
                </h3>\
            </div>\
            <div class='sc-boxrechts' style='margin-left: -39.5px;'>\
                <h3>\
                    Kasse\
                </h3>\
            </div>\
        </div>\
        <hr>\
                    <div class='col-6'>\
        \
                        <p style='text-align: center'>Kein Produkt im Warenkorb</p>            </div>\
                </div>");
        $('main').append(newContent);
    }
}


function updatePrices() {
    if ($('.sc-product-cart').length) {
        $.ajax({
            type: "GET",
            url: "../phpScripts/updatePrice.php",
            dataType: "json",
            success: function (response) {
                let articleElements = document.querySelectorAll('.sc-product-cart');
                /*
                let articleElementIDs = document.getElementsByClassName('sc-product-cart').attr('id');
                let articleElements2D = [];
                for(let i = 0; i < articleElements.length; i++){
                    articleElements2D.push( [ array1[i], array2[i] ] );
                } */
                let priceElements = document.querySelectorAll('.sc-article-price');
                if (debug) { console.log(priceElements); }
                let zwPrice = null;
                let price;
                let rabatt = 1;
                $.each(response, function (index, array) {
                    let produkt_id = array.produkt_id;
                    let menge = array.menge;
                    let akt_preis = array.akt_preis;
                    if (articleElements[index].id == produkt_id) {
                        if ($('#sc-gutschein').val() !== '') {
                            // rabatt = 10%;
                        } else { // funktioniert nur wenn DOM von JavaScript in selber reihenfolge in ein Array gepackt wird, wie es auch das Session Produkt Array ausliest.
                            price = menge * akt_preis; //#TODO: Rabatt Berechnung Einfügen
                            zwPrice += price;
                        }
                        priceElements[index].innerHTML = price.toFixed(2) + ' €';
                    }
                    if (debug) {
                        console.log("produkt_id: " + produkt_id + ", menge: " + menge + ", AKT_Price: " + akt_preis);
                    }
                });
                document.getElementById('sc-price-before-tag').innerHTML = zwPrice.toFixed(2) + " €";
                let mwst = ((zwPrice -(zwPrice / 119) * 100));
                document.getElementById('sc-price-mwst').innerHTML = mwst.toFixed(2) + " €";
                let endPrice = zwPrice // Da Zwischenpreisch schon Bruttopreis beinhaltet
                document.getElementById('sc-price-end-tag').innerHTML = endPrice.toFixed(2) + " €";
            }

        });
    }
}