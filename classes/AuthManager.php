<?php
require_once __DIR__ . "/DatabaseManager.php";

class AuthManager {


    public static function loginCliente($mail, $password) {
        $conn = (new DatabaseManager())->getConn();
        $stmt = $conn->prepare("SELECT id_cliente FROM Cliente WHERE mail=? AND password=? AND IsAbilitato=TRUE");
        $stmt->bind_param("ss", $mail, $password);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc(); 
    }

    public static function registraCliente($mail, $password) {
        $conn = (new DatabaseManager())->getConn();
        
        $check = $conn->prepare("SELECT id_cliente FROM Cliente WHERE mail=?");
        $check->bind_param("s", $mail);
        $check->execute();
        if ($check->get_result()->num_rows > 0) throw new Exception("EMAIL_ESISTENTE");

        $otp = self::generateUUID();
        $scadenza = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $stmt = $conn->prepare("INSERT INTO Cliente (mail, password, OTP, dataScadenzaOTP, IsAbilitato) VALUES (?,?,?,?,FALSE)");
        $stmt->bind_param("ssss", $mail, $password, $otp, $scadenza);
        if (!$stmt->execute()) throw new Exception("ERRORE_DB");

        try {
            self::inviaMailCOnferma($mail, $otp);
        } catch (Exception $e) {
            $del = $conn->prepare("DELETE FROM Cliente WHERE mail=? AND IsAbilitato=FALSE");
            $del->bind_param("s", $mail);
            $del->execute();
            throw new Exception("ERRORE_MAIL: " . $e->getMessage());
        }
    }


    public static function loginDipendente($username, $password) {
        $conn = (new DatabaseManager())->getConn();
        $stmt = $conn->prepare("SELECT id_dipendente, id_officina FROM Dipendente WHERE username=? AND password=?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function registraDipendente($username, $password) {
        $conn = (new DatabaseManager())->getConn();
        $check = $conn->prepare("SELECT id_dipendente FROM Dipendente WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) return false;

        $stmt = $conn->prepare("INSERT INTO Dipendente (username, password) VALUES (?,?)");
        $stmt->bind_param("ss", $username, $password);
        return $stmt->execute();
    }

    public static function loginAdmin($username, $password) {
        $conn = (new DatabaseManager())->getConn();
        $stmt = $conn->prepare("SELECT id_admin FROM Admin WHERE username=? AND password=?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    private static function generateUUID() {
        $data = random_bytes(16);

        // Set versione a 0100 (UUID v4)
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        // Set variante a 10
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private static function inviaMailCOnferma($to, $otp) {
        self::sendMailViaCurl(
            $to,
            'Conferma registrazione',
            "Clicca il link per confermare la registrazione: <a href='https://sigmaguide.netsons.org/api/confirmRegistration.php?otp=$otp'>Conferma</a>"
        );
    }

    public static function confermaRegistrazione($otp) {
        $conn = (new DatabaseManager())->getConn();
        $stmt = $conn->prepare("SELECT id_cliente FROM Cliente WHERE OTP=? AND dataScadenzaOTP > NOW() AND IsAbilitato=FALSE");
        $stmt->bind_param("s", $otp);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $id = $result->fetch_assoc()['id_cliente'];
            $update = $conn->prepare("UPDATE Cliente SET IsAbilitato=TRUE, OTP=NULL, dataScadenzaOTP=NULL WHERE id_cliente=?");
            $update->bind_param("i", $id);
            return $update->execute();
        }
        return false;
    }

    public static function requestPasswordReset($mail) {
        $conn = (new DatabaseManager())->getConn();
        $stmt = $conn->prepare("SELECT id_cliente FROM Cliente WHERE mail=? AND IsAbilitato=TRUE");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        if ($stmt->get_result()->num_rows == 0) return false;

        $otp = self::generateUUID();
        $scadenza = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $update = $conn->prepare("UPDATE Cliente SET OTP=?, dataScadenzaOTP=? WHERE mail=?");
        $update->bind_param("sss", $otp, $scadenza, $mail);
        if (!$update->execute()) return false;

        try {
            self::inviaResetEmail($mail, $otp);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private static function inviaResetEmail($to, $otp) {
        self::sendMailViaCurl(
            $to,
            'Reset password',
            "Clicca il link per resettare la password: <a href='https://sigmaguide.netsons.org/pages/reset_password.html?otp=$otp'>Reset</a>"
        );
    }

    private static function sendMailViaCurl($to, $oggetto, $body) {
        $url = "https://agora.ismonnet.it/sendMail/send.php";

        $data = [
            "mail_invio"        => "esercizio-5ainf@ismonnet.eu",
            "mail_destinazione" => $to,
            "oggetto"           => $oggetto,
            "body"              => $body
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception("Errore cURL: $err");
        }

        curl_close($ch);
    }

    public static function resetPassword($otp, $newPassword) {
        $conn = (new DatabaseManager())->getConn();
        $stmt = $conn->prepare("SELECT id_cliente FROM Cliente WHERE OTP=? AND dataScadenzaOTP > NOW() AND IsAbilitato=TRUE");
        $stmt->bind_param("s", $otp);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $id = $result->fetch_assoc()['id_cliente'];
            $update = $conn->prepare("UPDATE Cliente SET password=?, OTP=NULL, dataScadenzaOTP=NULL WHERE id_cliente=?");
            $update->bind_param("si", $newPassword, $id);
            return $update->execute();
        }
        return false;
    }
}
