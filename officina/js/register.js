

document.getElementById("registerButton").addEventListener("click", register);

async function register() {
  const cognome = document.getElementById("cognomeInput").value;
  const nome = document.getElementById("nomeInput").value;
  const telefono = document.getElementById("telefonoInput").value;
  const password = document.getElementById("passwordInput").value;


  if (!cognome || !password || !telefono || !nome) {
    alert("tutti paramaetri");
    return;
  }

  const data = new URLSearchParams({
    cognome: cognome,
    nome: nome,
    telefono: telefono,
    password: password,
  });

  try {
    const response = await fetch("../api/register.php", {
      method: "POST",
      body: data,
    });
    let json = await response.json();


    if (json.status) {
      sessionStorage.setItem("cognome", cognome);
      alert(json.message);
      location.href = "./index.html";
    } else {
      alert(json.message);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error");
  }
}