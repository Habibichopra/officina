<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/OfficineManager.php";
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    echo rispostaErrore("Accesso negato");
    exit;
}

$idOfficina = (int)($_POST['id_officina'] ?? 0);
$idPezzo = (int)($_POST['id_pezzo'] ?? 0);
$quantita = (int)($_POST['quantita'] ?? 0);

if (!$idOfficina || !$idPezzo || $quantita < 0) {
    echo rispostaErrore("Dati mancanti o invalidi");
    exit;
}

$m = new OfficineManager();
echo $m->associaPezzo($idOfficina, $idPezzo, $quantita)
    ? rispostaOk("Pezzo associato")
    : rispostaErrore("Errore associazione");
?>