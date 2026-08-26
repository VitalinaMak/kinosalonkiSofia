<?php 
    require_once 'include/configuration.php';

    $emails = $pdo->query("
        SELECT * FROM notifications
        WHERE status = 0
        LIMIT 50
    ");

    foreach ($emails as $email) {
        //get the name of the event
        $tapahtuma = $pdo->query("SELECT event_name FROM events WHERE id={$email['event_id']}")->fetch(PDO::FETCH_ASSOC);

        //send email (simplified version, later will be changed with SendGrid, Mailgun, PHPMailer or something like this)
        mail(
            $email['recipient'],
            "Uusi tapahtuma: {$tapahtuma['event_name']}",
            $email['message']
        );
        $pdo->query("
            UPDATE notifications
            SET status='sent'
            WHERE id={$email['id']}
        ");
    }

?>