-- user - Login - Registrierung --

	create table if not exists passwort(
		pw_id 		int AUTO_INCREMENT
		,pw		VARCHAR(2000)
		,last_login date
		,PRIMARY KEY(pw_id)
	);

	create table if not exists zahlungsinformationen(
		zi_id 			int AUTO_INCREMENT
		,banknamen 		VARCHAR(500)
		,land			VARCHAR(100)
		,BIC			VARCHAR(100)
		,bezeichnung	VARCHAR(100)
		,iban			VARCHAR(100)
		,PRIMARY KEY(zi_id)
	);

	create table if not exists zahlungsmethodexuser(
		z_id 		int AUTO_INCREMENT
		,u_id_ref 	int not null
		,zi_id_ref 	int not null
		,PRIMARY KEY(z_id)
	);

	create table if not exists user (
		u_id 			INT AUTO_INCREMENT
		,titel 			VARCHAR(30)
		,vorname 		VARCHAR(30)NOT NULL
		,nachname 		VARCHAR(30)NOT NULL
		,anrede			VARCHAR(10)NOT NULL
		,pw_id_ref		int NOT NULL
		,email 			VARCHAR(100) UNIQUE
		,geburtsdatum 	date
		,land 			VARCHAR(50)
		,plz			VARCHAR(10)
		,ort			VARCHAR(50)
		,strasse		VARCHAR(100)
		,hausnr			VARCHAR(10)
		,adresszusatz	VARCHAR(100)
		,z_id_ref		int not null
		,PRIMARY KEY(u_id)
	);

	-- Foreign Keys
		-- Foreing Keys for zahlungsmethodexuser
			alter table zahlungsmethodexuser
			add FOREIGN KEY(u_id_ref) REFERENCES user (u_id);

			alter table zahlungsmethodexuser
			add FOREIGN KEY(zi_id_ref) REFERENCES zahlungsinformationen (zi_id);
		--

		-- Foreign Keys for user
			alter table user
			add FOREIGN KEY(pw_id_ref) REFERENCES passwort (pw_id);

			alter table user
			add FOREIGN KEY(z_id_ref) REFERENCES zahlungsmethodexuser (z_id);
		--
	--
--

-- produkte - Bilder --

	create table if not exists produkt_kategorie(
		p_k_id 			int AUTO_INCREMENT
		,bezeichnung	VARCHAR(100)
		,p_k_b_id		int DEFAULT 1	-- Info: Sinn: Wenn kein Bild hinterlegt ist, dann soll ein Placeholder genutzt werden
		,PRIMARY KEY(p_k_id)
	);

	create table if not exists produkt_kategorie_bild(
		p_k_b_id 	int AUTO_INCREMENT
		,image 		Longblob
		,PRIMARY KEY(p_k_b_id)
	);

	create table if not exists produktbild(
		p_b_id 			int AUTO_INCREMENT
		,image 			Longblob
		,order_id		VARCHAR(2000)
		,image_name		VARCHAR(2000)
		,PRIMARY KEY(p_b_id)
	);

	create table if not exists hersteller(
		oem_id 			int AUTO_INCREMENT
		,bezeichnung 	VARCHAR(100)
		,PRIMARY KEY(oem_id)
	);

	create table if not exists produkt(
		p_id 			int AUTO_INCREMENT
		,bezeichnung	VARCHAR(2000)
		,p_b_id_ref		int DEFAULT 1 NOT NULL	-- Info: Sinn: Wenn kein Bild hinterlegt ist, dann soll ein Placeholder genutzt werden
		,eigenschaft_1	VARCHAR(100)
		,eigenschaft_2	VARCHAR(100)
		,eigenschaft_3	VARCHAR(100)
		,eigenschaft_4	VARCHAR(100)
		,eigenschaft_5	VARCHAR(100)
		,eigenschaft_6	VARCHAR(100)
		,details		VARCHAR(2000)
		,menge			int not null
		,akt_preis		decimal(8,2)
		,oem_id_ref		int
		,p_k_id_ref		int
		,PRIMARY KEY(p_id)
	);
	-- Foreign Keys
		-- Foreign Keys for produkt_kategorie
			alter table produkt_kategorie
			add FOREIGN KEY(p_k_b_id) REFERENCES produkt_kategorie_bild(p_k_b_id);
		--

		-- Foreign Keys for produkt
			alter table produkt
			add FOREIGN KEY(p_k_id_ref) REFERENCES produkt_kategorie(p_k_id);

			alter table produkt
			add FOREIGN KEY(oem_id_ref) REFERENCES hersteller(oem_id);

			alter table produkt
			add FOREIGN KEY(p_b_id_ref) REFERENCES produktbild(p_b_id);
		--
	--
--

-- bestellung --

	create table if not exists bestell_Historie(
		b_h_id			int AUTO_INCREMENT
		,u_id_ref		int not null
		,b_id_ref		int not null
		,PRIMARY KEY(b_h_id)
	);



	create table if not exists bestellposition(
		b_p_id			int AUTO_INCREMENT
		,b_id_ref		int not null
		,p_id_ref		int not null
		,pos			int				-- Info:  Muss vom Frontend mitgegeben werden | Am besten aus der Warenkorb Logik
		,menge			int
		,PRIMARY KEY(b_p_id)
	);


	create table if not exists bestellung(
		b_id 						int AUTO_INCREMENT
		,b_p_id_ref					int
		,u_id_ref					int
		,gesamtkosten				decimal(10,2)
		,zi_id_ref					int
		,bestell_datum				date
		,anzahl_bestellpositionen	int
		,PRIMARY KEY(b_id)
	);
	-- Foreign Keys
		-- Foreign Keys for bestellung
			alter table bestellung
			add FOREIGN KEY(zi_id_ref) REFERENCES zahlungsinformationen(zi_id);

			alter table bestellung
			add FOREIGN KEY(b_p_id_ref) REFERENCES bestellposition(b_p_id);

			alter table bestellung
			add FOREIGN KEY(u_id_ref) REFERENCES user(u_id);
		--

		-- Foreign Keys for bestell_Historie
			alter table bestell_Historie
			add FOREIGN KEY(u_id_ref) REFERENCES user(u_id);

			alter table bestell_Historie
			add FOREIGN KEY(b_id_ref) REFERENCES bestellung(b_id);
		--

		-- Foreign Keys for bestellposition
			alter table bestellposition
			add FOREIGN KEY(b_id_ref) REFERENCES bestellung(b_id);

			alter table bestellposition
			add FOREIGN KEY(p_id_ref) REFERENCES produkt(p_id);
		--
	--
--