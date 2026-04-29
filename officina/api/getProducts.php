<?php

header("Content-Type:application/json");
require_once __DIR__ . "/../misc/functions.php";
require_once __DIR__ . "/../classes/Dipendente.php";

$shop = new Dipendente();
$servizi = $shop->getServizi();
$accessori = $shop->getAccessori();
$pezziRicambio = $shop->getPezziRicambio();



echo json_encode([
    "status" => true,
    "data" => [
        "servizi" => $servizi,
        "accessori" => $accessori,
        "pezziRicambio" => $pezziRicambio
    ]
]);
