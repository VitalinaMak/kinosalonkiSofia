<?php 
    $pageTitle = "AddEvent";
    $extraCSS = "CSS/add_edit_event.css";
    $extraJS = "JavaScript/addEvent.js";
    include 'include/header.php'; 
?>

<!-- prepare sql-statement -->
<?php 
    $stmt = $conn->prepare("INSERT INTO events (event_name, event_type, event_date, event_time, event_image, description, age_limit, location, max_visitors) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
?>

<main class="addEvent_page">
    <div class="wrapper">
        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
            <form action="addEvent.php" method="post" enctype="multipart/form-data" class="addEvent form" id="addEventForm">
                <h1>Uusi tapahtuma</h1>
                    <div class="name-age group"> 
                        <div> <!-- Название -->
                            <label for="name-input"></label> 
                            <input type="text" id="name-input" name="name" placeholder="Tapahtuman nimi" required>
                        </div>
                        <div> <!-- Age limit -->
                            <label for="ageLimit-input"></label> 
                            <select id="ageLimit-input" name="ageLimit">
                                <option value="Ei luokiteltu">Ei luokiteltu</option>
                                <option value="S">S</option>
                                <option value="K7">K7</option>
                                <option value="K12">K12</option>
                                <option value="K16">K16</option>
                                <option value="K18">K18</option>
                            </select>
                        </div>
                    </div>

                    <div> <!-- Описание -->
                        <label for="description-input"></label> 
                        <textarea id="description-input" name="description" placeholder="Kuvaus" required></textarea>
                    </div>

                    <div> <!-- Фото -->
                        <label for="eventPicture-input"> Lataa tapahtuman kuva! </label> 
                        <input type="file" id="eventPicture-input" name="eventPicture">
                        <img id="preview" style="display:none; width:50%;"> <!-- preview -->
                    </div>

                    <div class="type-place group"> 
                        <div> <!-- Type -->
                            <label for="eventType-input"></label> 
                            <select id="eventType-input" name="eventType" required>
                                <option value="1">Elokuvaesitys</option>
                                <option value="2">Tapahtuma, jossa on rajattu osalisujamäärä</option>
                                <option value="3">Tapahtuma, jossa on rajaton osalisujamäärä</option>
                            </select>
                        </div>
                        <div> <!-- Места -->
                            <label for="maxplaces-input"> </label> 
                            <input type="number" id="maxplaces-input" name="maxplaces" min="1" placeholder=" Max. osallistujamäärä: 24" required>
                        </div>
                    </div>

                    <div> <!-- Adress -->
                        <label for="adress-input"></label> 
                        <input type="text" id="adress-input" name="adress" placeholder="Adress" autocomplete="street-address" required>
                    </div>
                    <div class="date-time group">
                        <div> <!-- Date -->
                            <label for="date-input"></label>
                            <input type="date" id="date-input" name="date" required>
                        </div>
                        <div> <!-- Time -->
                            <label for="time-input"></label>
                            <input type="time" id="time-input" name="time" required>
                        </div>
                    </div>
                <button type="submit">Lisää tapahtuma</button>
            </form>
        <?php endif ?>
    </div>

<?php 
    $params = [];
    $types = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $types = "sissssssi"; // data types, i = int, s = string

        /* picture handling - START */
        $uploadFileName = "";
        $uploadOk = 1;  //status control variable
        
        if (isset($_FILES["eventPicture"])) {
            
            if ($_FILES["eventPicture"]["error"] === UPLOAD_ERR_NO_FILE) {
                // if no file uploaded, leave the string for the file name empty
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
                        echo "<img src='$targetdir.$newFileName' alt='Uploaded Image'>";
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
        $maxplaces = 1;
        if ((int)$_POST['eventType'] === 1) {
            $maxplaces = 24;  //if event type is movie, max. amount of places is always 24
        } else if ((int)$_POST['eventType'] === 3) {
            $maxplaces = 1;  //for the 3rd type of event (event with unlimited visitors) the default value is 1, the it will be handled differently in the event list
        } else {
            $maxplaces = (int)$_POST['maxplaces'];
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
                echo "<p>Uusi tapahtuma lisätty onnistuneesti.</p>";
            } else {
                echo "<p>Error: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p>Tapahtuma ei lisätty, koska kuvaa ei voitu ladata.</p>";
        }
    }
?>

</main>

<?php include 'include/footer.php'; ?>

