CREATE DATABASE IF NOT EXISTS casa_automobilistica CHARACTER SET utf8mb4;
USE casa_automobilistica;

CREATE TABLE Officina (
    id_officina   INT PRIMARY KEY AUTO_INCREMENT,
    denominazione VARCHAR(100) NOT NULL,
    indirizzo     VARCHAR(150) NOT NULL
);

CREATE TABLE Admin (
    id_admin  INT PRIMARY KEY AUTO_INCREMENT,
    username  VARCHAR(50)  NOT NULL UNIQUE,
    password  VARCHAR(100) NOT NULL
);

CREATE TABLE Dipendente (
    id_dipendente INT PRIMARY KEY AUTO_INCREMENT,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password      VARCHAR(100) NOT NULL,
    id_officina   INT,
    FOREIGN KEY (id_officina) REFERENCES Officina(id_officina)
);

CREATE TABLE Cliente (
    id_cliente       INT PRIMARY KEY AUTO_INCREMENT,
    mail             VARCHAR(100) NOT NULL UNIQUE,
    password         VARCHAR(100) NOT NULL,
    OTP              VARCHAR(36)  NULL,
    dataScadenzaOTP  DATETIME     NULL,
    IsAbilitato      BOOLEAN      NOT NULL DEFAULT FALSE
);

CREATE TABLE Servizio (
    id_servizio  INT PRIMARY KEY AUTO_INCREMENT,
    descrizione  VARCHAR(100)  NOT NULL,
    costo_orario DECIMAL(10,2) NOT NULL
);

CREATE TABLE Pezzo_Ricambio (
    id_pezzo       INT PRIMARY KEY AUTO_INCREMENT,
    descrizione    VARCHAR(100)  NOT NULL,
    costo_unitario DECIMAL(10,2) NOT NULL
);

CREATE TABLE Accessorio (
    id_accessorio  INT PRIMARY KEY AUTO_INCREMENT,
    descrizione    VARCHAR(100)  NOT NULL,
    costo_unitario DECIMAL(10,2) NOT NULL
);

CREATE TABLE Offre (
    id_officina INT NOT NULL,
    id_servizio INT NOT NULL,
    PRIMARY KEY (id_officina, id_servizio),
    FOREIGN KEY (id_officina) REFERENCES Officina(id_officina),
    FOREIGN KEY (id_servizio) REFERENCES Servizio(id_servizio)
);

CREATE TABLE Magazzino_Pezzi (
    id_officina INT NOT NULL,
    id_pezzo    INT NOT NULL,
    quantita    INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id_officina, id_pezzo),
    FOREIGN KEY (id_officina) REFERENCES Officina(id_officina),
    FOREIGN KEY (id_pezzo)    REFERENCES Pezzo_Ricambio(id_pezzo)
);

CREATE TABLE Magazzino_Accessori (
    id_officina   INT NOT NULL,
    id_accessorio INT NOT NULL,
    quantita      INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id_officina, id_accessorio),
    FOREIGN KEY (id_officina)   REFERENCES Officina(id_officina),
    FOREIGN KEY (id_accessorio) REFERENCES Accessorio(id_accessorio)
);


INSERT INTO Admin (username, password) VALUES 
    ('abbas', 'abbas');

INSERT INTO Officina (denominazione, indirizzo) VALUES
    ('Officina Centrale', 'Via Roma 1, Milano'),
    ('Officina Nord',     'Corso Garibaldi 15, Torino'),
    ('Officina Sud',      'Via Napoli 8, Roma');

INSERT INTO Servizio (descrizione, costo_orario) VALUES
    ('Tagliando',        50.00),
    ('Cambio freni',     60.00),
    ('Cambio gomme',     40.00),
    ('Revisione motore', 80.00);

INSERT INTO Pezzo_Ricambio (descrizione, costo_unitario) VALUES
    ('Filtro olio',     12.50),
    ('Pastiglie freno', 35.00),
    ('Pneumatico',      89.90),
    ('Candele',          8.00);

INSERT INTO Accessorio (descrizione, costo_unitario) VALUES
    ('Tappetini gomma', 29.90),
    ('Coprivolante',    15.00),
    ('Profumatore',      5.90);


INSERT INTO Offre (id_officina, id_servizio) VALUES
    (1, 1), (1, 2), (1, 3), (1, 4),
    (2, 1), (2, 2), (2, 3), (2, 4),
    (3, 1), (3, 2), (3, 3), (3, 4);

INSERT INTO Magazzino_Pezzi (id_officina, id_pezzo, quantita) VALUES
    (1, 1, 20), (1, 2, 15), (1, 3, 10), (1, 4, 25),
    (2, 1, 18), (2, 2, 12), (2, 3, 8),  (2, 4, 30),
    (3, 1, 22), (3, 2, 20), (3, 3, 12), (3, 4, 28);

INSERT INTO Magazzino_Accessori (id_officina, id_accessorio, quantita) VALUES
    (1, 1, 5), (1, 2, 8), (1, 3, 15),
    (2, 1, 7), (2, 2, 6), (2, 3, 12),
    (3, 1, 4), (3, 2, 10), (3, 3, 20);
