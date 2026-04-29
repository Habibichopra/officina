// Carica i dati al caricamento della pagina
window.addEventListener("load", () => {
  loadProductsData();
});

// Carica servizi, pezzi e accessori da popolare nei select
async function loadProductsData() {
  try {
    const response = await fetch("../api/getProducts.php");
    const json = await response.json();

    if (json.status) {
      // Popola il select dei servizi
      const serviziSelect = document.getElementById("servizi");
      json.data.servizi.forEach((servizio) => {
        const option = document.createElement("option");
        option.value = servizio.id_servizio;
        option.textContent = `${servizio.descrizione} - €${parseFloat(servizio.costo_orario).toFixed(2)}/h`;
        serviziSelect.appendChild(option);
      });

      // Popola il select dei pezzi ricambio
      const pezziSelect = document.getElementById("pezzi");
      json.data.pezziRicambio.forEach((pezzo) => {
        const option = document.createElement("option");
        option.value = pezzo.id_pezzo;
        option.textContent = `${pezzo.descrizione} - €${parseFloat(pezzo.costo_unitario).toFixed(2)}`;
        pezziSelect.appendChild(option);
      });

      // Popola il select degli accessori
      const accessoriSelect = document.getElementById("accessori");
      json.data.accessori.forEach((accessorio) => {
        const option = document.createElement("option");
        option.value = accessorio.id_accessorio;
        option.textContent = `${accessorio.descrizione} - €${parseFloat(accessorio.costo_unitario).toFixed(2)}`;
        accessoriSelect.appendChild(option);
      });
    }
  } catch (error) {
    console.error("Errore nel caricamento dei dati:", error);
    showMessage("Errore nel caricamento dei dati", false);
  }
}

// Funzione di ricerca
async function searchOffices() {
  const servizio = document.getElementById("servizi").value;
  const pezzo = document.getElementById("pezzi").value;
  const accessorio = document.getElementById("accessori").value;

  // Validazione
  if (!servizio && !pezzo && !accessorio) {
    showMessage("Seleziona almeno un servizio, pezzo o accessorio", false);
    return;
  }

  // Mostra il caricamento
  const resultsDiv = document.getElementById("results");
  resultsDiv.innerHTML = '<div class="loading"><div class="spinner"></div><p>Ricerca in corso...</p></div>';

  try {
    // Costruisci l'URL con i parametri
    let url = "../api/searchOffice.php?";
    if (servizio) url += "servizio=" + servizio + "&";
    if (pezzo) url += "pezzo=" + pezzo + "&";
    if (accessorio) url += "accessorio=" + accessorio;

    const response = await fetch(url);
    const json = await response.json();

    resultsDiv.innerHTML = ""; // Pulisci i risultati precedenti

    if (json.status && json.data.length > 0) {
      const title = document.createElement("div");
      title.className = "results-title";
      title.textContent = `${json.message}`;
      resultsDiv.appendChild(title);

      json.data.forEach((office) => {
        const card = document.createElement("div");
        card.className = "office-card";

        let html = `
          <div class="office-header">
            <div class="office-name">${office.denominazione}</div>
            <div class="office-address">${office.indirizzo}</div>
          </div>
          <div class="office-details">
        `;

        // Aggiungi sezione servizi se disponibili
        if (office.servizi && office.servizi.length > 0) {
          html += '<div class="detail-section">';
          html += '<div class="detail-title">Servizi Disponibili</div>';
          office.servizi.forEach((s) => {
            html += `<div class="detail-item">${s.descrizione} - €${parseFloat(s.costo_orario).toFixed(2)}/h</div>`;
          });
          html += '</div>';
        }

        // Aggiungi sezione pezzi se disponibili
        if (office.pezzi && office.pezzi.length > 0) {
          html += '<div class="detail-section">';
          html += '<div class="detail-title">Pezzi di Ricambio Disponibili</div>';
          office.pezzi.forEach((p) => {
            html += `<div class="detail-item">${p.descrizione}<br/>Quantità: ${p.quantita} - €${parseFloat(p.costo_unitario).toFixed(2)}</div>`;
          });
          html += '</div>';
        }

        // Aggiungi sezione accessori se disponibili
        if (office.accessori && office.accessori.length > 0) {
          html += '<div class="detail-section">';
          html += '<div class="detail-title">Accessori Disponibili</div>';
          office.accessori.forEach((a) => {
            html += `<div class="detail-item">${a.descrizione}<br/>Quantità: ${a.quantita} - €${parseFloat(a.costo_unitario).toFixed(2)}</div>`;
          });
          html += '</div>';
        }

        html += '</div>';
        card.innerHTML = html;
        resultsDiv.appendChild(card);
      });

      showMessage(`Ricerca completata: ${json.data.length} officina/e trovata/e`, true);
    } else {
      resultsDiv.innerHTML = `
        <div class="no-results">
          <p>${json.message}</p>
        </div>
      `;
      showMessage(json.message, false);
    }
  } catch (error) {
    console.error("Errore nella ricerca:", error);
    showMessage("Errore nella ricerca", false);
    resultsDiv.innerHTML = '<div class="error-message">Errore durante la ricerca. Riprova più tardi.</div>';
  }
}

// Funzione per ripristinare il form
function resetForm() {
  document.getElementById("servizi").value = "";
  document.getElementById("pezzi").value = "";
  document.getElementById("accessori").value = "";
  document.getElementById("results").innerHTML = "";
}

// Funzione per mostrare messaggi
function showMessage(message, isSuccess) {
  console.log(isSuccess ? "✓ " + message : "✗ " + message);
}
