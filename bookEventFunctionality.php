<?php require_once 'include/configuration.php'; ?>

<?php
    $eventID = $_GET['id'];  //id of the event

    $user = $_SESSION['user_id'] ?? null;  //user's id

    //$totalBooked = $userAlreadyBooked + count($seatsArray);  total amount of booked seats

    /* prevent data handling before form submition */
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $data = json_decode(file_get_contents("php://input"), true);  //read and decode JSON-input from JavaScript

    $seats = explode(",", $data['seats']);  //get the numbers of selected seats and save them into array

    $selectedCount = count($seats);  //amount of selected seats

    /* get how many seats the user already booked */
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM bookings WHERE user_id = ? AND event_id = ?");
    $stmt->bind_param("ii", $user, $eventID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $userAlreadyBooked = $row['count'];

    /* if too much places booked, return an error */
    if (($userAlreadyBooked + $selectedCount) > 2) {
        echo json_encode([
            "success" => false,
            "message" => "Voit varata enintään 2 paikkaa.",
        ]);
        exit;
    }

    $success = true;  //variable for status check

    /* save booking to the database */
    foreach ($seats as $seat) {
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, seat_number) VALUES (?,?,?)");
        $stmt->bind_param("iii", $user, $eventID, $seat);

        if (!$stmt->execute()) {
            $success = false;
        }
    }

    $newBookedCount = $userAlreadyBooked + $selectedCount;  //new total amount of booked seats for this event

    /* show booking status */
    echo json_encode([
        "success" => $success,
        "message" => $success ? "Varaus onnistui! Kiitos varauksestasi." : "Virhe varauksessa.",
        "bookedSeatsCount" => $newBookedCount
    ]);
?>