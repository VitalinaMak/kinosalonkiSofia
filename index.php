<?php 
    $servername = "mariadb.rpkk.fi";
    $username = "sofia";
    $password = "KinoS2026#?";
    $dbname = "sofia";

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
    <!-- link to bootstrap for using CSS features (it is required for buttons design) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Aloitussivu</title>
</head>
<body>
    <header> 
        <div class="otsikko">
            <img src="kuvat/logot/logo_white.png" alt="logo" style="height: 30px">
            <h1>Kinosalonki Sofia</h1>
        </div>
        <a href="login.php" class="btn btn-outline-primary">Kirjaudu sisään</a>
    </header>
    <h1></h1>

    <main> 
    <div class="about">
        <!-- Pictures and "history" here -->
        
        <p> Replace this paragraph with img property </p>
        <!-- <img src="url" alt="alternatetext"> -->
        
        <p> Kinosalonki Sofia established 2019, history bla bla bla Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip 
        </p>    
    </div>
        
    <div class="upcomingEvents">
        
        <p> Tapahtumat tänään, 11 helmikuuta 2026:</p>
        <table>
            <tbody>
              <tr>
                <td>10.10</td>
                <td>Elokuva1</td>
                <td>5 paikkaa jäljellä</td>
              </tr>
              <tr>
                <td>12.00</td>
                <td>Elokuva2</td>
                <td>loppuunmyyty</td>
              </tr>
              <tr>
                <td>18.00</td>
                <td>Tapahtuma</td>
                <td>loppuunmyyty</td>
              </tr>
            </tbody>
        </table>

        <!-- Это ведь и будет ссылка на страницу всех tapahtumat?
         цвета не окончательные, пока что я просто взяла предустановленные, потом по окружению будет понятно, какие лучше взять -->
        <a href="tapahtumat.php" class="btn btn-outline-danger">интерактивная(?) кнопка</a>  
        
        <!-- твой код с css я пока скопировала в css-файл, но не до ссылок на него пока не сделано -->
    
    </div>    

    </main>
    
    <!-- link to bootstrap for using some extratools -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="code.js"></script>
</body>
</html>
