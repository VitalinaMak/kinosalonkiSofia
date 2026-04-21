<?php require_once 'include/configuration.php'; ?>

<?php
    /* Set JSON response header */
    header('Content-Type: application/json');

    /* get and validate user id */
    $user = $_SESSION['user_id'] ?? null;
    if (!$user) {
        echo json_encode([      
            "success" => false,
            "message" => "Kirjaudu sisään."
        ]);
        exit;
    }

    /* get and validate event id */
    $eventID = (int)$_GET['id'];  //id of the event - cast to int for safety
    if ($eventID <= 0) {
        echo json_encode([      
            "success" => false,
            "message" => "Virheellinen tapahtuma."
        ]);
        exit;
    }

    //$totalBooked = $userAlreadyBooked + count($seatsArray);  total amount of booked seats

    /* prevent data handling before form submission */
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            "success" => false,
            "message" => "Virheellinen pyyntö."
        ]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);  //read and decode JSON-input from JavaScript

    /* Validate JSON data */
    if (!$data || !isset($data['seats'])) {
        echo json_encode([
            "success" => false,
            "message" => "Virheellinen data."
        ]);
        exit;
    }

    $seats = explode(",", $data['seats']);  //get the numbers of selected seats and save them into array. This line splits string and KEEPS empty values. To remove empty values change it to $seats = array_filter(explode(",", $data['seats']), fn($s) => $s !== "");

    $selectedCount = count($seats);  //amount of selected seats

    /* get how many seats the user already booked */
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM bookings WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$user, $eventID]);
    $userAlreadyBooked = (int)$stmt->fetchColumn();  //get only one value from the first column (query returns only the result of counting anyway)

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
    
    try {
        $pdo->beginTransaction();  //start a transaction, so all DB changes are temporary

        foreach ($seats as $seat) {
            $seat = (int)$seat;

            /* check if seat already taken */
            $check = $pdo->prepare("
                SELECT COUNT(*) 
                FROM bookings 
                WHERE event_id = ? AND seat_number = ?
            ");
            $check->execute([$eventID, $seat]);

            if ($check->fetchColumn() > 0) {
                throw new Exception("Seat already booked");  //if the seat is booked, throw exception and immediatly jump to catch
            }

            /* insert booking */
            $insert = $pdo->prepare("
                INSERT INTO bookings (user_id, event_id, seat_number)
                VALUES (?, ?, ?)
            ");
            $insert->execute([$user, $eventID, $seat]);
        }

        /* if everything is OK, commit changes */
        $pdo->commit();

        echo json_encode([
            "success" => true,
            "message" => "Varaus onnistui! Kiitos varauksestasi.",
            "bookedSeatsCount" => $userAlreadyBooked + $selectedCount
        ]);

    } catch (Exception $e) {
        $pdo->rollBack();  //cancel all changes

        echo json_encode([
            "success" => false,
            "message" => "Paikka on jo varattu tai tapahtui virhe."
        ]);
    }
?>