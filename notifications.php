<?php
include 'include/header.php';
// get event id
$eventId = $_POST['event_id'] ?? null;

// do your notification logic here
// (queue emails, etc.)

echo "OK: ".$eventId;
?>