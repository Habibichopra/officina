<?php
function rispostaOk($messaggio, $dati = null) {
    return json_encode(["status" => true, "messaggio" => $messaggio, "dati" => $dati]);
}

function rispostaErrore($messaggio) {
    return json_encode(["status" => false, "messaggio" => $messaggio]);
}
