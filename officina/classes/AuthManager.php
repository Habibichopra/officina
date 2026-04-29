<?php

require_once __DIR__ . "/DatabaseManager.php";

class AuthManager
{
    public static function login($cognome, $nome, $password): bool
    {
        $db = new DatabaseManager();
        $conn = $db->getConnection();
        
        // CORRETTO: l'ordine dei parametri era sbagliato
        $stmt = $conn->prepare("SELECT cognome, nome, password FROM cliente WHERE cognome = ? AND nome = ? AND password = ?");
        
        $stmt->bind_param("sss", $cognome, $nome, $password);
        $stmt->execute();

        $result = $stmt->get_result();

        return ($result && $result->num_rows === 1);
    }

    public static function register($cognome, $nome, $telefono, $password): bool
    {
        $db = new DatabaseManager();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("INSERT INTO cliente (cognome, nome, telefono, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $cognome, $nome, $telefono, $password);
        
        return $stmt->execute();
    }
}