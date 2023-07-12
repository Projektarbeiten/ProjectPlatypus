<?php
session_start();
use TCPDF;
require_once dirname(__FILE__,2).'/phpFunctions/util.php';
require_once dirname(__FILE__,2).'/phpClasses/tcpdf.php';

$OrderArray = null;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



function startInvoiceCreation($OrderArray,$conn){
    $Test = true;
    if($Test){
        $file = dirname(__FILE__,2) . '/phpScripts/order_array_data.json';
        $OrderArray = json_decode(file_get_contents($file), true);
    }

    // XML File Laden und befüllen
    $xml = loadXMLTemplate();
    $xml = startInvoiceFilling($xml,$OrderArray);
    $xml->asXml('xml_output.xml');
	$dom =

    // XSLT-Vorlage laden
    $xslString = file_get_contents(dirname(__FILE__,2) . '/xml/stylesheet.xsl');
    $xsl = new DOMDocument();
    $xsl->loadXML($xslString);

    // XSLTProcessor erstellen und konfigurieren
    $proc = new XSLTProcessor();
    $proc->importStylesheet($xsl);

    // Transformierte XML-Daten erhalten
    $transformedXml = $proc->transformToXML($xml);

    // PDF generierung
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,'UTF-8', false);
    $pdf->SetCreator('Platyweb Invoice Generator');
    $pdf->setAuthor('John Doe');
    $pdf->SetTitle('Ihre Bestellung Nr:' . $OrderArray['bestellid']);
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->AddPage();
    $pdf->WriteHTML($transformedXml, true, false, true, false, '');
    $pdf->Output(dirname(__FILE__).'/output.pdf', 'F'); //_getrawstream()

}

function loadXMLTemplate() : SimpleXMLElement{
    $template = dirname(__FILE__,2) . '/xml/Rechnungsvorlage.xml';
    $xmlString = file_get_contents($template);
    $xml = new SimpleXMLElement($xmlString);
    return $xml;
}

function fillInvoiceHead($Bestellung,$OrderArray) {
    $kunden_adresse = $Bestellung->Bestellkopf->kunden_adresse;
    $kunden_adresse->kunden_name = $OrderArray['userData']['anrede']." ".$OrderArray['userData']['vorname']." ".$OrderArray['userData']['nachname'];
    $Bestellung->Bestellkopf->kunden_adresse->kunden_strasse = $OrderArray['userData']['strasse']." ".$OrderArray['userData']['hausnr'];
    $Bestellung->Bestellkopf->kunden_adresse->kunden_ort = $OrderArray['userData']['plz']."".$OrderArray['userData']['ort'];
    $Bestellung->Bestellkopf->kunden_adresse->kunden_land = $OrderArray['userData']['userland'];
    $Bestellung->Bestellkopf->rechnung_daten_allgemein->rechnung_nr = $OrderArray['rechnung_nr'];
    $Bestellung->Bestellkopf->rechnung_daten_allgemein->kunden_nr = "003".$OrderArray['uid'];
    $Bestellung->Bestellkopf->rechnung_daten_allgemein->rechnung_datum = $OrderArray['bestellDatum'];
    $Bestellung->Bestellkopf->Bestell_Adressen->Rechnungsadresse->Rechnungsadresse_name = $OrderArray['userData']['anrede']." ".$OrderArray['userData']['vorname']." ".$OrderArray['userData']['nachname'];
    $Bestellung->Bestellkopf->Bestell_Adressen->Rechnungsadresse->Rechnungsstrasse = $OrderArray['userData']['strasse']." ".$OrderArray['userData']['hausnr'];
    $Bestellung->Bestellkopf->Bestell_Adressen->Rechnungsadresse->Rechnungsort = $OrderArray['userData']['plz']."".$OrderArray['userData']['ort'];
    $Bestellung->Bestellkopf->Bestell_Adressen->Rechnungsadresse->Rechnungsland = $OrderArray['userData']['userland'];
    $Bestellung->Bestellkopf->Bestell_Adressen->Lieferadresse->Lieferadresse_name = $OrderArray['userData']['anrede']." ".$OrderArray['userData']['vorname']." ".$OrderArray['userData']['nachname'];
    $Bestellung->Bestellkopf->Bestell_Adressen->Lieferadresse->Lieferstrasse = $OrderArray['userData']['strasse']." ".$OrderArray['userData']['hausnr'];
    $Bestellung->Bestellkopf->Bestell_Adressen->Lieferadresse->Lieferort = $OrderArray['userData']['plz']."".$OrderArray['userData']['ort'];
    $Bestellung->Bestellkopf->Bestell_Adressen->Lieferadresse->Lieferland = $OrderArray['userData']['userland'];
    return $Bestellung;
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

    $dom->Bestellinformationen->Bestellnummern->Bestelldatum = $OrderArray['bestellDatum'];
    $dom->Bestellinformationen->Bestellnummern->Bestellnummer = "#".$OrderArray['bestellid'];
    $bestPosArray = $OrderArray['bestellPositionen'];
    for ($i=0; $i < count($bestPosArray); $i++) {
            $bestellposition = $dom->Bestellinformationen->Rechnungsdetails->bestellpositionen->addChild('bestellposition');
            $bestellposition->addChild('pos',$i+1);
            $bestellposition->addChild('bezeichnung',$bestPosArray[$i]['bezeichnung']);
            $bestellposition->addChild('menge',$bestPosArray[$i]['menge']);
            $bestellposition->addChild('einzelpreis',$bestPosArray[$i]['akt_preis']."€");
            $bestellposition->addChild('gesamtpreis',floatval($bestPosArray[$i]['akt_preis']) * intval($bestPosArray[$i]['menge'])."€");
    }
    $dom->Bestellinformationen->Rechnungsdetails->gesamtpreis->preis = $OrderArray['gesamtkosten'] ."€";
    return $dom;
}

function startInvoiceFilling($dom,$OrderArray) {
    $dom = fillInvoiceHead($dom,$OrderArray);
    $dom = fillInvoiceBody($dom,$OrderArray);
    return $dom;
}


?>