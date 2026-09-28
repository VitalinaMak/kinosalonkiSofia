<?php
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
?>