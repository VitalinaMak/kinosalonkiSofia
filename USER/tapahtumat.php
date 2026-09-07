<?php 

    require_once '../include/configuration.php';  //connection to database and session start

    /* if the user is admin, redirect to the admin's tapahtumat page */
    if (isset($_SESSION['user_id']) && $_SESSION['user_id']==1) {  //admin's account shouldn't be deleted, so it's id always remains 1
        header("Location: $baseUrl/ADMIN/ad_tapahtumat.php");
        exit();
    }

    $pageTitle = "Tapahtumat";
    $extraCSS = "/kinosalonkiSofia/CSS/tapahtumat.css";
    $extraJS = $baseUrl . "/kinosalonkiSofia/JavaScript/tapahtumat.js";
    include '../include/header.php'; 
?>

<?php
    /* handling empty search field here, because it throws an error if there's any output before header() */
    if (isset($_GET['search']) && trim($_GET['search']) === '') {
        $params = [];  //array for key-value pairs, that will store all of the parameters (such as sort or filtering options)
        if (isset($_GET['sort'])) {
            $params['sort'] = $_GET['sort'];  //if sorting is used, save it's value to the array
        }
        if (isset($_GET['type'])) {
            $params['type'] = $_GET['type'];  //if filtering by event type is used, save it's value to the array
        }
        $query = http_build_query($params);  //http-query with all used parameters
        header("Location: $baseUrl/USER/tapahtumat.php" . ($query ? "?$query" : ""));  //if query is not empty, add it to the URL and reload the page
    }
?>

