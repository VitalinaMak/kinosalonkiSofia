<?php 
    $extraJS = "JavaScript/add_edit_event.js";
    require_once 'include/configuration.php';
    
    $params = [];
    $types = "";

    /* prepare sql-statement */
    $stmt = $conn->prepare("INSERT INTO events (event_name, event_type, event_date, event_time, event_image, description, age_limit, location, max_visitors) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

  /*   if ($_SERVER['REQUEST_METHOD'] === 'POST') { */

        $types = "sissssssi"; // data types, i = int, s = string

        /* picture handling - START */
        $uploadFileName = "";
        $uploadOk = 1;  //status control variable
        
        if (isset($_FILES["eventPicture"])) {
            
            if (($_FILES["eventPicture"]["error"] === UPLOAD_ERR_NO_FILE) || (isset($_POST['remove_image']) && $_POST['remove_image'] === "1")) {
                // if no file uploaded or user deleted it, leave the string for the file name empty
                $uploadFileName = "";
            }

            elseif ($_FILES["eventPicture"]["error"] === UPLOAD_ERR_OK) {
        
                $target_dir = "kuvat/tapahtumaKuvat/";  //defines the folder where the file will be saved
                $target_file = basename($_FILES["eventPicture"]["name"]);  //removes any directory path and keeps only the file name
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));  //retrieves the file extention (.png/.jpg etc) and converts it to lowercase

                $newFileName = round(microtime(true)) . "." . $imageFileType;  //gives the file a new name created from the timestamp and extension from the old name

                /* check for mistakes */
                $check = getimagesize($_FILES["eventPicture"]["tmp_name"]);

                if ($check === false) {
                    $uploadOk = 0;
                    echo "File is not an image.";
                }

                if (file_exists($target_file)) {
                    $uploadOk = 0;
                    echo "File already exists.";
                }

                if ($uploadOk === 1) {
                    if (move_uploaded_file($_FILES["eventPicture"]["tmp_name"], $target_dir . $newFileName)) {  //add file to the project folder
                        $uploadFileName = $newFileName;  //if everything is OK, give the variable new name of the picture
                        /* echo "<img src='{$target_dir}.{$newFileName}' alt='Uploaded Image'>"; */
                    } else {
                        $uploadOk = 0;
                        echo "Error uploading file.";
                    }
                }
            }
        } else {
            // Some other upload error
            $uploadOk = 0;
            echo "Upload error code: " . $_FILES["eventPicture"]["error"];
        }
        /* picture handling - END */

        /* max. places number handling */
        $maxplaces = "";  //it has to be empty by default, so the database can set NULL
        if ((int)$_POST['eventType'] === 1) {
            $maxplaces = 24;  //if event type is movie, max. amount of places is always 24
        } else if ((int)$_POST['eventType'] === 3) {
            $maxplaces = "";  //for the 3rd type of event (event with unlimited visitors) the default value is "", it will be set to NULL when passed to the DB
        } else {
            $maxplaces = (int)$_POST['maxplaces'];  //for the 2nd type of event get the value from input
        }

        /* parameters for prepared statement */
        $params = [
            $_POST['name'],
            (int)$_POST['eventType'],
            $_POST['date'],
            $_POST['time'],
            $uploadFileName,
            $_POST['description'],
            $_POST['ageLimit'],
            $_POST['adress'],
            $maxplaces
        ];

        /* convert values of parameters to references */
        $tmp = [];
        foreach ($params as $key => $value) {
            $tmp[$key] = &$params[$key];
        }
            
        if (!$stmt->bind_param($types, ...$tmp)) {
            die("Bind param failed: " . $stmt->error);
        }

        /* if there's no errors with image, execute the statement */
        if ($uploadOk === 1) {
            if ($stmt->execute()) {
                $eventId = $stmt->insert_id;  //save id of the added event
                /* send data to JavaScript */
                echo json_encode([
                    "status" => "success",
                    "event_id" => $eventId
                ]);
                /* echo "<p>Uusi tapahtuma lisätty onnistuneesti.</p>";
                echo "<a href='tapahtumat.php' class='button'>Takaisin tapahtuma-listalle</a>"; */
            } else {
                /* send error-message to JavaScript */
                echo json_encode([
                    "status" => "error",
                    "message" => $stmt->error
                ]);
               /*  echo "<p>Error: " . $stmt->error . "</p>"; */
            }
        } /* else {
            echo "<p>Tapahtuma ei lisätty, koska kuvaa ei voitu ladata.</p>";
        } */
   /*  } */
?>