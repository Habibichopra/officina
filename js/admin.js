let datiCatalogo = null;

window.addEventListener("load", async function() {
    let sessione = await (await fetch("../api/getSessione.php")).json();
    if (!sessione.loggato || sessione.tipo !== "admin") {
        alert("Accesso riservato agli admin");
        location.href = "./login_admin.html";
        return;
    }
    let risposta = await fetch("../api/adminGetCatalogo.php");
    datiCatalogo = await risposta.json();
    let selOfficina = document.getElementById("selectOfficina");
    datiCatalogo.officine.forEach(function(o) {
        let opt = document.createElement("option");
        opt.value = o.id_officina;
        opt.textContent = o.denominazione;
        selOfficina.appendChild(opt);
    });
    // Copia per gli altri select
    let selOfficinaPezzo = document.getElementById("selectOfficinaPezzo");
    let selOfficinaAccessorio = document.getElementById("selectOfficinaAccessorio");
    datiCatalogo.officine.forEach(function(o) {
        let opt1 = document.createElement("option");
        opt1.value = o.id_officina;
        opt1.textContent = o.denominazione;
        selOfficinaPezzo.appendChild(opt1.cloneNode(true));
        selOfficinaAccessorio.appendChild(opt1.cloneNode(true));
    });

    aggiornaSelectServizi();
    aggiornaSelectPezzi();
    aggiornaSelectAccessori();

    mostraLista("servizi");
});

function aggiornaSelectServizi() {
    let selServizio = document.getElementById("selectServizio");
    selServizio.innerHTML = "";
    datiCatalogo.servizi.forEach(function(s) {
        let opt = document.createElement("option");
        opt.value = s.id_servizio;
        opt.textContent = s.descrizione + " (" + parseFloat(s.costo_orario).toFixed(2) + " €/h)";
        selServizio.appendChild(opt);
    });
}

function aggiornaSelectPezzi() {
    let selPezzo = document.getElementById("selectPezzo");
    selPezzo.innerHTML = "";
    datiCatalogo.pezzi.forEach(function(p) {
        let opt = document.createElement("option");
        opt.value = p.id_pezzo;
        opt.textContent = p.descrizione + " (" + parseFloat(p.costo_unitario).toFixed(2) + " €)";
        selPezzo.appendChild(opt);
    });
}

function aggiornaSelectAccessori() {
    let selAccessorio = document.getElementById("selectAccessorio");
    selAccessorio.innerHTML = "";
    datiCatalogo.accessori.forEach(function(a) {
        let opt = document.createElement("option");
        opt.value = a.id_accessorio;
        opt.textContent = a.descrizione + " (" + parseFloat(a.costo_unitario).toFixed(2) + " €)";
        selAccessorio.appendChild(opt);
    });
}

function aggiornaCampi() {
    let tipo = document.getElementById("tipoItem").value;
    document.getElementById("labelCosto").textContent =
        tipo === "servizio" ? "Costo orario (€):" : "Costo unitario (€):";
}

async function aggiungi() {
    let tipo        = document.getElementById("tipoItem").value;
    let descrizione = document.getElementById("descrizioneInput").value.trim();
    let costo       = document.getElementById("costoInput").value;
    let msg         = document.getElementById("msgAggiungi");

    if (!descrizione || !costo) { msg.textContent = "Compila tutti i campi"; return; }

    let dati = new URLSearchParams({ tipo: tipo, descrizione: descrizione, costo: costo });
    let risposta = await fetch("../api/adminAggiungi.php", { method: "POST", body: dati });
    let json = await risposta.json();

    msg.textContent = json.messaggio;

    if (json.status) {
        document.getElementById("descrizioneInput").value = "";
        document.getElementById("costoInput").value = "";
        
        let r = await fetch("../api/adminGetCatalogo.php");
        datiCatalogo = await r.json();
        aggiornaSelectServizi();
        aggiornaSelectPezzi();
        aggiornaSelectAccessori();
        mostraLista(tipo === "servizio" ? "servizi" : tipo === "pezzo" ? "pezzi" : "accessori");
    }
}

async function associa() {
    let idOfficina = document.getElementById("selectOfficina").value;
    let idServizio = document.getElementById("selectServizio").value;
    let msg        = document.getElementById("msgAssocia");

    if (!idOfficina || !idServizio) { msg.textContent = "Seleziona officina e servizio"; return; }

    let dati = new URLSearchParams({ id_officina: idOfficina, id_servizio: idServizio });
    let risposta = await fetch("../api/adminAssociaServizio.php", { method: "POST", body: dati });
    let json = await risposta.json();

    msg.textContent = json.messaggio;
}

async function associaPezzo() {
    let idOfficina = document.getElementById("selectOfficinaPezzo").value;
    let idPezzo = document.getElementById("selectPezzo").value;
    let quantita = document.getElementById("quantitaPezzo").value;
    let msg = document.getElementById("msgAssociaPezzo");

    if (!idOfficina || !idPezzo || quantita === "") { msg.textContent = "Seleziona officina, pezzo e quantità"; return; }

    let dati = new URLSearchParams({ id_officina: idOfficina, id_pezzo: idPezzo, quantita: quantita });
    let risposta = await fetch("../api/adminAssociaPezzo.php", { method: "POST", body: dati });
    let json = await risposta.json();

    msg.textContent = json.messaggio;
}

async function associaAccessorio() {
    let idOfficina = document.getElementById("selectOfficinaAccessorio").value;
    let idAccessorio = document.getElementById("selectAccessorio").value;
    let quantita = document.getElementById("quantitaAccessorio").value;
    let msg = document.getElementById("msgAssociaAccessorio");

    if (!idOfficina || !idAccessorio || quantita === "") { msg.textContent = "Seleziona officina, accessorio e quantità"; return; }

    let dati = new URLSearchParams({ id_officina: idOfficina, id_accessorio: idAccessorio, quantita: quantita });
    let risposta = await fetch("../api/adminAssociaAccessorio.php", { method: "POST", body: dati });
    let json = await risposta.json();

    msg.textContent = json.messaggio;
}

function mostraLista(tipo) {
    let div = document.getElementById("listaElementi");
    let elementi = datiCatalogo[tipo];

    div.innerHTML = "<h4>" + tipo + "</h4>";

    if (!elementi || elementi.length === 0) {
        div.innerHTML += "<p>Nessun elemento.</p>";
        return;
    }

    let tabella = "<table border='1' cellpadding='6'><tr><th>Descrizione</th><th>Costo</th></tr>";
    elementi.forEach(function(el) {
        let prezzo = el.costo_orario
            ? parseFloat(el.costo_orario).toFixed(2) + " €/h"
            : parseFloat(el.costo_unitario).toFixed(2) + " €";
        tabella += "<tr><td>" + el.descrizione + "</td><td>" + prezzo + "</td></tr>";
    });
    div.innerHTML += tabella + "</table>";
}
