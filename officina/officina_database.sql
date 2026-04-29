

CREATE TABLE Officina (
    id_officina INT PRIMARY KEY AUTO_INCREMENT,
    denominazione VARCHAR(100),
    indirizzo VARCHAR(150)
);

CREATE TABLE Cliente (
    id_cliente INT PRIMARY KEY AUTO_INCREMENT,  
    cognome VARCHAR(50),
    nome VARCHAR(50),
    telefono VARCHAR(20),
    password VARCHAR(100)  
);

CREATE TABLE Autoveicolo (
    targa VARCHAR(10) PRIMARY KEY,
    telaio VARCHAR(50),
    descrizione VARCHAR(100),
    anno_costruzione INT,
    id_cliente INT,
    FOREIGN KEY (id_cliente) REFERENCES Cliente(id_cliente)
);

CREATE TABLE Dipendente (
    id_dipendente INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    password VARCHAR(100),
    id_officina INT,
    FOREIGN KEY (id_officina) REFERENCES Officina(id_officina)
);

CREATE TABLE Tipo_Intervento (
    id_tipo INT PRIMARY KEY AUTO_INCREMENT,
    descrizione VARCHAR(100)
);

CREATE TABLE Intervento (
    id_intervento INT PRIMARY KEY AUTO_INCREMENT,
    data DATE,
    id_officina INT,
    targa VARCHAR(10),
    id_cliente INT,
    id_tipo INT,
    FOREIGN KEY (id_officina) REFERENCES Officina(id_officina),
    FOREIGN KEY (targa) REFERENCES Autoveicolo(targa),
    FOREIGN KEY (id_cliente) REFERENCES Cliente(id_cliente),
    FOREIGN KEY (id_tipo) REFERENCES Tipo_Intervento(id_tipo)
);

CREATE TABLE Servizio (
    id_servizio INT PRIMARY KEY AUTO_INCREMENT,
    descrizione VARCHAR(100),
    costo_orario DECIMAL(10,2)
);

CREATE TABLE Pezzo_Ricambio (
    id_pezzo INT PRIMARY KEY AUTO_INCREMENT,
    descrizione VARCHAR(100),
    costo_unitario DECIMAL(10,2)
);

CREATE TABLE Accessorio (
    id_accessorio INT PRIMARY KEY AUTO_INCREMENT,
    descrizione VARCHAR(100),
    costo_unitario DECIMAL(10,2)
);

CREATE TABLE Offre (
    id_officina INT,
    id_servizio INT,
    PRIMARY KEY (id_officina, id_servizio),
    FOREIGN KEY (id_officina) REFERENCES Officina(id_officina),
    FOREIGN KEY (id_servizio) REFERENCES Servizio(id_servizio)
);

CREATE TABLE Magazzino_Pezzi (
    id_officina INT,
    id_pezzo INT,
    quantita INT,
    PRIMARY KEY (id_officina, id_pezzo),
    FOREIGN KEY (id_officina) REFERENCES Officina(id_officina),
    FOREIGN KEY (id_pezzo) REFERENCES Pezzo_Ricambio(id_pezzo)
);

CREATE TABLE Magazzino_Accessori (
    id_officina INT,
    id_accessorio INT,
    quantita INT,
    PRIMARY KEY (id_officina, id_accessorio),
    FOREIGN KEY (id_officina) REFERENCES Officina(id_officina),
    FOREIGN KEY (id_accessorio) REFERENCES Accessorio(id_accessorio)
);

CREATE TABLE Comprende (
    id_intervento INT,
    id_servizio INT,
    ore INT,
    PRIMARY KEY (id_intervento, id_servizio),
    FOREIGN KEY (id_intervento) REFERENCES Intervento(id_intervento),
    FOREIGN KEY (id_servizio) REFERENCES Servizio(id_servizio)
);

CREATE TABLE Utilizza (
    id_intervento INT,
    id_pezzo INT,
    quantita INT,
    PRIMARY KEY (id_intervento, id_pezzo),
    FOREIGN KEY (id_intervento) REFERENCES Intervento(id_intervento),
    FOREIGN KEY (id_pezzo) REFERENCES Pezzo_Ricambio(id_pezzo)
);

CREATE TABLE Usa (
    id_intervento INT,
    id_accessorio INT,
    quantita INT,
    PRIMARY KEY (id_intervento, id_accessorio),
    FOREIGN KEY (id_intervento) REFERENCES Intervento(id_intervento),
    FOREIGN KEY (id_accessorio) REFERENCES Accessorio(id_accessorio)
);
