<?php 

    require_once '../include/configuration.php';  //connection to database and session start

    /* if the user is admin, redirect to the admin's tapahtumat page */
    if (isset($_SESSION['user_id']) && $_SESSION['user_id']==1) {  //admin's account shouldn't be deleted, so it's id always remains 1
        header("Location: $baseUrl/ADMIN/ad_tapahtumat.php");
        exit();
    }

    $pageTitle = "Tapahtumat";
    $extraCSS = "/kinosalonkiSofia/CSS/tapahtumat.css";
    $extraJS = "/kinosalonkiSofia/JavaScript/tapahtumat.js";
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

            /* -------FILE 1-------- */
            include '../MODULES/events_query.php';  //base query for events (NOTE: it's uncompleted!). It uses variables $sql, $params[]
            /* -------FILE 1 END-------- */
            
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

            
            
            $sql .= " GROUP BY events.id ORDER BY $order, events.event_time;";

            /* -------FILE 2-------- */
            include '../MODULES/events_display.php';  //display events. It uses variables $sql, $params, $hasRows, $row, $bgColor, $typeForColor, $kuvaPath, $placesNumber, $ageLimit, $imageHtml 
            /* -------FILE 2 END-------- */
            
        ?>
    </div>
</main>
    
<?php include '../include/footer.php'; ?>