<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/OfficineManager.php";

$idServizio = isset($_GET['id_servizio']) && $_GET['id_servizio'] !== '' ? (int)$_GET['id_servizio'] : null;
$idPezzo = isset($_GET['id_pezzo']) && $_GET['id_pezzo'] !== '' ? (int)$_GET['id_pezzo'] : null;
$idAccessorio = isset($_GET['id_accessorio']) && $_GET['id_accessorio'] !== '' ? (int)$_GET['id_accessorio'] : null;

$m = new OfficineManager();
$officine = $m->getOfficineCompatibili($idServizio, $idPezzo, $idAccessorio);
echo json_encode($officine);
?>