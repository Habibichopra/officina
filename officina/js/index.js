async function loadProducts() {
  try {
    const response = await fetch("../api/getProducts.php");
    const json = await response.json();

    if (json.status) {
      const container = document.getElementById("container");
      container.innerHTML = "";

      // Determina su quale pagina siamo in base al percorso URL
      const path = window.location.pathname;
      let products = [];
      let title = "";
      
      if (path.includes("index.php") || path.includes("index.html")) {
        products = json.data.servizi;
        title = "Listino Servizi";
      } else if (path.includes("pezziRicambio.php")) {
        products = json.data.pezziRicambio;
        title = "Listino Pezzi di Ricambio";
      } else if (path.includes("accessori.php")) {
        products = json.data.accessori;
        title = "Listino Accessori";
      }

      // Aggiungi titolo
      const titleElement = document.createElement("h2");
      titleElement.textContent = title;
      container.appendChild(titleElement);

      if (products.length === 0) {
        const noProducts = document.createElement("p");
        noProducts.textContent = "Nessun prodotto disponibile";
        container.appendChild(noProducts);
      } else {
        products.forEach((product) => {
          const card = document.createElement("div");
          card.style.border = "1px solid #ccc";
          card.style.margin = "10px";
          card.style.padding = "10px";
          
          let idField = product.id_servizio || product.id_pezzo || product.id_accessorio;
          let priceField = product.costo_orario || product.costo_unitario;
          let priceLabel = product.costo_orario ? "Costo orario" : "Costo unitario";
          
          card.innerHTML = `
            <strong>ID: ${idField}</strong><br>
            ${product.descrizione}<br>
            ${priceLabel}: €${parseFloat(priceField).toFixed(2)}
            <hr>
          `;
          container.appendChild(card);
        });
      }
    } else {
      const container = document.getElementById("container");
      container.innerHTML = `<p style="color: red;">Errore: ${json.message}</p>`;
    }
  } catch (error) {
    console.error("Error loading products:", error);
    const container = document.getElementById("container");
    container.innerHTML = `<p style="color: red;">Errore nel caricamento dei prodotti</p>`;
  }
}

window.addEventListener("load", () => loadProducts());