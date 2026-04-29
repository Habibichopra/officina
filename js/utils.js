async function mostraBarraUtente() {
    let risposta = await fetch("../api/getSessione.php");
    let json = await risposta.json();

    let div = document.getElementById("barraUtente");
    if (!div) return;

    if (json.loggato) {
        div.innerHTML = "Sei loggato come <strong>" + json.nome + "</strong> (" + json.tipo + ") — <a href='../api/logout.php'>Logout</a>";
    } else {
        div.innerHTML = "Non sei loggato — <a href='./login_cliente.html'>Login cliente</a> | <a href='./login_dipendente.html'>Login dipendente</a>";
    }
}

mostraBarraUtente();
