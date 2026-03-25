<?php
    /* include PHPMailer-library to send emails. It's already installled in the project (files composer.json, composer.lock and folder vendor) */
    require 'vendor/autoload.php';

    /* PHPMailer classes */
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* get all data from the form */
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

        // Send email
        /* $mail->send(); */  // currently disabled due to the missing app password
        echo "OK";  //for debugging copy and paste all elements above into echo to see the content of the message
       /*  echo $email->body; */

    } catch (Exception $e) {
        echo "Viestin lähettäminen epäonnistui. Virhe: {$mail->ErrorInfo}";
    }
}

?>