<main class="tapahtumat.php">

    <div class="top">
        <h1>Tapahtumat</h1>
        <!-- line with all of the actions for the list -->
        <div class="eventListActions">
            <div class="search">
                <!-- search field. When the info is sent, it passes it in URL -->
                <form action="" method="GET" id="searchForm"> 
                    <label for="user-search" class="lupka" id="searchLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                    </label>
                    <input type="text" id="user-search" value="<?php if(isset($_GET['search'])){htmlspecialchars($_GET['search']);} ?>" name="search" placeholder="Etsi tapahtumaa">
                    
                    <?php foreach ($_GET as $key => $value): ?>  <!-- iterates through all parameters in URL if there's something else than search -->
                        <?php if ($key !== 'search'): ?>
                            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">  <!-- resends the parameters to the URL -->
                        <?php endif; ?>
                    <?php endforeach; ?>
                </form>

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
                if (isset($_GET['type'])) {
                    $params['type'] = $_GET['type'];  //if filer by event type is used, save it's value to the array
                }
                if (isset($_GET['agelimit'])) {
                    $params['agelimit'] = $_GET['agelimit'];  //if filer by age limit is used, save it's value to the array
                }
                $query = http_build_query($params);  //http_build_query automatically builds a query string for URL (sth like 'sort=nimiaz&type=2') 
                if ($query) {
                    $query = $query . "&";  //adds '&' to the end, so it'll only be added to URL if extra params are used
                }
            ?>

            <div class="sort-filter">
                <!-- button for sorting events. Contains a dropdown menu with sorting options -->
                <div class="eventSorting">  
                    <button class="sortButton"> 
                        <!-- Järjestää -->
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M120-240v-80h240v80H120Zm0-200v-80h480v80H120Zm0-200v-80h720v80H120Z"/></svg>
                    </button>
                    <div class="sortingOptions">
                        <h4>Järjestä tapahtumat:</h4>
                        <!-- save the rest of the parameters in url and add sorting -->
                        <a href="?sort=nimiaz&search=<?php echo urlencode($_GET['search'] ?? ''); if (isset($_GET['type'])) {echo '&type='.urlencode($_GET['type'] ?? '');}; if (isset($_GET['agelimit'])) if (isset($_GET['agelimit'])) {echo '&agelimit='.urlencode($_GET['agelimit'] ?? '');};?>">Tapahtuman nimi (A-Z)</a>
                        <a href="?sort=nimiza&search=<?php echo urlencode($_GET['search'] ?? ''); if (isset($_GET['type'])) {echo '&type='.urlencode($_GET['type'] ?? '');}; if (isset($_GET['agelimit'])) if (isset($_GET['agelimit'])) {echo '&agelimit='.urlencode($_GET['agelimit'] ?? '');};?>">Tapahtuman nimi (Z-A)</a>
                        <a href="?sort=pvmnouseva&search=<?php echo urlencode($_GET['search'] ?? ''); if (isset($_GET['type'])) {echo '&type='.urlencode($_GET['type'] ?? '');}; if (isset($_GET['agelimit'])) if (isset($_GET['agelimit'])) {echo '&agelimit='.urlencode($_GET['agelimit'] ?? '');};?>">Päivämäärä (nouseva)</a>  <!-- this one is used by default -->
                        <a href="?sort=pvmlaskeva&search=<?php echo urlencode($_GET['search'] ?? ''); if (isset($_GET['type'])) {echo '&type='.urlencode($_GET['type'] ?? '');}; if (isset($_GET['agelimit'])) if (isset($_GET['agelimit'])) {echo '&agelimit='.urlencode($_GET['agelimit'] ?? '');};?>">Päivämäärä (laskeva)</a>
                    </div>
                </div>
                <!-- button for filtering events. Contains a dropdown menu with filtering options -->
                <div class="eventFiltering">
                    <button class="filterButton">
                        <!-- Suodata -->
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M440-160q-17 0-28.5-11.5T400-200v-240L168-736q-15-20-4.5-42t36.5-22h560q26 0 36.5 22t-4.5 42L560-440v240q0 17-11.5 28.5T520-160h-80Zm40-308 198-252H282l198 252Zm0 0Z"/></svg>
                    </button>
                    <div class="filteringOptions">
                        <h4>Suodata tapahtumat</h4>
                        <p>Tapahtuman tyyppi:</p>
                        <!-- for every option it creates the copy of array with url-parameters and change type's value to new one (1, 2, or 3 accordingly). Then it builds a new link with new parameters and pass it to <a>-element -->
                        <?php $p = $params; $p['type'] = 1; ?>
                        <a class="event1" href="?<?php echo http_build_query($p); ?>">Elokuvaesitys</a>
            
                        <?php $p = $params; $p['type'] = 2; ?>
                        <a class="event2"  href="?<?php echo http_build_query($p); ?>">Rajattu osallistujamäärä</a>
            
                        <?php $p = $params; $p['type'] = 3; ?>
                        <a class="event3" href="?<?php echo http_build_query($p); ?>">Rajaton osallistujamäärä</a>
            
                        <p>Ikärajoitus:</p>
                        <!-- same logic as for type filters -->
                        <?php $p = $params; $p['agelimit'] = "K18"; ?>
                        <a class="age-18" href="?<?php echo http_build_query($p); ?>">K18</a>
            
                        <?php $p = $params; $p['agelimit'] = "S"; ?>
                        <a class="age-none" href="?<?php echo http_build_query($p); ?>">Ilman K18-rajoitusta</a>
            
                        <!-- button to remove all filters -->
                        <?php 
                            $p = $params;  //create a copy of the array with parameters for URL
                            unset($p['type'], $p['agelimit']);  //remove all filter parameters
                        ?>  
                        <a href="?<?php echo http_build_query($p); ?>" id="removeFilterButton">Poista suodattimet</a>
                    </div>
                </div>
            </div>
        </div> 

    </div>

    
    <!-- div for the list of events -->
    <div class="eventList">
        <?php
            $search = '';  //variable for searching
            $order = 'events.event_date ASC';  //variable for sorting data. For default sorts by date starting from earlier events 
            $eventType = '';  //variable for filtering by event type
            $ageLimitFilter = '';  //variable for filtering by age limit

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

            /* filtering by age limit */
            if (isset($_GET['agelimit'])) {
                if ($_GET['agelimit'] == "K18") {
                    $ageLimitFilter = "events.age_limit = 'k18'";  //only 18+ events
                } else {
                    $ageLimitFilter = "events.age_limit != 'k18'";  //all other events
                }
            }

            /* base sql-query */
            $sql = "
                SELECT 
                    events.id,
                    events.event_name,
                    events.event_date,
                    TO_CHAR(events.event_date, 'DD.MM.YYYY') AS event_formatted_date,
                    EXTRACT(HOUR FROM events.event_time) AS event_hour,
                    TO_CHAR(events.event_time, 'MI') AS event_minute,
                    events.event_image,
                    events.description,
                    events.age_limit,
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
                $sql .= " WHERE events.event_name ILIKE ?";
                $params[] = $search;
                $types .= "s";

                /* if filtering by event type, extra options are added to WHERE */
                if ($eventType !== '') {
                    $sql .= " AND ".$eventType;
                }

                /* if filtering be age limit, one more extra opion is added */
                if ($ageLimitFilter != "") {
                    $sql .= " AND ".$ageLimitFilter;
                }

            } else {
                /* if no searching is used, check for filtering by event type and age limitations */
                if (($eventType !== '') && ($ageLimitFilter != "")) {
                    $sql .= "WHERE ".$eventType." AND ".$ageLimitFilter;    //filter by event type AND age limit
                } else if (($eventType !== '') && ($ageLimitFilter == "")) {
                    $sql .= "WHERE ".$eventType;      //filter only by event type
                } else if (($eventType == '') && ($ageLimitFilter != "")) {
                    $sql .= " WHERE ".$ageLimitFilter;      //filter only by age limit
                }
            }

            
            
            $sql .= " GROUP BY events.id ORDER BY $order, events.event_time";

            $stmt = $pdo->prepare($sql);  //prepare statement

            $stmt->execute($params);  //execute with prepared parameters

            
            $hasRows = false;  //check if there's any output

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                $hasRows = true;  //if while-loop is activated, then at least one row exists

                /* change the color of the background depending on the event type */
                $bgColor = "";
                if ($row['event_type'] == "1") {
                    $typeForColor = "event1body";
                } else if ($row['event_type'] == "2") {
                    $typeForColor = "event2body";
                } else {
                    $typeForColor = "event3body";
                }

                // $kuvaPath = empty($row["event_image"]) ? "noImage.png" : $row["event_image"];  //if the event doesn't have image, use the default image
                $kuvaPath = !empty($row["event_image"]) ? $row["event_image"] : null;
                $placesNumber = !(is_null($row['max_visitors'])) ? "Paikkoja jäljellä: ".($row['max_visitors'] - $row['booked_places']) : "Osallistujien määrä: ".$row['booked_places'];  //amount of seats left (for 1st and 2nd types of event), calculated from the max. amount of seats (stated in the table) and amount of bookings made for the event. For the 3rd type of event (max. amount of seats = 0 by default) show amount of participants
                $ageLimit = ($row['age_limit']=="Ei luokiteltu") ? "" : $row['age_limit'];  //age limit. If it's defined, it appears in parenthesis after the name of the event

                // Build the image tag only if path exists
                $imageHtml = "";
                if ($kuvaPath) {
                    $imageHtml = "<img src='$baseUrl/kuvat/tapahtumaKuvat/$kuvaPath' alt='{$row['event_name']}'/>";
                }
                
                echo "<div class='event {$typeForColor}' onclick='window.location.href=\""."{$baseUrl}/USER/bookEvent.php?id=".$row['id']."\"'>

                        <div class='eventInfo'>
                            <h3 class='event_header'>{$row['event_name']} <span class='event_age'>{$ageLimit}</span></h3>
                            <h4 class='event_day'>{$row['event_formatted_date']} </h4> 
                            <h3 class='event_time'> <span class='klo'>klo</span> {$row['event_hour']}.{$row['event_minute']} </h3>
                            {$imageHtml}
                            <h4 class='event_adress'> 
                                <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#1f1f1f'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z'/><circle cx='12' cy='9' r='2.5'/></svg>
                                {$row['location']}</h4>
                            <p class='event_description'>{$row['description']}</p>
                            <p class='places'>{$placesNumber}</p>
                        </div>
                    </div>";
            }
            
            if (!$hasRows) {
                echo "<p class='nothingFound'>Tapahtumia ei löytynyt :(</p>";
            }

            
        ?>
    </div>
</main>
    
<?php include '../include/footer.php'; ?>