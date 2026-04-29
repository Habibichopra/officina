async function registra() {
    let mail     = document.getElementById("mailInput").value.trim();
    let password = document.getElementById("passwordInput").value;

    if (!mail || !password) { alert("Compila tutti i campi"); return; }

    try {
        let dati = new URLSearchParams({ mail: mail, password: password });
        let risposta = await fetch("../api/registerCliente.php", { method: "POST", body: dati });
        if (!risposta.ok) {
            alert("Errore HTTP: " + risposta.status);
            return;
        }
        let json = await risposta.json();
        alert(json.messaggio);
        if (json.status) location.href = "./index.php";
    } catch (error) {
        alert("Errore di rete: " + error.message);
    }
}
