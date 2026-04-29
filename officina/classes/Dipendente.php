<?php


class Dipendente
{

    private $conn;


    public function __construct()
    {

        require_once __DIR__ . "/DatabaseManager.php";
        $db = new DatabaseManager();
        $this->conn = $db->getConnection();
    }




    public function getServizi()
    {
        $result = $this->conn->query("SELECT id_servizio, descrizione, costo_orario FROM Servizio");
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

        public function getPezziRicambio()
    {
        $result = $this->conn->query("SELECT id_pezzo, descrizione, costo_unitario FROM Pezzo_Ricambio");
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

        public function getAccessori()
    {
        $result = $this->conn->query("SELECT id_accessorio, descrizione, costo_unitario FROM Accessorio");
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }


 
}