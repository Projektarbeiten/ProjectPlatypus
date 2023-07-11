<?php
session_start();
use TCPDF;
require_once dirname(__FILE__).'./phpFunctions/util.php';
require dirname(__FILE__, 2) . '/phpClasses/tcpdf.php';

$OrderArray = null;
$Test = false;

if($Test){
    $file = dirname(__FILE__) . '/phpScripts/order_array_data.json';
    $OrderArray = json_decode(file_get_contents($file));
}

function startInvoiceCreation($OrderArray,$conn){
    $document = new DOMDocument();
    $xml = loadXMLTemplate();
    $document->loadXML($xml);
    $document = startInvoiceFilling($document,$OrderArray);
    $document->asXML('xml_output.xml');

}

function loadXMLTemplate() : object{
    $template = dirname(__FILE__) . '/xml/Rechnungsvorlage.xml';
    $xmlData = simplexml_load_file($template);
    return $xmlData;
}

function fillInvoiceHead($dom,$OrderArray) {
    $kunden_adresse = $dom->getElementsByTagName('kunden_adresse')->item[0];
    $kunden_adresse->kunden_name = $OrderArray['anrede']." ".$OrderArray['vorname']." ".$OrderArray['nachname'];
    $kunden_adresse->kunden_strasse = $OrderArray['strasse']." ".$OrderArray['hausnr'];
    $kunden_adresse->kunden_ort = $OrderArray['plz']."".$OrderArray['ort'];
    $kunden_adresse->kunden_land = $OrderArray['land'];
    $rechnung_daten_allgemein = $dom->getElementsByTagName('rechnung_daten_allgemein')->item[0];
    $rechnung_daten_allgemein->rechun_nr = $OrderArray['rechnung_nr'];
    $rechnung_daten_allgemein->kunden_nr = "003".$OrderArray['uid'];
    $rechnung_daten_allgemein->rechnung_datum = $OrderArray['rechnung_datum'];
    $bestell_adressen = $dom->getElementsByTagName('Bestell_Adressen')->item[0];
    $bestell_adressen->rechnungsadresse->rechnungsadressen_name = $OrderArray['anrede']." ".$OrderArray['vorname']." ".$OrderArray['nachname'];
    $bestell_adressen->rechnungsadresse->rechnungsstrasse = $OrderArray['strasse']." ".$OrderArray['hausnr'];
    $bestell_adressen->rechnungsadresse->rechnungsort = $OrderArray['plz']."".$OrderArray['ort'];
    $bestell_adressen->rechnungsadresse->rechnungsland = $OrderArray['land'];
    $bestell_adressen->lieferadresse->lieferadresse_name = $OrderArray['anrede']." ".$OrderArray['vorname']." ".$OrderArray['nachname'];
    $bestell_adressen->lieferadresse->lieferstrasse = $OrderArray['strasse']." ".$OrderArray['hausnr'];
    $bestell_adressen->lieferadresse->lieferort = $OrderArray['plz']."".$OrderArray['ort'];
    $bestell_adressen->lieferadresse->lieferland = $OrderArray['land'];
    return $dom;
}

function fillInvoiceBody($dom,$OrderArray) {
    /*
        <bestellposition>
                <pos attribute="Value">Wert</pos>
                <bezeichnung attribute="Value">Wert</bezeichnung>
                <menge attribute="Value">Wert</menge>
                <einzelpreis attribute="Value">Wert</einzelpreis>
                <gesamtpreis attribute="Value">Wert</gesamtpreis>
        </bestellposition>


    */

    $bestellnummern = $dom->getElementsByTagName('Bestellnummern')->item[0];
    $bestellnummern->bestelldatum = $OrderArray['BestellDatum'];
    $bestellnummern->bestellnummer = $OrderArray['bestellnummer'];
    $bestellpositionen = $dom->getElementsByTagName('bestellpositionen')->item[0];
    $bestPosArray = $OrderArray['bestellpos'];
    for ($i=0; $i < count($bestPosArray); $i++) {
        $bestellposition = $dom->createElement('bestellposition');
            $posElement= $dom->createElement('pos');
               $posElement->appendChild($dom->createTextNode($i+1));
            $bezeichnungElement = $dom->createElement('bezeichnung');
                $bezeichnungElement->appendChild($dom->createTextNode($bestPosArray[$i]['bezeichnung']));
            $mengeElement = $dom->createElement('menge');
                $mengeElement->appendChild($dom->createTextNode($bestPosArray[$i]['menge']));
            $einzelpreisElement= $dom->createElement('einzelpreis');
                $einzelpreisElement->appendChild($dom->createTextNode($bestPosArray[$i]['akt_preis']));
            $gesamtpreisElement = $dom->createElement('gesamtpreis');
                $gesamtpreisElement->appendChild($dom->createTextNode($bestPosArray[$i]['akt_preis'] * $bestPosArray[$i]['menge']));
        $bestellposition->appendChild($posElement);
        $bestellposition->appendChild($bezeichnungElement);
        $bestellposition->appendChild($mengeElement);
        $bestellposition->appendChild($einzelpreisElement);
        $bestellposition->appendChild($gesamtpreisElement);
        $bestellposition->appendChild($bestellpositionen);
    }
    $dom->getElementsByTagName('gesamtpreis')->preis = $OrderArray['gesamtpreis'];
    return $dom;
}

function startInvoiceFilling($dom,$OrderArray) {
    $dom = fillInvoiceHead($dom,$OrderArray);
    $dom = fillInvoiceBody($dom,$OrderArray);
    return $dom;
}


?>