<link rel="stylesheet" href="../CSS/events_display.css">

<?php
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
            $imageHtml = "<img src='../IMAGES/tapahtumaKuvat/$kuvaPath' alt='{$row['event_name']}'/>";
        }
        
        echo "<div class='event {$typeForColor}' onclick='window.location.href=\""."../USER/bookEvent.php?id=".$row['id']."\"'>

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