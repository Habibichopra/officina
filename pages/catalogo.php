<?php require "../config/header.php"; ?>
<!DOCTYPE html>
<html lang="it">
<head><meta charset="UTF-8"><title>Catalogo</title></head>
<body>

<div id="barraUtente"></div>

<h2>Catalogo: <span id="nomeOfficina"></span></h2>

<button onclick="mostraSezione('servizi')">Servizi</button>
<button onclick="mostraSezione('pezzi')">Pezzi di ricambio</button>
<button onclick="mostraSezione('accessori')">Accessori</button>

<div id="contenuto"></div>

<script src="../js/utils.js"></script>
<script src="../js/catalogo.js"></script>
</body>
</html>
