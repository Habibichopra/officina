<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/OfficineManager.php";

$m = new OfficineManager();
echo json_encode($m->getTuttiAccessori());
?>