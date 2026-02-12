<?php 
    $servername = "mariadb.rpkk.fi";
    $username = "sofia";
    $password = "KinoS2026#?";
    $dbname = "sofia";

    $conn = new mysqli($servername, $username, $password, $dbname);  //muodostetaan yhteys tietokantaan

    /*require_once 'config/db.php';
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
    <!-- link to bootstrap for using CSS features -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Tapahtumat</title>
</head>
<body>
    <header> 
        <div class="otsikko">
            <img src="kuvat/logot/logo_white.png" alt="logo" style="height: 30px">
            <h1>Kinosalonki Sofia</h1>
        </div>
        <a href="login.php" class="btn btn-outline-primary">Kirjaudu sisään</a>
    </header>
    <div class="main">
        <h1>Tapahtumat</h1>
        <!-- line with all of the actions for the list -->
        <div class="eventListActions">
            <button class="btn btn-outline-info">Suodata</button>
            <form action="tapahtumat.php" method="GET"> <!-- search field. When the info is sent, it pass it with URL to the php-function in tapahtumat.php file -->
                <input type="text" name="search" placeholder="Etsi tapahtumaa">
                <button type="submit">Etsi</button>
            </form>
            <button class="btn btn-outline-info">Järjestää</button>
        </div> 
        <div class="eventList">
            <!-- list of events (no photos yet, I'll add them a little later) -->
            <?php 
                $sql = "SELECT * FROM events;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div><h3>".$row['event_name']."</h3><h3>".$row['event_date']."</h3><p>".$row['description']."</p>";
                    }
                }
            ?>
        </div>
    </div>
    <!-- link to bootstrap for using some extratools -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
