<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" />

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
            display: flex;
            justify-content: space-between;
          }
          .separator {
            border-bottom: 1px solid black;
            margin-bottom: 10px;
          }
          .title {
            font-weight: bold;
            margin-top: 20px;
          }
          .table {
            border-collapse: collapse;
            width: 100%;
          }
          .table th,
          .table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
          }
          .table th {
            background-color: lightgray;
            font-weight: bold;
          }
          .page {
            width: 21cm;
            margin: 2cm auto;
          }
          .block {
            text-align: justify;
          }
          .right-align {
            text-align: right;
          }
          .footer {
            display: flex;
            justify-content: flex-end;
          }
        </style>
      </head>
      <body>
        <div class="page">
          <!-- Customer Address and Rechnung_daten_allgemein -->
          <div class="header">
            <div class="address">
              <div class="title">Kundenadresse</div>
              <div class="block">
                <xsl:apply-templates select="Bestellkopf/kunden_adresse/*" />
              </div>
            </div>
            <div class="details">
              <div class="title">Rechnungsdetails</div>
              <div class="block">
                <xsl:apply-templates select="Bestellkopf/rechnung_daten_allgemein/*" />
              </div>
            </div>
          </div>

          <!-- Separator -->
          <div class="separator"></div>

          <!-- Bestell_Adressen -->
          <xsl:apply-templates select="Bestellkopf/Bestell_Adressen" />

          <!-- Separator -->
          <div class="separator"></div>

          <!-- Bestellinformationen -->
          <div class="title">Bestellinformationen</div>

          <!-- Bestellnummern -->
          <xsl:apply-templates select="Bestellinformationen/Bestellnummern/*" />

          <!-- Separator -->
          <div class="separator"></div>

          <!-- Bestellpositionen -->
          <table class="table">
            <thead>
              <tr>
                <th>pos</th>
                <th>bezeichnung</th>
                <th>menge</th>
                <th>einzelpreis</th>
                <th>gesamtpreis</th>
              </tr>
            </thead>
            <tbody>
              <xsl:apply-templates select="Bestellinformationen/Rechnungsdetails/bestellpositionen/bestellposition"/>
            </tbody>
          </table>

          <!-- Separator -->
          <div class="separator"></div>

          <!-- Gesamtpreis -->
          <div class="footer">
            <xsl:apply-templates select="Bestellinformationen/Rechnungsdetails/gesamtpreis" />
          </div>
        </div>
      </body>
    </html>
  </xsl:template>

  <!-- Template for child elements -->
  <xsl:template match="*">
    <div>
      <xsl:value-of select="." />
    </div>
  </xsl:template>

  <!-- Template for Bestell_Adressen element -->
  <xsl:template match="Bestell_Adressen">
    <div class="header">
      <div class="address">
        <div class="title">Rechnungsadresse</div>
        <div class="block">
          <xsl:apply-templates select="Rechnungsadresse/*" />
        </div>
      </div>
      <div class="address">
        <div class="title">Lieferadresse</div>
        <div class="block">
          <xsl:apply-templates select="Lieferadresse/*" />
        </div>
      </div>
    </div>
  </xsl:template>

  <!-- Template for header cells -->
  <xsl:template match="*" mode="header">
    <th>
      <xsl:value-of select="local-name()" />
    </th>
  </xsl:template>

  <!-- Template for table rows -->
  <xsl:template match="bestellposition">
    <tr>
      <td><xsl:value-of select="pos"/></td>
      <td><xsl:value-of select="bezeichnung"/></td>
      <td><xsl:value-of select="menge"/></td>
      <td><xsl:value-of select="einzelpreis"/></td>
      <td><xsl:value-of select="gesamtpreis"/></td>
    </tr>
  </xsl:template>

</xsl:stylesheet>
