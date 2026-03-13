<?php 
    $pageTitle = "AddEvent";
    $extraCSS = "CSS/add_edit_event.css";
    $extraJS = "JavaScript/add_edit_event.js";
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
                        <div class="dropdown"> <!-- Age limit -->
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
                        <div class="fileInputField">
                            <label for="eventPicture-input"> <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M440-440ZM120-120q-33 0-56.5-23.5T40-200v-480q0-33 23.5-56.5T120-760h126l74-80h240v80H355l-73 80H120v480h640v-360h80v360q0 33-23.5 56.5T760-120H120Zm640-560v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80ZM440-260q75 0 127.5-52.5T620-440q0-75-52.5-127.5T440-620q-75 0-127.5 52.5T260-440q0 75 52.5 127.5T440-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Z"/></svg>
                                <!-- Tapahtuman kuva  -->
                                <span class="label-text">Tapahtuman kuva</span>
                                <input type="file" id="eventPicture-input" name="eventPicture" hidden>  <!-- an actual file input, but it's hidden -->
                                <input type="hidden" name="remove_image" value="0">  <!-- a flag that signals if the user clicked “remove image”  -->
                                <img id="preview" style="display:none; width:50%;"> <!-- preview -->
                                <a class="button" href="javascript:void(0)" onclick="removeImage()">Poistaa kuvaa</a>  <!-- a link to remove the picture -->
                            </label> 
                        </div>
                    </div>

                    <div class="type-place group dropdown"> 
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
                            <input type="number" id="maxplaces-input" name="maxplaces" min="1" placeholder="Max. osallistujamäärä" required>
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

