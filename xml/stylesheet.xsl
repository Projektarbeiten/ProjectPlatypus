<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" indent="yes" />

	<xsl:template match="/">
		<html>
			<head>
				<meta charset="UTF-8" />
				<title>Platyweb Rechnung</title>
				<style>
        	body {
				font-family: Arial, Helvetica, sans-serif;
				overflow-x: hidden;
				background-color: var(--theme-color-1);
				box-sizing: border-box;
				margin: 0 auto;
				width: 100%;
				}
			.row {
				padding: 0 30px;
				margin-bottom: 30px;
				}

			h1 {
				display: inline-block;
			}
			#kunden_adresse,
			#rechnung_daten_allgemein,
			#rechnungsadresse,
			#lieferadresse {
				display: inline-block;
				width: 300px;
			}
			#rechnung_daten_allgemein {
				float: right;
				width: auto;
				background-color: gainsboro;
				border: 1px solid black;
				border-radius: 10px;
				padding: 15px 10px;
			}
			#bestellnummern {
				width: auto;
			}
			#lieferadresse {
				width: auto;
			}
			p {
				margin: 0;
			}
			h2 {
				font-size: 1.2rem;
			}
			table {
				width: 100%;
			}
			th,
			td {
				width: 20%;
				padding: 15px;
			}
			th {
				background-color: gainsboro;
			}
			tr:nth-of-type(odd) {
				background-color: #eee;
			}
			tr:nth-of-type(even) {
				background-color: gainsboro;
			}
			td {
				text-align: center;
			}
			hr {
				border: 1px solid black;
			}
			.labellike {
				display: inline;
				margin: 0;
				padding: 0;
			}
			table {
				border-collapse: collapse;
				overflow: hidden;
				border-radius: 10px;
			}
				</style>
			</head>
			<body>
				<div id="bestellung">
					<div class="row">
						<h1 stye="float:left">Rechnung</h1>
						<img src="http://localhost/img/platyweb.svg" alt="Logo" width="150px" style="float: right" />
					</div>

					<div id="bestellkopf">
						<div class="row">
							<div id="kunden_adresse">
								<p id="kunden_name">
									<xsl:value-of select="//kunden_name" />
								</p>
								<p id="kunden_strasse">
									<xsl:value-of select="//kunden_strasse" />
								</p>
								<p id="kunden_ort">
									<xsl:value-of select="//kunden_ort" />
								</p>
								<p id="kunden_land">
									<xsl:value-of select="//kunden_land" />
								</p>
							</div>
							<div id="rechnung_daten_allgemein">
								<p class="labellike">Rechnungs-Nr:</p>
								<p class="labellike" id="rechnung_nr">
									<xsl:value-of select="//rechnung_nr" />
								</p>
								<br />
								<p class="labellike">Kunden-Nr:</p>
								<p class="labellike" id="kunden_nr">
									<xsl:value-of select="//kunden_nr" />
								</p>
								<br />
								<p class="labellike">Rechnungsdatum:</p>
								<p id="rechnung_datum" class="labellike">
									<xsl:value-of select="//rechnung_datum" />
								</p>
							</div>
						</div>

						<hr />
						<div class="row">
							<div id="bestell_adressen">
								<div id="rechnungsadresse">
									<h2>Rechnungsadresse</h2>
									<p id="rechnungsadresse_name">
										<xsl:value-of select="//rechnungsadresse_name" />
									</p>
									<p id="rechnungsstrasse">
										<xsl:value-of select="//rechnungsstrasse" />
									</p>
									<p id="rechnungsort">
										<xsl:value-of select="//rechnungsort" />
									</p>
									<p id="rechnungsland">
										<xsl:value-of select="//rechnungsland" />
									</p>
								</div>
								<div id="lieferadresse" style="float: right">
									<h2>Lieferadresse</h2>
									<p id="lieferadresse_name">
										<xsl:value-of select="//lieferadresse_name" />
									</p>
									<p id="lieferstrasse">
										<xsl:value-of select="//lieferstrasse" />
									</p>
									<p id="lieferort">
										<xsl:value-of select="//lieferort" />
									</p>
									<p id="lieferland">
										<xsl:value-of select="//lieferland" />
									</p>
								</div>
							</div>
						</div>
					</div>

					<hr />
					<div id="bestellinformationen">
						<div class="row">
							<h2>Bestellinformationen</h2>
							<div id="bestellnummern">
								<p class="labellike">Bestelldatum:</p>
								<p id="bestelldatum" class="labellike">
									<xsl:value-of select="//bestelldatum" />
								</p>
								<br />
								<p class="labellike">Bestellnummer:</p>
								<p id="bestellnummer" class="labellike">
									<xsl:value-of select="//bestellnummer" />
								</p>
							</div>
						</div>

						<hr />
						<div id="rechnungs_details">
							<div class="row">
								<h2>Rechnungsdetails</h2>
								<div id="bestellpositionen">
									<table>
										<thead>
											<th>Pos</th>
											<th>Bezeichnung</th>
											<th>Menge</th>
											<th>E-Preis €</th>
											<th>G-Preis €</th>
										</thead>
										<tbody>
											<xsl:for-each select="//position">
												<tr class="bestellposition">
													<td class="pos">
														<xsl:value-of select="pos" />
													</td>
													<td class="bezeichnung">
														<xsl:value-of select="bezeichnung" />
													</td>
													<td class="menge">
														<xsl:value-of select="menge" />
													</td>
													<td class="einzelpreis">
														<xsl:value-of select="einzelpreis" />
													</td>
													<td class="bestellpositions_gesamtpreis">
														<xsl:value-of select="bestellpositions_gesamtpreis" />
													</td>
												</tr>
											</xsl:for-each>
										</tbody>
									</table>
								</div>
							</div>

							<hr />
							<div class="row">
								<div id="gesamtpreis">
									<h2 style="display: inline-block; margin-right: 30px">Gesamtpreis:</h2>
									<p id="preis" style="display: inline">
										<xsl:value-of select="//preis" />
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
