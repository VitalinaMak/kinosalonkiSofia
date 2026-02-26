<?php 
    $pageTitle = "AddEvent";
    $extraCSS = "CSS/add_edit_event.css";
    include 'include/header.php'; 
?>

<!-- prepare sql-statement -->
<?php 
    $stmt = $conn->prepare("INSERT INTO events (event_name, event_type, event_date, event_time, event_image, description, location, max_visitors) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
?>

<main class="addEvent_page">
    <div class="wrapper">
        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
            <form action="addEvent.php" method="post"  enctype="multipart/form-data" class="addEvent form">
                <h1>Uusi tapahtuma</h1>

                <div class="column1">
                <div> <!-- Название -->
                    <label for="name"></label> 
                    <input type="text" id="name" name="name" placeholder="Nimi" required minlength="2">
                </div>
                <div> <!-- Фото -->
                    <label for="eventPicture-input"> Lataa tapahtuman kuva! </label> <br>
                    <input type="file" id="eventPicture-input" name="eventPicture">
                </div>
                <div> <!-- Описание -->
                    <label for="description-input"></label> 
                    <textarea type="text" id="description-input" name="description" placeholder="Kuvaus" required></textarea>
                </div>
                <div> <!-- Type -->
                    <label for="eventType-input"></label> 
                    <select id="eventType-input" name="eventType" required>
                    <option value="1">Elokuvaesitys</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    </select>
                </div>
                </div>
                <div class="column2">
                <div> <!-- Места -->
                    <label for="maxplaces-input">Max. osallistujamäärä: </label> <br>
                    <input type="text" id="maxplaces-input" name="maxplaces" placeholder="24" required>
                </div>

                <div> <!-- Place -->
                    <label for="adress-input"></label> 
                    <input type="text" id="adress-input" name="adress" placeholder="Adress" autocomplete="street-address" required>
                </div>
                <div> <!-- Date -->
                    <label for="date-input"></label>
                    <input type="date" id="date-input" name="date" required>
                </div>
                <div> <!-- Time -->
                    <label for="time-input"></label>
                    <input type="time" id="time-input" name="time" required>
                </div>
                <button type="submit">Lisää tapahtuma</button>
                </div>
            </form>
        <?php endif ?>
    </div>

<?php 
    $params = [];
    $types = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $types = "sisssssi"; // data types, i = int, s = string

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
                $target_file = $target_dir . basename($_FILES["eventPicture"]["name"]);  //removes any directory path and keeps only the file name
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));  //retrieves the file extention (.png/.jpg etc) and converts it to lowercase

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
                    if (move_uploaded_file($_FILES["eventPicture"]["tmp_name"], $target_file)) {
                        $uploadFileName = basename($_FILES["eventPicture"]["name"]);  //if everything is OK, give the variable the name of the picture
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

