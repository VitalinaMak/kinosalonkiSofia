<?php
    require_once '../include/configuration.php';
    
    /* include PHPMailer-library to send emails. It's already installled in the project (files composer.json, composer.lock and folder vendor) */
    require '../vendor/autoload.php';

    /* PHPMailer classes */
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* get all data from the form */
    $user = $_POST["user_id"];
    $eventID = $_POST["event_id"];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $places = $_POST['places'];
    $eventName = $_POST['eventName'];
    $comment = ($_POST['comment']) ? $_POST['comment'] : "Ei lisähuomautuksia";

    $mail = new PHPMailer(true);

    try {
        // SMTP settings (using Gmail)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@gmail.com'; // email from which all the messages will be sent
        $mail->Password = 'your_app_password';    // app password
        $mail->SMTPSecure = 'tls';  //encrypt the connection
        $mail->Port = 587;

        // Sender & recipient
        $mail->setFrom($email, $name);
        $mail->addAddress('admin@mail.com'); // here will be admin's email

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
        /* alternative email content (in plain-text form) in case the client doesn't support HTML */
        $mail->AltBody = "
        Asiakkaan nimi: $name
        Sähköposti: $email
        Puhelinnumero: $phone
        Tapahtuma: $eventName
        Paikat: $places
        Lisähuomautukset: $comment
        ";

        /* save the message to the database */
        $insert = $pdo->prepare("
                INSERT INTO extra_bookings (created_at, user_id, event_id, places_amount, message)
                VALUES (?, ?, ?, ?, ?)
            ");
        $insert->execute([date('Y-m-d H:i:s'), $user, $eventID, $places, $comment]);

        // Send email
        /* $mail->send(); */  // currently disabled due to the missing app password
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
    }
}

?>