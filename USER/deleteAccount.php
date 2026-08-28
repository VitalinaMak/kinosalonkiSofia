<?php
    require_once 'include/configuration.php';  //connection to database and session start

    if (isset($_POST['delete_account'])) {
        $userID = ($_SESSION['user_id']);  //current user's id

        /* prepared query to remove the user from the database */
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?;");
        if (!$stmt) {
            die("Prepare failed: " . $pdo->errorInfo()[2]);
        }

        if ($stmt->execute([$userID])) {

            /* clear the memory and destroy current session */
            session_unset();
            session_destroy();

            header("Location: index.php");  //redirect to index
            exit();
        }
    }
?>