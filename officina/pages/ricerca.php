<?php require "../config/header.php"; ?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ricerca Officine Compatibili</title>
    <
</head>
<body>
    <h1>Ricerca Officine Compatibili</h1>
    
    <div class="search-form">
        <h3>Seleziona i criteri di ricerca</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="servizi">Servizio:</label>
                <select id="servizi">
                    <option value="">Seleziona un servizio...</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="pezzi">Pezzo di ricambio:</label>
                <select id="pezzi">
                    <option value="">Seleziona un pezzo...</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="accessori">Accessorio:</label>
                <select id="accessori">
                    <option value="">Seleziona un accessorio...</option>
                </select>
            </div>
        </div>
        
        <div class="button-group">
            <button class="btn-search" onclick="searchOffices()">Cerca Officine</button>
            <button class="btn-reset" onclick="resetForm()">Ripristina</button>
        </div>
    </div>
    
    <div id="results" class="results"></div>
    
    <script src="../js/ricerca.js"></script>
</body>
</html>
