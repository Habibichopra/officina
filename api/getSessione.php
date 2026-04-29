<?php
header("Content-Type: application/json");
session_start();

if (isset($_SESSION['tipo'])) {
    echo json_encode(["loggato" => true, "tipo" => $_SESSION['tipo'], "nome" => $_SESSION['username'] ?? $_SESSION['mail']]);
} else {
    echo json_encode(["loggato" => false]);
}
