window.addEventListener("load", caricaOfficine);

async function caricaOfficine() {
    let risposta = await fetch("../api/getOfficine.php");
    let json = await risposta.json();

    let select = document.getElementById("selectOfficina");

    json.dati.forEach(function(officina) {
        let opzione = document.createElement("option");
        opzione.value = officina.id_officina;
        opzione.textContent = officina.denominazione;
        select.appendChild(opzione);
    });

    select.addEventListener("change", function() {
        let id = this.value;
        let infoDiv = document.getElementById("infoOfficina");

        if (!id) { infoDiv.style.display = "none"; return; }

        let officina = json.dati.find(o => o.id_officina == id);
        document.getElementById("denominazione").textContent = officina.denominazione;
        document.getElementById("indirizzo").textContent = officina.indirizzo;
        document.getElementById("linkCatalogo").href = "./catalogo.php?id=" + id;
        infoDiv.style.display = "block";
        sessionStorage.setItem("id_officina", id);
    });
}
