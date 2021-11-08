CREATE DATABASE hengelsport;

USE hengelsport;

CREATE TABLE locatie(
    ID INT NOT NULL AUTO_INCREMENT,
    locatie VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(ID)
);

CREATE TABLE leverancier(
    ID INT NOT NULL AUTO_INCREMENT,
    naam VARCHAR(255),
    telefoon VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(ID)
);

CREATE TABLE product(
    ID INT NOT NULL AUTO_INCREMENT,
    naam VARCHAR(255),
    type VARCHAR(255),
    leverancier_id INT NOT NULL,
    inkoopprijs DECIMAL(15,2),
    verkoopprijs DECIMAL(15,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(leverancier_id) REFERENCES leverancier(ID),
    PRIMARY KEY(ID)
);

CREATE TABLE voorraad(
    ID INT NOT NULL AUTO_INCREMENT,
    locatie_id INT NOT NULL,
    product_id INT NOT NULL,
    aantal INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(locatie_id) REFERENCES locatie(ID),
    FOREIGN KEY(product_id) REFERENCES product(ID) ON DELETE CASCADE,
    PRIMARY KEY(ID)
);

CREATE TABLE role(
    ID INT NOT NULL AUTO_INCREMENT,
    naam VARCHAR(255),
    PRIMARY KEY(ID)
);

CREATE TABLE medewerker(
    ID INT NOT NULL AUTO_INCREMENT,
    voornaam VARCHAR(255),
    tussenvoegsel VARCHAR(255),
    achternaam VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    gebruikersnaam VARCHAR(255) UNIQUE,
    wachtwoord VARCHAR(255),
    role_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(role_id) REFERENCES role(ID),
    PRIMARY KEY(ID)
);

INSERT INTO role(ID, naam)
VALUES(NULL, 'medewerker'),
      (NULL, 'admin');


INSERT INTO locatie(ID, locatie)
VALUES(NULL, 'Amsterdam'),
	  (NULL, 'Rotterdam');

INSERT INTO leverancier(ID, naam, telefoon)
VALUES(NULL, 'De Stoel', '06 12 123 1234'),
	  (NULL, 'De Broek', '06 12 345 678');

INSERT INTO product(ID, naam, type, leverancier_id, inkoopprijs, verkoopprijs)
VALUES(NULL, 'hengel', 'AC500', 1, 25.95, 39.95),
	  (NULL, 'paraplu', 'AC523', 2, 40.95, 59.95);
    
INSERT INTO voorraad(ID, locatie_id, product_id, aantal)
VALUES(NULL, 1, 2, 3),
	  (NULL, 2, 1, 3);