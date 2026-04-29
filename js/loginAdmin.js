async function login() {
    let username = document.getElementById("usernameInput").value.trim();
    let password = document.getElementById("passwordInput").value;

    if (!username || !password) { alert("Compila tutti i campi"); return; }

    let dati = new URLSearchParams({ username: username, password: password });
    let risposta = await fetch("../api/loginAdmin.php", { method: "POST", body: dati });
    let json = await risposta.json();

    alert(json.messaggio);
    if (json.status) location.href = "./admin.php";
}
