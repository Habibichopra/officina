<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/OfficineManager.php";
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    echo rispostaErrore("Accesso negato");
    exit;
}

$tipo        = $_POST['tipo']        ?? '';
$descrizione = trim($_POST['descrizione'] ?? '');
$costo       = (float)($_POST['costo']    ?? 0);

if (!$tipo || !$descrizione || $costo <= 0) {
    echo rispostaErrore("Compila tutti i campi");
    exit;
}

$m = new OfficineManager();

if ($tipo === 'servizio')       $ok = $m->aggiungiServizio($descrizione, $costo);
elseif ($tipo === 'pezzo')      $ok = $m->aggiungiPezzo($descrizione, $costo);
elseif ($tipo === 'accessorio') $ok = $m->aggiungiAccessorio($descrizione, $costo);
else {
    echo rispostaErrore("Tipo non valido");
    exit;
}

echo $ok ? rispostaOk("Elemento aggiunto") : rispostaErrore("Errore inserimento");
