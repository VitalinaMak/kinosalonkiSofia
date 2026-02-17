<?php 
    $pageTitle = "Tapahtumat";
    include 'include/header.php'; 
?>

    <main class="tapahtumat.php">
        <h1>Tapahtumat</h1>
        <!-- line with all of the actions for the list -->
        <div class="eventListActions">
            <!-- button for filtering events. Contains a dropdown menu with sorting options -->
            <div class="eventFiltering">
                <button class="btn btn-outline-info">Suodata</button>
                <div class="filteringOptions">
                    
                </div>
            </div>
            <!-- search field. When the info is sent, it pass it in URL -->
            <form action="" method="GET"> 
                <input type="text" value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>" name="search" placeholder="Etsi tapahtumaa">
                <button type="submit" class="btn btn-outline-danger">Etsi</button>
            </form>
            <!-- button for sorting events. Contains a dropdown menu with sorting options -->
            <div class="eventSorting">  
                <button class="btn btn-outline-info">Järjestää</button>
                <div class="sortingOptions">
                    <a href="?sort=nimiaz">Tapahtuman nimi (A-Z)</a>
                    <a href="?sort=nimiza">Tapahtuman nimi (Z-A)</a>
                    <a href="?sort=pvmnouseva">Päivämäärä (nouseva)</a>  <!-- this one is used by default -->
                    <a href="?sort=pvmlaskeva">Päivämäärä (laskeva)</a>
                </div>
            </div>
        </div> 

        <div class="eventList">
            <?php
                $order = 'event_date, ASC';  //variable for sorting data. For default sorts by date starting from earlier events 
                if (isset($_GET['sort']) && $_GET['sort'] == 'nimiaz') {
                    $order = "event_name ASC";  //sorts by name in alphabetical order
                } else if (isset($_GET['sort']) && $_GET['sort'] == 'nimiza') {
                    $order = "event_name DESC";  //sorts by name in reversed order
                } else if (isset($_GET['sort']) && $_GET['sort'] == 'pvmlaskeva') {
                    $order = "event_date DESC";  //sorts by date starting from later events
                } else {  
                    $order = "event_date ASC";  //default sorting by date
                }
            
                /* if the input in search field is empty, reload the page (it is made to avoid redudant info in URL) */
                if (isset($_GET['search']) && trim($_GET['search']) === '') {
                    header("Location: tapahtumat.php");
                    exit;
                }

                if (isset($_GET['search']) && trim($_GET['search']) != "") {  //if the searchfield is set some value, query will search it
                    $search = $_GET['search'];
                    /* query to retrive all information from events and count rows for every event in bookings to get the amount of booked tickets + search function that retrieves all rows that contain the content of the $search */
                    $sql = "SELECT events.event_name, events.event_date, DATE_FORMAT(events.event_date, '%d.%m.%Y') AS event_formatted_date, HOUR(events.event_time) AS event_hour, DATE_FORMAT(events.event_time, '%i') AS event_minute, events.event_image, events.description, events.location, events.event_type, events.max_visitors, COUNT(bookings.id) AS booked_places FROM events LEFT JOIN bookings ON events.id = bookings.event_id WHERE events.event_name LIKE '%$search%' GROUP BY events.id ORDER BY events.$order, events.event_time;";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if (empty($row["kuva"])) {
                                $kuvaPath = "noImage.png";  //if there's no image uploaded, the default image is used
                            } else {
                            $kuvaPath = $row["kuva"];  //a variable for the image's name
                            }
                            /* count the amount of tickets left */
                            $placesLeft = $row['max_visitors'] - $row['booked_places'];
                            echo "<div><h3 class='event_header'>".$row['event_name']."</h3><h3 class='event_date'>".$row['event_formatted_date']." klo ".$row['event_hour'].".".$row['event_minute']."</h3><img src='kuvat/tapahtumaKuvat/".$kuvaPath."' alt='".$row['event_name']."'/><p>".$row['description']."<br>Paikkoja jäljellä: ".$placesLeft.".</p></div>";
                        }
                    }
                } else {  //if no searching is used, retrieve all data from the table
                    $sql = "SELECT events.event_name, events.event_date, DATE_FORMAT(events.event_date, '%d.%m.%Y') AS event_formatted_date, HOUR(events.event_time) AS event_hour, DATE_FORMAT(events.event_time, '%i') AS event_minute, events.event_image, events.description, events.location, events.event_type, events.max_visitors, COUNT(bookings.id) AS booked_places FROM events LEFT JOIN bookings ON events.id = bookings.event_id GROUP BY events.id ORDER BY $order, event_time;";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if (empty($row["kuva"])) {
                                $kuvaPath = "noImage.png";  //if there's no image uploaded, the default image is used
                            } else {
                                $kuvaPath = $row["kuva"];  //a variable for the image's name
                            }
                            /* count the amount of tickets left */
                            $placesLeft = $row['max_visitors'] - $row['booked_places'];
                            echo "<div><h3 class='event_header'>".$row['event_name']."</h3><h3 class='event_date'>".$row['event_formatted_date']." klo ".$row['event_hour'].".".$row['event_minute']."</h3><img src='kuvat/tapahtumaKuvat/".$kuvaPath."' alt='".$row['event_name']."'/><p>".$row['description']."<br>Paikkoja jäljellä: ".$placesLeft.".</p></div>";
                        }
                    }
                }
                
            ?>
        </div>
    </div>
    
<?php include 'include/footer.php'; ?>

