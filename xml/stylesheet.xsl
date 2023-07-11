<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html"/>
  
  <!-- Template for the root element -->
  <xsl:template match="/Bestellung">
    <html>
      <head>
        <style>
          .header {
            background-color: lightgray;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
          }
          .separator {
            border-bottom: 1px solid black;
            margin-bottom: 10px;
          }
          .title {
            font-weight: bold;
          }
          .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
          }
          .left {
            width: 48%;
          }
          .right {
            width: 48%;
          }
        </style>
      </head>
      <body>
        <!-- Customer Address -->
        <div class="header">
          <xsl:apply-templates select="Bestellkopf/kunden_adresse/*"/>
        </div>
        
        <!-- Rechnung_daten_allgemein -->
        <div class="header">
          <xsl:apply-templates select="Bestellkopf/rechnung_daten_allgemein/*"/>
        </div>
        
        <!-- Separator -->
        <div class="separator"></div>
        
        <!-- Bestell_Adressen -->
        <div class="row">
          <div class="left">
            <xsl:apply-templates select="Bestellkopf/Bestell_Adressen/Rechnungsadresse/*"/>
          </div>
          <div class="right">
            <xsl:apply-templates select="Bestellkopf/Bestell_Adressen/Lieferadresse/*"/>
          </div>
        </div>
        
        <!-- Separator -->
        <div class="separator"></div>
        
        <!-- Bestellinformationen -->
        <div class="title">Bestellinformationen</div>
        
        <!-- Bestellnummern -->
        <xsl:apply-templates select="Bestellinformationen/Bestellnummern/*"/>
        
        <!-- Separator -->
        <div class="separator"></div>
      </body>
    </html>
  </xsl:template>
  
  <!-- Template for child elements -->
  <xsl:template match="*">
    <div>
      <xsl:value-of select="."/>
    </div>
  </xsl:template>
  
</xsl:stylesheet>