<?php 
    $servername = "mariadb.rpkk.fi";
    $username = "vitalina";
    $password = "vitalina";
    $dbname = "";

    $conn = new mysqli($servername, $username, $password, $dbname);  //muodostetaan yhteys tietokantaan

    /* require_once 'config/db.php';
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);  //muodostetaan yhteys tietokantaan*/

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");  // asetetaan merkistö
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
    <h1></h1>
    <div class="main"></div>
    <script src="code.js"></script>
</body>
</html>
