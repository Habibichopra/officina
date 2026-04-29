async function richiediReset() {
    let mail = document.getElementById("mailInput").value.trim();

    if (!mail) { alert("Inserisci la tua email"); return; }

    try {
        let dati = new URLSearchParams({ mail: mail });
        let risposta = await fetch("../api/requestReset.php", { method: "POST", body: dati });
        let json = await risposta.json();
        alert(json.messaggio);
    } catch (error) {
        alert("Errore di rete: " + error.message);
    }
}

async function resetPassword() {
    let newPassword  = document.getElementById("newPasswordInput").value;
    let conferma     = document.getElementById("confermaPasswordInput").value;

    if (!newPassword || !conferma) { alert("Compila tutti i campi"); return; }
    if (newPassword !== conferma)  { alert("Le password non coincidono"); return; }

    let otp = new URLSearchParams(window.location.search).get("otp");
    if (!otp) { alert("OTP mancante"); return; }

    try {
        let dati = new URLSearchParams({ otp: otp, newPassword: newPassword });
        let risposta = await fetch("../api/resetPassword.php", { method: "POST", body: dati });
        let json = await risposta.json();
        alert(json.messaggio);
        if (json.status) location.href = "./login_cliente.html";
    } catch (error) {
        alert("Errore di rete: " + error.message);
    }
}

window.addEventListener("DOMContentLoaded", function () {
    let otp = new URLSearchParams(window.location.search).get("otp");
    if (otp) {
        document.getElementById("formRichiesta").style.display = "none";
        document.getElementById("formReset").style.display     = "block";
    } else {
        document.getElementById("formRichiesta").style.display = "block";
        document.getElementById("formReset").style.display     = "none";
    }
});
