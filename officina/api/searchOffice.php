<?php

header("Content-Type: application/json");
require_once __DIR__ . "/../misc/functions.php";
require_once __DIR__ . "/../classes/DatabaseManager.php";

$db = new DatabaseManager();
$conn = $db->getConnection();

$id_servizio = $_GET['servizio'] ?? null;
$id_pezzo = $_GET['pezzo'] ?? null;
$id_accessorio = $_GET['accessorio'] ?? null;

// Validazione
if (!$id_servizio && !$id_pezzo && !$id_accessorio) {
    echo error("Deve essere selezionato almeno un servizio, pezzo o accessorio");
    exit;
}

$officine = [];

try {
    $query = "SELECT DISTINCT o.id_officina, o.denominazione, o.indirizzo FROM Officina o WHERE 1=1";
    $params = [];
    $types = "";

    if ($id_servizio && $id_servizio !== "") {
        $query .= " AND o.id_officina IN (SELECT id_officina FROM Offre WHERE id_servizio = ?)";
        $params[] = $id_servizio;
        $types .= "i";
    }

    if ($id_pezzo && $id_pezzo !== "") {
        $query .= " AND o.id_officina IN (SELECT id_officina FROM Magazzino_Pezzi WHERE id_pezzo = ? AND quantita > 0)";
        $params[] = $id_pezzo;
        $types .= "i";
    }

    if ($id_accessorio && $id_accessorio !== "") {
        $query .= " AND o.id_officina IN (SELECT id_officina FROM Magazzino_Accessori WHERE id_accessorio = ? AND quantita > 0)";
        $params[] = $id_accessorio;
        $types .= "i";
    }

    $stmt = $conn->prepare($query);
    
    if (count($params) > 0) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $officine = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($officine as &$office) {
            $office['servizi'] = [];
            $office['pezzi'] = [];
            $office['accessori'] = [];
            
            if ($id_servizio && $id_servizio !== "") {
                $servizio_query = "SELECT descrizione, costo_orario FROM Servizio WHERE id_servizio = ?";
                $srv_stmt = $conn->prepare($servizio_query);
                $srv_stmt->bind_param("i", $id_servizio);
                $srv_stmt->execute();
                $srv_result = $srv_stmt->get_result();
                if ($srv_result->num_rows > 0) {
                    $office['servizi'] = $srv_result->fetch_all(MYSQLI_ASSOC);
                }
                $srv_stmt->close();
            }
            
            if ($id_pezzo && $id_pezzo !== "") {
                $pezzo_query = "SELECT p.descrizione, p.costo_unitario, mp.quantita FROM Pezzo_Ricambio p 
                               JOIN Magazzino_Pezzi mp ON p.id_pezzo = mp.id_pezzo 
                               WHERE p.id_pezzo = ? AND mp.id_officina = ?";
                $pz_stmt = $conn->prepare($pezzo_query);
                $pz_stmt->bind_param("ii", $id_pezzo, $office['id_officina']);
                $pz_stmt->execute();
                $pz_result = $pz_stmt->get_result();
                if ($pz_result->num_rows > 0) {
                    $office['pezzi'] = $pz_result->fetch_all(MYSQLI_ASSOC);
                }
                $pz_stmt->close();
            }
            
            if ($id_accessorio && $id_accessorio !== "") {
                $acc_query = "SELECT a.descrizione, a.costo_unitario, ma.quantita FROM Accessorio a 
                             JOIN Magazzino_Accessori ma ON a.id_accessorio = ma.id_accessorio 
                             WHERE a.id_accessorio = ? AND ma.id_officina = ?";
                $acc_stmt = $conn->prepare($acc_query);
                $acc_stmt->bind_param("ii", $id_accessorio, $office['id_officina']);
                $acc_stmt->execute();
                $acc_result = $acc_stmt->get_result();
                if ($acc_result->num_rows > 0) {
                    $office['accessori'] = $acc_result->fetch_all(MYSQLI_ASSOC);
                }
                $acc_stmt->close();
            }
        }
        
        echo json_encode([
            "status" => true,
            "message" => "Officine trovate: " . count($officine),
            "data" => $officine
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Nessuna officina trovata con i criteri selezionati",
            "data" => []
        ]);
    }
    $stmt->close();
    
} catch (Exception $e) {
    echo error("Errore nella ricerca: " . $e->getMessage());
}

$conn->close();
?>