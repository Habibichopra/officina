        async function caricaOpzioni() {
            try {
                const responseServizi = await fetch('../api/getTuttiServizi.php');
                const servizi = await responseServizi.json();
                const selectServizio = document.getElementById('servizio');
                servizi.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id_servizio;
                    option.textContent = item.descrizione;
                    selectServizio.appendChild(option);
                });

                const responsePezzi = await fetch('../api/getTuttiPezzi.php');
                const pezzi = await responsePezzi.json();
                const selectPezzo = document.getElementById('pezzo');
                pezzi.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id_pezzo;
                    option.textContent = item.descrizione;
                    selectPezzo.appendChild(option);
                });

                const responseAccessori = await fetch('../api/getTuttiAccessori.php');
                const accessori = await responseAccessori.json();
                const selectAccessorio = document.getElementById('accessorio');
                accessori.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id_accessorio;
                    option.textContent = item.descrizione;
                    selectAccessorio.appendChild(option);
                });
            } catch (error) {
                console.error('Errore caricamento opzioni:', error);
            }
        }

        async function cercaOfficine() {
            const idServizio = document.getElementById('servizio').value;
            const idPezzo = document.getElementById('pezzo').value;
            const idAccessorio = document.getElementById('accessorio').value;

            const params = new URLSearchParams();
            if (idServizio) params.append('id_servizio', idServizio);
            if (idPezzo) params.append('id_pezzo', idPezzo);
            if (idAccessorio) params.append('id_accessorio', idAccessorio);

            try {
                const response = await fetch(`../api/getOfficineCompatibili.php?${params}`);
                const officine = await response.json();
                mostraRisultati(officine);
            } catch (error) {
                console.error('Errore ricerca:', error);
                document.getElementById('risultati').innerHTML = '<p>Errore durante la ricerca.</p>';
            }
        }

        function mostraRisultati(officine) {
            const div = document.getElementById('risultati');
            div.innerHTML = '<h2>Risultati:</h2>';
            if (officine.length === 0) {
                div.innerHTML += '<p>Nessuna officina compatibile trovata.</p>';
                return;
            }
            const ul = document.createElement('ul');
            officine.forEach(officina => {
                const li = document.createElement('li');
                li.innerHTML = `<strong>${officina.denominazione}</strong> - ${officina.indirizzo}`;
                ul.appendChild(li);
            });
            div.appendChild(ul);
        }

        window.onload = caricaOpzioni;