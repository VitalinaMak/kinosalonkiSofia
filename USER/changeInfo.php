<?php
    require_once '../include/configuration.php';  //connection to database and session start

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    /* check if the user is registered */
    if (!isset($_SESSION['user_id'])) {
        header("Location: $baseUrl/login.php");
        exit();
    }

    $userID = ($_SESSION['user_id']);  // registered user's id

    /* get the old email in case it'll be changed */
    $stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute([$userID]);
    // fetch the result into the variable
    $oldEmail = $stmt->fetchColumn();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /* functionality for password reset */
        if ($_POST['form_type'] === 'password') {
            /* retrieve the old password hash from database */
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$userID]);
            // fetch the result into the variable
            $passHashFromDB = $stmt->fetchColumn();

            /* assign new values to the variables */
            $oldPass = $_POST['oldPassword'];
            $newPass = $_POST['newPassword'];
            $newPassRepeat = $_POST['newPasswordRepeat'];
            
           if ($newPass != $newPassRepeat) {
                die ('Salasanat eivät täsmää!');
            }

            /* check if the old password is valid */
            if (password_verify($oldPass, $passHashFromDB)) {
                echo "Password is verified";
                try {
                    $updateStmt = $pdo->prepare("UPDATE users SET password_hash=? WHERE id=?;");  // Prepare and bind parameters to sql-query

                    $newHash = password_hash($newPass, PASSWORD_DEFAULT);  //hashed new password from input
                    if ($updateStmt->execute([$newHash, $userID])) {
                        $_SESSION['success_message'] = "Tiedot on päivitetty onnistuneesti";  //save the message into session
                        header("Location: account.php");  //redirect back to account.php
                    } 
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

            } else {
                echo "Vanha salasana on virheellinen!";
            }

        } else {
            /* assign values to variables */
            $username = trim($_POST['name']);
            $email = trim($_POST['email']);
    
            // Check if new email already exists
            if ($email != $oldEmail) {
                $checkEmailStmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
                $checkEmailStmt->execute([$email]);
                if ($checkEmailStmt->fetchColumn() > 0) {
                    echo "Sähköposti on jo käytössä.";
                }
            }
    
            /* UPDATE INFORMATION */
            try {
                $updateStmt = $pdo->prepare("UPDATE users SET email=?, username=? WHERE id=?;");  // Prepare and bind parameters to sql-query
                if ($updateStmt->execute([$email, $username, $userID])) {
                    $_SESSION['success_message'] = "Tiedot on päivitetty onnistuneesti";  //save the message into session
                    header("Location: account.php");  //redirect back to account.php
                } 
            } catch (PDOException $e) {
                $_SESSION['success_message'] = "Virhe: " . $e->getMessage();
            }
            
        }
        
    }
?>