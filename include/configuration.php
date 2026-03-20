<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();  //start the session. Don't write anithing above it, it has to be the first line in php-code
    }

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