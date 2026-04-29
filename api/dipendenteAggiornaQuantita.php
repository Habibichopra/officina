<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/OfficineManager.php";
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'dipendente') {
    echo rispostaErrore("Accesso non autorizzato");
    exit;
}

$idOfficina = $_SESSION['id_officina'] ?? null;
if (!$idOfficina) {
    echo rispostaErrore("Nessuna officina associata a questo account");
    exit;
}

$idPezzo    = (int)($_POST['id_pezzo']    ?? 0);
$operazione = $_POST['operazione']        ?? '';
$quantita   = (int)($_POST['quantita']    ?? 0);

if (!$idPezzo || !in_array($operazione, ['aggiungi', 'rimuovi']) || $quantita <= 0) {
    echo rispostaErrore("Dati non validi");
    exit;
}

$delta = $operazione === 'aggiungi' ? $quantita : -$quantita;

$manager = new OfficineManager();
if ($manager->aggiornQuantitaPezzo($idOfficina, $idPezzo, $delta)) {
    echo rispostaOk("Quantità aggiornata con successo");
} else {
    echo rispostaErrore("Operazione non riuscita: quantità insufficiente");
}
