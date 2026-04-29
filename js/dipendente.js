window.addEventListener("load", async function () {
    let sessione = await (await fetch("../api/getSessione.php")).json();
    if (!sessione.loggato || sessione.tipo !== "dipendente") {
        alert("Accesso riservato ai dipendenti");
        location.href = "./login_dipendente.html";
        return;
    }

    await caricaMagazzino();
});

async function caricaMagazzino() {
    let risposta = await fetch("../api/dipendenteGetMagazzino.php");
    let json = await risposta.json();

    let container = document.getElementById("tabellaContainer");

    if (!json.status) {
        container.innerHTML = "<p style='color:red;'>" + json.messaggio + "</p>";
        return;
    }

    if (json.dati.length === 0) {
        container.innerHTML = "<p>Nessun pezzo di ricambio nel magazzino.</p>";
        return;
    }

    let tabella = `<table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Descrizione</th>
            <th>Costo unitario</th>
            <th>Quantità attuale</th>
            <th>Quantità da modificare</th>
            <th>Operazione</th>
        </tr>`;

    json.dati.forEach(function (pezzo) {
        tabella += `
        <tr id="riga-${pezzo.id_pezzo}">
            <td>${pezzo.descrizione}</td>
            <td>€${parseFloat(pezzo.costo_unitario).toFixed(2)}</td>
            <td id="qty-${pezzo.id_pezzo}">${pezzo.quantita}</td>
            <td><input type="number" id="input-${pezzo.id_pezzo}" min="1" value="1" style="width:60px;"></td>
            <td>
                <button onclick="aggiorna(${pezzo.id_pezzo}, 'aggiungi')">Aggiungi</button>
                <button onclick="aggiorna(${pezzo.id_pezzo}, 'rimuovi')">Rimuovi</button>
            </td>
        </tr>`;
    });

    tabella += "</table>";
    container.innerHTML = tabella;
}

async function aggiorna(idPezzo, operazione) {
    let inputEl = document.getElementById("input-" + idPezzo);
    let quantita = parseInt(inputEl.value);
    let msg = document.getElementById("msgOperazione");

    if (!quantita || quantita <= 0) {
        msg.style.color = "red";
        msg.textContent = "Inserisci una quantità valida (minimo 1)";
        return;
    }

    let dati = new URLSearchParams({ id_pezzo: idPezzo, operazione: operazione, quantita: quantita });
    let risposta = await fetch("../api/dipendenteAggiornaQuantita.php", { method: "POST", body: dati });
    let json = await risposta.json();

    msg.style.color = json.status ? "green" : "red";
    msg.textContent = json.messaggio;

    if (json.status) {
        await caricaMagazzino();
    }
}
