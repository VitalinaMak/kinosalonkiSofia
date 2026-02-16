<?php 
    $pageTitle = "Tapahtumat";
    include 'include/header.php'; 
?>

    <div class="main">
        <h1>Tapahtumat</h1>
        <!-- line with all of the actions for the list -->
        <div class="eventListActions">
            <button class="btn btn-outline-info">Suodata</button>
            <form action="tapahtumat.php" method="GET"> <!-- search field. When the info is sent, it pass it with URL to the php-function in tapahtumat.php file -->
                <input type="text" name="search" placeholder="Etsi tapahtumaa">
                <button type="submit" class="btn btn-outline-danger">Etsi</button>
            </form>
            <button class="btn btn-outline-info">Järjestää</button>
        </div> 
        <div class="eventList">
            <!-- list of events (no photos yet, I'll add them a little later) -->
            <?php 
                $sql = "SELECT event_name, DATE_FORMAT(DATE(event_date), '%d.%m.%Y') AS event_date, HOUR(event_date) AS event_hour, MINUTE(event_date) AS event_minute, event_image, description, location, event_type, max_visitors FROM events;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if (empty($row["kuva"])) {
                                $kuvaPath = "noImage.png";  //if there's no image uploaded, the default image is used
                            } else {
                            $kuvaPath = $row["kuva"];  //a variable for the image's name
                            }
                        echo "<div><h3 class='event_header'>".$row['event_name']."</h3><h3 class='event_date'>".$row['event_date']." klo ".$row['event_hour'].".".$row['event_minute']."</h3><img src='kuvat/tapahtumaKuvat/".$kuvaPath."' alt='".$row['event_name']."'/><p>".$row['description']."</p></div>";
                    }
                }
            ?>
        </div>
    </div>
    
<?php include 'include/footer.php'; ?>

