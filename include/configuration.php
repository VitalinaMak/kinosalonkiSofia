<?php 
    $servername = "mariadb.rpkk.fi";
    $username = "sofia";
    $password = "KinoS2026#?";
    $dbname = "sofia";

    $conn = new mysqli($servername, $username, $password, $dbname);  //muodostetaan yhteys tietokantaan

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");  // asetetaan merkistö
?> 