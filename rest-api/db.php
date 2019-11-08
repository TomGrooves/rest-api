<?php

class DB
{
// En function til at starte connectionen
// Bruges med variablerne: $user, $password, $dbname og $hostname
    public function Connect($user, $password, $dbname,$hostname)
    {

     $conn = null;

        try {
            // sæt variablen $dsn til at være hvad vi modtager i funktionen
            $dsn = "mysql:dbname=".$dbname."; host=".$hostname.";";

            // sæt "options" for PDO
            $options  = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false];

            // Opret ny PDO som denne $conn
            $this->$conn = new PDO($dsn, $user, $password, $options);
            
            // returner denne $conn til hvad end der kaldte funktionen.
            return $this->$conn;

    // Tjek om der er errors
        } catch (PDOException $errorMsg) {
            echo 'Connection error: ' . $errorMsg->getMessage();
        }
    }

    public function Close()
    {
        $this->conn = null;
    }
}

?>
