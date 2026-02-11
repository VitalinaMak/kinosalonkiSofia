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
    <link rel="stylesheet" href="styles.css">
    <title>Aloitussivu</title>
</head>
<body>
    <header> 
        <h1>Kinosalonki Sofia</h1>
        <a href="login.php">Kirjaudu sisään</a>
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

        <button > блин кароч тут надо чтоб кнопка была интерактивная надо делать, смотри коммент в коде </button>  
        
        <!-- 
        href ссылочка на страницу всех tapahtumat, 
        <a href="https://google.com" class="button">Kaikki tapahtumat</a>
        
        и в css эту ссылочку задизайтерить как кнопочку, ИЛИ скопировать КАК НА ТОМ САЙТЕ КНОПОЧЕК ЕСТЬ ИМБАА
        
        a.button {
            padding: 1px 6px;
            border: 1px outset buttonborder;
            border-radius: 3px;
            color: buttontext;
            background-color: buttonface;
            text-decoration: none;
        }
        --> 
    
    </div>    

    </main>
    
    <script src="code.js"></script>
</body>
</html>
