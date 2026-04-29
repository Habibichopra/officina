<?php require "../config/header.php"; ?>
<!DOCTYPE html>
<html lang="it">
<head><meta charset="UTF-8"><title>Home</title></head>
<body>

<div id="barraUtente"></div>

<h2>Seleziona un'officina</h2>
<select id="selectOfficina">
    <option value="">-- Scegli --</option>
</select>

<div id="infoOfficina" style="display:none;">
    <p><strong>Denominazione:</strong> <span id="denominazione"></span></p>
    <p><strong>Indirizzo:</strong> <span id="indirizzo"></span></p>
    <a id="linkCatalogo" href="#">Vai al catalogo</a>
</div>

<script src="../js/utils.js"></script>
<script src="../js/index.js"></script>
</body>
</html>
