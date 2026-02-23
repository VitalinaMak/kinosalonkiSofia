<?php 
    $pageTitle = "Tapahtumat";
    $extraCSS = "CSS/tapahtumat.css";
    include 'include/header.php'; 
?>

<!-- handling empty search field here, because it throws an error if there's any output before header() -->
<?php
    if (isset($_GET['search']) && trim($_GET['search']) === '') {
        $params = [];  //array for key-value pairs, that will store all of the parameters (such as sort or filtering options)
        if (isset($_GET['sort'])) {
            $params['sort'] = $_GET['sort'];  //if sorting is used, save it's value to the array
        }
        if (isset($_GET['type'])) {
            $params['type'] = $_GET['type'];  //if filtering by event type is used, save it's value to the array
        }
        $query = http_build_query($params);  //http-query with all used parameters
        header("Location: tapahtumat.php" . ($query ? "?$query" : ""));  //if query is not empty, add it to the URL and reload the page
    }
?>

<main class="tapahtumat.php">
    <h1>Tapahtumat</h1>
    <!-- line with all of the actions for the list -->
    <div class="eventListActions">
        <div class="searchAndSort">
            <!-- search field. When the info is sent, it passes it in URL -->
            <form action="" method="GET"> 
                <input type="text" value="<?php if(isset($_GET['search'])){htmlspecialchars($_GET['search']);} ?>" name="search" placeholder="Etsi tapahtumaa">
                <?php foreach ($_GET as $key => $value): ?>  <!-- iterates through all parameters in URL if there's something else than search -->
                    <?php if ($key !== 'search'): ?>
                        <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">  <!-- resends the parameters to the URL -->
                    <?php endif; ?>
                <?php endforeach; ?>
                <!-- <button type="submit" class="btn btn-outline-danger">Etsi</button> -->  <!-- seems like we don't realy need submit-button here, but don't delete it for now -->
            </form>

            <!-- button for sorting events. Contains a dropdown menu with sorting options -->
            <div class="eventSorting">  
                <button class="btn btn-outline-info">Järjestää</button>
                <div class="sortingOptions">
                    <a href="?sort=nimiaz&search=<?php echo urlencode($_GET['search'] ?? ''); if (isset($_GET['type'])) {echo '&type='.urlencode($_GET['type'] ?? '');};?>">Tapahtuman nimi (A-Z)</a>
                    <a href="?sort=nimiza&search=<?php echo urlencode($_GET['search'] ?? ''); if (isset($_GET['type'])) {echo '&type='.urlencode($_GET['type'] ?? '');}; ?>">Tapahtuman nimi (Z-A)</a>
                    <a href="?sort=pvmnouseva&search=<?php echo urlencode($_GET['search'] ?? ''); if (isset($_GET['type'])) {echo '&type='.urlencode($_GET['type'] ?? '');}; ?>">Päivämäärä (nouseva)</a>  <!-- this one is used by default -->
                    <a href="?sort=pvmlaskeva&search=<?php echo urlencode($_GET['search'] ?? ''); if (isset($_GET['type'])) {echo '&type='.urlencode($_GET['type'] ?? '');}; ?>">Päivämäärä (laskeva)</a>
                </div>
            </div>
        </div>

        <!-- handling search and sort parameters (if they already exist) in the url for filtering -->
        <?php 
            $params = [];  //array for sort options and search
            if (isset($_GET['search'])) {
                $params['search'] = $_GET['search'];  //if search is used, save it's value to the array
            }
            if (isset($_GET['sort'])) {
                $params['sort'] = $_GET['sort'];  //if sorting is used, save it's value to the array
            }
            $query = http_build_query($params);  //http_build_query automatically builds a query string for URL (sth like 'sort=nimiaz&type=2') 
            if ($query) {
                $query = $query . "&";  //adds '&' to the end, so it'll only be added to URL if extra params are used
            }
        ?>

        <!-- button for filtering events. Contains a dropdown menu with filtering options -->
        <div class="eventFiltering">
            <button class="btn btn-outline-info">Suodata</button>
            <div class="filteringOptions">
                <p>Event type:</p>
                <a href="<?php echo ($query ? "?$query" : "?")?>type=1">Elokuvaesitys</a>
                <a href="<?php echo ($query ? "?$query" : "?")?>type=2">Tapahtumat rajatulla osallistujamäärällä</a>
                <a href="<?php echo ($query ? "?$query" : "?")?>type=3">Tapahtumat, joissa ei ole osallistujamäärän rajoitusta</a> 
                <a href="<?php echo ($query ? "?".substr($query, 0, -1) : "")?>">Poista suodatin</a>
            </div>
        </div>
    </div> 

    <!-- div for the list of events -->
    <div class="eventList">
        <?php
            $search = '';  //variable for searching
            $order = 'events.event_date ASC';  //variable for sorting data. For default sorts by date starting from earlier events 
            $eventType = '';  //variable for filtering by event type

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

            

            /* filtering by event type */
            if (isset($_GET['type'])) {
                switch ($_GET['type']) {
                    case '2':
                        $eventType = "events.event_type = 2";
                        break;
                    case '3':
                        $eventType = "events.event_type = 3";
                        break;
                    default:
                        $eventType = "events.event_type = 1";  //let movie be the default type
                }
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

                /* if filtering by event type, extra options are added to WHERE */
                if ($eventType !== '') {
                    $sql .= " AND ".$eventType;
                }
            } else {
                /* if no searching is used, check for filtering by event type, and add WHERE */
                if ($eventType !== '') {
                    $sql .= "WHERE ".$eventType;
                }
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
            } else {
                echo "<p class=nothingFound>Tapahtumia ei löytynyt</p>";
            }

        ?>
    </div>
</div>
    
<?php include 'include/footer.php'; ?>

