<?php require "../config/header.php"; ?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Pannello Admin</title>
</head>

<body>

    <h2>Pannello Admin</h2>
    <a href="../api/logout.php">Logout</a>
    <hr>

    <h3>Aggiungi elemento</h3>

    <label>Tipo:</label>
    <select id="tipoItem" onchange="aggiornaCampi()">
        <option value="servizio">Servizio</option>
        <option value="pezzo">Pezzo di ricambio</option>
        <option value="accessorio">Accessorio</option>
    </select><br><br>

    <label>Descrizione:</label>
    <input type="text" id="descrizioneInput" placeholder="Es. Cambio olio"><br><br>

    <label id="labelCosto">Costo orario €:</label>
    <input type="number" id="costoInput" step="0.01" min="0"><br><br>

    <button onclick="aggiungi()">Aggiungi</button>
    <p id="msgAggiungi"></p>

    <hr>

    <h3>Associa servizio a officina</h3>

    <label>Officina:</label>
    <select id="selectOfficina"></select><br><br>

    <label>Servizio:</label>
    <select id="selectServizio"></select><br><br>

    <hr>

    <h3>Associa pezzo a officina</h3>

    <label>Officina:</label>
    <select id="selectOfficinaPezzo"></select><br><br>

    <label>Pezzo:</label>
    <select id="selectPezzo"></select><br><br>

    <label>Quantità:</label>
    <input type="number" id="quantitaPezzo" min="0" value="0"><br><br>

    <button onclick="associaPezzo()">Associa Pezzo</button>
    <p id="msgAssociaPezzo"></p>

    <hr>

    <h3>Associa accessorio a officina</h3>

    <label>Officina:</label>
    <select id="selectOfficinaAccessorio"></select><br><br>

    <label>Accessorio:</label>
    <select id="selectAccessorio"></select><br><br>

    <label>Quantità:</label>
    <input type="number" id="quantitaAccessorio" min="0" value="0"><br><br>

    <button onclick="associaAccessorio()">Associa Accessorio</button>
    <p id="msgAssociaAccessorio"></p>

    <hr>

    <h3>Elementi nel catalogo</h3>
    <button onclick="mostraLista('servizi')">Servizi</button>
    <button onclick="mostraLista('pezzi')">Pezzi</button>
    <button onclick="mostraLista('accessori')">Accessori</button>
    <div id="listaElementi"></div>

    <script src="../js/admin.js"></script>
</body>

</html>