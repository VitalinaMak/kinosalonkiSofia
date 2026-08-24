<?php
    header('Content-Type: application/json');

    require_once 'include/configuration.php';

    // get event id
    $eventId = $_POST['event_id'] ?? null;
    if (!$eventId) {
        error_log("Event ID is missing");
        exit;
    }

    /* get information about new event */
    $stmt = $pdo->prepare("SELECT event_name, TO_CHAR(event_date,'DD.MM.YYYY') AS event_formatted_date, EXTRACT(HOUR FROM event_time) AS event_hour, TO_CHAR(event_time,'MI') AS event_minute, description, age_limit, location FROM events WHERE id = ?;");
    $stmt->execute([$eventId]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$event) {
        echo json_encode([
            'success' => false,
            'error' => 'Event not found'
        ]);
        exit;
    }

    $notificationCount = 0; //counter for the number of notifications sent

    /* DRAFT FOR TESTING. Content of email needs polishing */
    $header = "<h3>Uusi tapahtuma: {$event['event_name']} ({$event['age_limit']})</h3>";
    $messageContent = "
        <p><strong>Milloin?</strong> {$event['event_formatted_date']} KLO {$event['event_hour']}"."."."{$event['event_minute']}.</p>
        <p><strong>Paikka:</strong> {$event['location']}.</p>
        <p><strong>Kuvaus:</strong> {$event['description']}</p>
        <a href='bookEvent.php?id={$eventId}'><strong>Varaa paikkaa</strong></a>
    ";

    $status = "0";  //status of the email, 0 = pending, 1 = sent, 2 = failed. It will be updated later by the script that processes the queue

    /* prepare statement for insertion users in a queue */
    $stmt = $pdo->prepare("INSERT INTO notifications (recipient, event_id, message, status) VALUES (?, ?, ?, ?)");
    
    if($stmt) {
        
        /* retrieve subscribed users from the table */
        $query = "SELECT id FROM users WHERE new_events = TRUE;";
        $recipients = $pdo->query($query);  //query is used when there's no prepared statements, so here it runs the query immidiately and saves the result set in $recipients

        if ($recipients->rowCount() > 0) {
            while ($row = $recipients->fetch(PDO::FETCH_ASSOC)) {
                $userId = $row['id'];
                $result = $stmt->execute([$userId, $eventId, $messageContent, $status]);
                $notificationCount++; //increment counter for each notification added to the queue
                if (!$result) {
                    error_log("Failed to insert notification: " . implode(", ", $stmt->errorInfo()));
                }
            }
        } else {
            error_log("No subscribed users found");
        }

    } else {
        error_log("Error preparing statement: " . $pdo->errorInfo()[2]);
    }

    echo json_encode([
        'success' => true, 
        'notificationCount' => $notificationCount]);


    /* process queue with PHPMailer */
    /* $mail = new PHPMailer(true);

    $emails = getPendingEmails(50); // batch size

    foreach ($emails as $email) {
        try {
            $mail->clearAddresses();
            $mail->addAddress($email['email']);
            $mail->Subject = $email['subject'];
            $mail->Body = $email['message'];

            $mail->send();

            markAsSent($email['id']);
            sleep(1); // avoid rate limits
        } catch (Exception $e) {
            markAsFailed($email['id']);
        }
    } */

    // notification logic (copied from moreBookings, the real sending happens in sendNotification.php, here is just one of the options)
    /* try {
        $mail->isSMTP();
        $mail->Host = 'smtp.sendgrid.net'; // or Mailgun/SES
        $mail->SMTPAuth = true;
        $mail->Username = 'your_username';
        $mail->Password = 'your_api_key';

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New message from website';
        $mail->Body = "
            <h3>New Contact Form Message</h3>
            <p><strong>Asiakkaan nimi:</strong> $name</p>
            <p><strong>Sähköposti:</strong> $email</p>
            <p><strong>Puhelinnumero:</strong> $phone</p>
            <p><strong>Tapahtuma:</strong><br>$eventName</p>
            <p><strong>Varattavien paikkojen määrä:</strong> $places</p>
            <p><strong>Lisähuomautukset:</strong> $comment</p>
        ";
        // alternative email content (in plain-text form) in case the client doesn't support HTML
        $mail->AltBody = "
        Asiakkaan nimi: $name
        Sähköposti: $email
        Puhelinnumero: $phone
        Tapahtuma: $eventName
        Paikat: $places
        Lisähuomautukset: $comment
        ";
        
        // Send email
        // $mail->send();  (currently disabled due to the missing app password)
        echo json_encode([
            "status" => "success",
            "message" => "Viesti lähetetty",
            "debug" => [
                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "places" => $places,
                "eventName" => $eventName,
                "comment" => $comment,
                "body" => $mail->Body
            ]
        ]);
    } catch (Exception $e) {
        echo "Viestin lähettäminen epäonnistui. Virhe: {$mail->ErrorInfo}";
    } */

?>