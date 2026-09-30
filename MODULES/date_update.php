<?php 
    include "../INCLUDE/configuration.php";  //connection to the DB

    if (isset($_GET['update']) && $_GET['update'] == 1) {
        updateEventDates($pdo);  //call the function to update event dates
    }

    function updateEventDates($pdo) {
        $date = date('Y-m-d');  //gets current date in suitable for dql format
    
        $stmt_update = $pdo->prepare("UPDATE events SET event_date = :newDate WHERE id = :eventID");
    
        $sql = "SELECT id FROM events ORDER BY event_date ASC LIMIT 4;";  //base query for getting id of the 4 evnts with the earliest dates
        $stmt = $pdo->query($sql);
        for ($i = 0; $row = $stmt->fetch(PDO::FETCH_ASSOC); $i++) {
            $eventID = $row['id'];  //get id of the event
            $params = [
                ':newDate' => $date,
                ':eventID' => $eventID
            ];
            $successCounter = 0;
            /* execute the query. On success reload the page, else show error */
            try {
                    if ($stmt_update->execute($params)) {
                        $successCounter++;  //doesn't really do anything, just some action for if-statement
                    }
                } catch (PDOException $e) {
                    echo "<h1>Error: " . $e->getMessage() . "</h1>";
                }
        }
        header("Location: ../COMMON/index.php");  //reload the page after updating the dates
        exit();
    }

?>