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
    
<?php include 'include/footer.php'; ?>

