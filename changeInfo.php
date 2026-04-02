<?php
    require_once 'include/configuration.php';  //connection to database and session start

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    /* check if the user is registered */
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $userID = ($_SESSION['user_id']);  // registered user's id

    /* get the old email in case it'll be changed */
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $stmt->store_result();
    // bind result to variable
    $stmt->bind_result($oldEmail);
    // fetch the result into the variable
    $stmt->fetch();
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /* functionality for password reset */
        if (isset($_POST['newPassword'])) {
            /* retrieve the old password hash from database */
            $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $stmt->store_result();
            // bind result to variable
            $stmt->bind_result($passHashFromDB);
            // fetch the result into the variable
            $stmt->fetch();
            $stmt->close();

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
                $updateStmt = $conn->prepare("UPDATE users SET password_hash=? WHERE id=?;");  // Prepare and bind parameters to sql-query
        
                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $newHash = password_hash($newPass, PASSWORD_DEFAULT);  //hashed new password from input

                $updateStmt->bind_param("ss", $newHash, $userID);
        
                if ($updateStmt->execute()) {
                    /* echo "Tiedot on päivitetty onnistuneesti"; */
                    $_SESSION['success_message'] = "Tiedot on päivitetty onnistuneesti";  //save the message into session
                    header("Location: account.php");  //redirect back to account.php
                } else {
                    echo "Error: " . $stmt->error;
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
                $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
                $checkEmailStmt->bind_param("s", $email);
                $checkEmailStmt->execute();
                $checkEmailStmt->store_result();
                if ($checkEmailStmt->num_rows > 0) {
                    echo "Sähköposti on jo käytössä.";
                }
            }
    
            /* UPDATE INFORMATION */
            
            $updateStmt = $conn->prepare("UPDATE users SET email=?, username=? WHERE id=?;");  // Prepare and bind parameters to sql-query
        
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $updateStmt->bind_param("sss", $email, $username, $userID);
    
            if ($updateStmt->execute()) {
                /* echo "Tiedot on päivitetty onnistuneesti"; */
                $_SESSION['success_message'] = "Tiedot on päivitetty onnistuneesti";  //save the message into session
                header("Location: account.php");  //redirect back to account.php
            } else {
                echo "Error: " . $stmt->error;
            }
            
        }
        
    }
?>