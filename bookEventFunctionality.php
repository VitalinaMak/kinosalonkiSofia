<?php require_once 'include/configuration.php'; ?>

<?php
    $eventID = $_GET['id'];  //id of the event

    $user = $_SESSION['user_id'] ?? null;  //user's id

    /* prevent data handling before form submition */
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }
    $data = json_decode(file_get_contents("php://input"), true);  //read and decode JSON-input from JavaScript

    $seats = explode(",", $data['seats']);  //get the numbers of selected seats and save them into array
    $success = true;  //variable for status check

    /* save booking to the database */
    foreach ($seats as $seat) {
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, seat_number) VALUES (?,?,?)");
        $stmt->bind_param("iii", $user, $eventID, $seat);

        if (!$stmt->execute()) {
            $success = false;
        }
    }

    /* show booking status */
    if ($success) {
        echo "Varaus onnistui! Kiitos varauksestasi.";
    } else {
        echo "Virhe varauksessa.";
    }
?>