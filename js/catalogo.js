let catalogo = null;

window.addEventListener("load", async function() {
    let params = new URLSearchParams(window.location.search);
    let idOfficina = params.get("id") || sessionStorage.getItem("id_officina");

    if (!idOfficina) {
        document.getElementById("contenuto").textContent = "Nessuna officina selezionata.";
        return;
    }

    let risposta = await fetch("../api/getCatalogo.php?id_officina=" + idOfficina);
    catalogo = await risposta.json();

    document.getElementById("nomeOfficina").textContent = catalogo.officina.denominazione;
    mostraSezione("servizi");
});

function mostraSezione(tipo) {
    let contenuto = document.getElementById("contenuto");
    let elementi = catalogo[tipo];

    contenuto.innerHTML = "<h3>" + tipo + "</h3>";

    if (elementi.length === 0) {
        contenuto.innerHTML += "<p>Nessun elemento disponibile.</p>";
        return;
    }

    elementi.forEach(function(el) {
        let div = document.createElement("div");
        div.style.border = "1px solid #ccc";
        div.style.margin = "6px 0";
        div.style.padding = "8px";

        let prezzo = el.costo_orario
            ? parseFloat(el.costo_orario).toFixed(2) + " €/h"
            : parseFloat(el.costo_unitario).toFixed(2) + " €";

        let qtxt = el.quantita !== undefined ? " — Quantita: " + el.quantita : "";
        div.innerHTML = "<strong>" + el.descrizione + "</strong> — " + prezzo + qtxt;
        contenuto.appendChild(div);
    });
}
