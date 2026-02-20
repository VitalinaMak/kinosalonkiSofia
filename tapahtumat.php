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
                    <a href="?sort=nimiaz&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">Tapahtuman nimi (A-Z)</a>
                    <a href="?sort=nimiza&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">Tapahtuman nimi (Z-A)</a>
                    <a href="?sort=pvmnouseva&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">Päivämäärä (nouseva)</a>  <!-- this one is used by default -->
                    <a href="?sort=pvmlaskeva&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">Päivämäärä (laskeva)</a>
                </div>
            </div>
        </div> 

        <div class="eventList">
            <?php
                $search = '';  //variable for searching
                $order = 'events.event_date ASC';  //variable for sorting data. For default sorts by date starting from earlier events 
                
                /* sorting */
                if (isset($_GET['sort'])) {
                    switch ($_GET['sort']) {
                        case 'nimiaz':
                            $order = "events.event_name ASC";
                            break;
                        case 'nimiza':
                            $order = "events.event_name DESC";
                            break;
                        case 'pvmlaskeva':
                            $order = "events.event_date DESC";
                            break;
                        default:
                            $order = 'events.event_date ASC';
                    }
                }
            
                /* searching */
                if (isset($_GET['search']) && trim($_GET['search']) !== '') {
                    $search = "%" . trim($_GET['search']) . "%";
                }

                if (isset($_GET['search']) && trim($_GET['search']) === '') {  //in case user sends a blank field in input
                if (isset($_GET['sort'])) {
                    header("Location: tapahtumat.php?sort=" . urlencode($_GET['sort']));  //if sorting is used, save it and reload the page
                } else {
                    header("Location: tapahtumat.php");  //if no sorting is used, just reload the page
                }
                exit;
            }

                /* base sql-query */
                $sql = "
                    SELECT 
                        events.event_name, 
                        events.event_date, 
                        DATE_FORMAT(events.event_date, '%d.%m.%Y') AS event_formatted_date, 
                        HOUR(events.event_time) AS event_hour, 
                        DATE_FORMAT(events.event_time, '%i') AS event_minute, 
                        events.event_image, 
                        events.description, 
                        events.location, 
                        events.event_type, 
                        events.max_visitors, 
                        COUNT(bookings.id) AS booked_places
                    FROM events 
                    LEFT JOIN bookings ON events.id = bookings.event_id
                ";
                                        
                $params = [];
                $types = "";
                
                /* WHERE is added only if searching */
                if ($search !== '') {
                    $sql .= " WHERE events.event_name LIKE ?";
                    $params[] = $search;
                    $types .= "s";
                }
                
                $sql .= " GROUP BY events.id ORDER BY $order, events.event_time";

                /* prepared statement */
                $stmt = $conn->prepare($sql);

                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                /* output */
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $kuvaPath = empty($row["event_image"]) ? "noImage.png" : $row["event_image"];  //if the event doesn't have image, use the default image
                        $placesLeft = $row['max_visitors'] - $row['booked_places'];  //amount of seats left, calculated from the max. amount of seats (stated in the table) and amount of bookings made for the event 

                        echo "<div>
                                <h3 class='event_header'>{$row['event_name']}</h3>
                                <h3 class='event_date'>{$row['event_formatted_date']} klo {$row['event_hour']}.{$row['event_minute']}</h3>
                                <img src='kuvat/tapahtumaKuvat/$kuvaPath' alt='{$row['event_name']}'/>
                                <p>{$row['description']}<br>Paikkoja jäljellä: {$placesLeft}.</p>
                            </div>";
                    }
                }

            ?>
        </div>
    </div>
    
<?php include 'include/footer.php'; ?>

