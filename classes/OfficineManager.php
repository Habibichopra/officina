<?php
require_once __DIR__ . "/DatabaseManager.php";

class OfficineManager {
    private $conn;

    public function __construct() {
        $this->conn = (new DatabaseManager())->getConn();
    }

    public function getTutteLeOfficine() {
        return $this->conn->query("SELECT id_officina, denominazione, indirizzo FROM Officina ORDER BY denominazione")->fetch_all(MYSQLI_ASSOC);
    }

    public function getOfficina($id) {
        $stmt = $this->conn->prepare("SELECT id_officina, denominazione, indirizzo FROM Officina WHERE id_officina=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // servizi associati a una officina (può essere vuoto)
    public function getServizi($idOfficina) {
        $stmt = $this->conn->prepare(
            "SELECT s.id_servizio, s.descrizione, s.costo_orario FROM Servizio s
             INNER JOIN Offre o ON s.id_servizio = o.id_servizio
             WHERE o.id_officina=?"
        );
        $stmt->bind_param("i", $idOfficina);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPezzi($idOfficina) {
        $stmt = $this->conn->prepare(
            "SELECT p.descrizione, p.costo_unitario, mp.quantita FROM Pezzo_Ricambio p
             INNER JOIN Magazzino_Pezzi mp ON p.id_pezzo = mp.id_pezzo
             WHERE mp.id_officina=?"
        );
        $stmt->bind_param("i", $idOfficina);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAccessori($idOfficina) {
        $stmt = $this->conn->prepare(
            "SELECT a.descrizione, a.costo_unitario, ma.quantita FROM Accessorio a
             INNER JOIN Magazzino_Accessori ma ON a.id_accessorio = ma.id_accessorio
             WHERE ma.id_officina=?"
        );
        $stmt->bind_param("i", $idOfficina);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // tutti i servizi esistenti (per la schermata admin)
    public function getTuttiServizi() {
        return $this->conn->query("SELECT id_servizio, descrizione, costo_orario FROM Servizio ORDER BY descrizione")->fetch_all(MYSQLI_ASSOC);
    }

    public function getTuttiPezzi() {
        return $this->conn->query("SELECT id_pezzo, descrizione, costo_unitario FROM Pezzo_Ricambio ORDER BY descrizione")->fetch_all(MYSQLI_ASSOC);
    }

    public function getTuttiAccessori() {
        return $this->conn->query("SELECT id_accessorio, descrizione, costo_unitario FROM Accessorio ORDER BY descrizione")->fetch_all(MYSQLI_ASSOC);
    }

    // aggiunge un servizio globale (non ancora associato a nessuna officina)
    public function aggiungiServizio($descrizione, $costo) {
        $stmt = $this->conn->prepare("INSERT INTO Servizio (descrizione, costo_orario) VALUES (?,?)");
        $stmt->bind_param("sd", $descrizione, $costo);
        return $stmt->execute();
    }

    public function aggiungiPezzo($descrizione, $costo) {
        $stmt = $this->conn->prepare("INSERT INTO Pezzo_Ricambio (descrizione, costo_unitario) VALUES (?,?)");
        $stmt->bind_param("sd", $descrizione, $costo);
        return $stmt->execute();
    }

    public function aggiungiAccessorio($descrizione, $costo) {
        $stmt = $this->conn->prepare("INSERT INTO Accessorio (descrizione, costo_unitario) VALUES (?,?)");
        $stmt->bind_param("sd", $descrizione, $costo);
        return $stmt->execute();
    }
    public function associaServizio($idOfficina, $idServizio) {
        $stmt = $this->conn->prepare("INSERT IGNORE INTO Offre (id_officina, id_servizio) VALUES (?,?)");
        $stmt->bind_param("ii", $idOfficina, $idServizio);
        return $stmt->execute();
    }

    public function getOfficineCompatibili($idServizio, $idPezzo, $idAccessorio) {
        $query = "SELECT DISTINCT o.id_officina, o.denominazione, o.indirizzo
                  FROM Officina o
                  LEFT JOIN Offre of ON o.id_officina = of.id_officina AND of.id_servizio = ?
                  LEFT JOIN Magazzino_Pezzi mp ON o.id_officina = mp.id_officina AND mp.id_pezzo = ?
                  LEFT JOIN Magazzino_Accessori ma ON o.id_officina = ma.id_officina AND ma.id_accessorio = ?
                  WHERE (of.id_servizio IS NOT NULL OR ? IS NULL)
                    AND (mp.quantita > 0 OR ? IS NULL)
                    AND (ma.quantita > 0 OR ? IS NULL)
                  ORDER BY o.denominazione";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiiiii", $idServizio, $idPezzo, $idAccessorio, $idServizio, $idPezzo, $idAccessorio);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function associaPezzo($idOfficina, $idPezzo, $quantita) {
        $stmt = $this->conn->prepare("INSERT INTO Magazzino_Pezzi (id_officina, id_pezzo, quantita) VALUES (?,?,?) ON DUPLICATE KEY UPDATE quantita = VALUES(quantita)");
        $stmt->bind_param("iii", $idOfficina, $idPezzo, $quantita);
        return $stmt->execute();
    }

    public function associaAccessorio($idOfficina, $idAccessorio, $quantita) {
        $stmt = $this->conn->prepare("INSERT INTO Magazzino_Accessori (id_officina, id_accessorio, quantita) VALUES (?,?,?) ON DUPLICATE KEY UPDATE quantita = VALUES(quantita)");
        $stmt->bind_param("iii", $idOfficina, $idAccessorio, $quantita);
        return $stmt->execute();
    }

    public function getMagazzinoOfficina($idOfficina) {
        $stmt = $this->conn->prepare(
            "SELECT p.id_pezzo, p.descrizione, p.costo_unitario, mp.quantita
             FROM Pezzo_Ricambio p
             INNER JOIN Magazzino_Pezzi mp ON p.id_pezzo = mp.id_pezzo
             WHERE mp.id_officina = ?
             ORDER BY p.descrizione"
        );
        $stmt->bind_param("i", $idOfficina);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function aggiornQuantitaPezzo($idOfficina, $idPezzo, $delta) {
        $stmt = $this->conn->prepare(
            "UPDATE Magazzino_Pezzi SET quantita = quantita + ?
             WHERE id_officina = ? AND id_pezzo = ? AND quantita + ? >= 0"
        );
        $stmt->bind_param("iiii", $delta, $idOfficina, $idPezzo, $delta);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
