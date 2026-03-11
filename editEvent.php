<?php 
    $pageTitle = "EditEvent";
    $extraCSS = "CSS/add_edit_event.css";
    $extraJS = "JavaScript/addEvent.js";
    include 'include/header.php'; 
?>
<main class="editEvent_page">
    <div class="wrapper">
    
        <form action="editEvent.php" method="post"  enctype="multipart/form-data" class="editEvent">
            <h1> Edit event</h1>
            <?php
                if ($_SERVER['REQUEST_METHOD'] !== 'POST'):
                    $ehto = (int)$_GET['id'];  //get id from the URL
                    $sql = "SELECT * FROM events WHERE id = $ehto";  //search for the event with that id in the DB
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                        $eventType = $row['event_type']; 
                        $ageLimit = $row['age_limit']; 
                        $picture = "noImage.png";
                        if ($row['event_image'] !== "") {
                            $picture = $row['event_image'];
                        } ?>
                            <input type="hidden" name="id" value="<?=$row['id'];?>" />  <!-- id (id doesn't have to be changed, it's here only to save it's value) -->
                            <!-- - - - HTML - - - -->
                            <div class="name-age group"> 
                                <div> <!-- Название -->
                                    <label for="name-input"></label> 
                                    <input type="text" id="name-input" name="name" value="<?=htmlspecialchars($row['event_name']);?>" required minlength="2">
                                </div>
                                <div class="dropdown"> <!-- Age limit -->
                                    <label for="ageLimit-input"></label> 
                                    <select id="ageLimit-input" name="ageLimit">
                                        <option value="Ei luokiteltu" <?= $ageLimit == 'option3' ? 'selected' : '' ?>>Ei luokiteltu</option>
                                        <option value="S" <?= $ageLimit == 'option3' ? 'selected' : '' ?>>S</option>
                                        <option value="K7" <?= $ageLimit == 'option3' ? 'selected' : '' ?>>K7</option>
                                        <option value="K12" <?= $ageLimit == 'option3' ? 'selected' : '' ?>>K12</option>
                                        <option value="K16" <?= $ageLimit == 'option3' ? 'selected' : '' ?>>K16</option>
                                        <option value="K18" <?= $ageLimit == 'option3' ? 'selected' : '' ?>>K18</option>
                                    </select>
                                </div>
                            </div>

                            <div> <!-- Описание -->
                                <label for="description-input"></label> 
                                <textarea type="text" id="description-input" name="description" required><?=htmlspecialchars($row['description']);?></textarea>
                            </div>
                            
                            <div class="fileInputField"> <!-- Фото -->
                                <label for="eventPicture-input"> Tapahtuman kuva 
                                    <input type="file" id="eventPicture-input" name="eventPicture" hidden>  <!-- input-field for the image. It's accessed through the label but it cannot contain information about the previous image-->
                                    <input type="hidden" name="current_image" value="<?= htmlspecialchars($row['event_image']); ?>">  <!-- ..so here is another input -->
                                    <input type="hidden" name="original_image" value="<?= htmlspecialchars($row['event_image']); ?>"> <!-- and one more hidden input in case the user changes the previous one -->
                                    <?= "<img id='preview' src='kuvat/tapahtumaKuvat/".$picture."' alt='Uploaded Image'>"; ?>
                                    <a href="javascript:void(0)" onclick="removeImage()" style="padding: 5px;">Poistaa kuvaa</a>
                                </label>
                            </div>

                            <div class="type-place group dropdown"> 
                                <div class="dropdown"> <!-- Type -->
                                    <label for="eventType-input"></label> 
                                    <select id="eventType-input" name="eventType" required>
                                        <option value="1" <?= $eventType == 'option1' ? 'selected' : '' ?>>Elokuvaesitys</option>
                                        <option value="2" <?= $eventType == 'option2' ? 'selected' : '' ?>>Tapahtuma, jossa on rajattu osalisujamäärä</option>
                                        <option value="3" <?= $eventType == 'option3' ? 'selected' : '' ?>>Tapahtuma, jossa on rajaton osalisujamäärä</option>
                                    </select>
                                </div>
                                <div> <!-- Place -->
                                    <label for="maxplaces-input">Max. osallistujamäärä: </label> <br>
                                    <input type="number" id="maxplaces-input" name="maxplaces" value="<?=htmlspecialchars($row['max_visitors']);?>" required>
                                </div>
                            </div>

                            <div> <!-- Adress -->
                                <label for="adress-input"></label> 
                                <input type="text" id="adress-input" name="adress" value="<?=htmlspecialchars($row['location']);?>" autocomplete="street-address" required>
                            </div>
                            <div class="date-time group">
                                <div> <!-- Date -->
                                    <label for="date-input"></label>
                                    <input type="date" id="date-input" name="date" value="<?=$row['event_date'];?>" required>
                                </div>
                                <div> <!-- Time -->
                                    <label for="time-input"></label>
                                    <input type="time" id="time-input" name="time" value="<?=$row['event_time'];?>"required>
                                </div>
                            </div>

                            <button type="submit">Lähetä</button>
                            
                            <!-- - - - END - - - -->
                        <?php endwhile ?>
                    <?php endif ?>
                <?php endif ?>
        </form>

        <?php 
            $params = [];
            $types = "";
                
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if (!isset($_POST['id'])) {
                    die("Missing ID");
                }
                    
                /* prepared statement to prevent sql injection */
                $stmt = $conn->prepare("UPDATE events SET event_name=?, event_type=?, event_date=?, event_time=?, event_image=?, description=?, age_limit=?, location=?, max_visitors=? WHERE id=?;");
            
                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $types = "sissssssii"; // i = int, s = string (must match order!)
                
                /* picture handling - START */

                $uploadFolder = "kuvat/tapahtumaKuvat/";
                $allowedTypes = ['jpg','jpeg','png','gif','webp'];

                $currentImage = isset($_POST['current_image']) ? basename($_POST['current_image']) : "";  //the value of the previous image from the hidden input. If it's empty, leave it empty, othervise save the name of the file
                $newImage = $currentImage;  //content of this variable will be saved into database
                $uploadOk = 1;  //variable to check for mistakes

                /* USER UPLOADED NEW IMAGE */
                if (!empty($_FILES['eventPicture']['name'])) {

                    /* delete previous image */
                    if (!empty($currentImage) && $currentImage !== "noImage.png") {
                        $oldPath = $uploadFolder . $currentImage;

                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }

                    /* validate upload */
                    if ($_FILES['eventPicture']['error'] !== UPLOAD_ERR_OK) {
                        $uploadOk = 0;
                        echo "Upload error.";
                    }

                    $check = getimagesize($_FILES['eventPicture']['tmp_name']);
                    if ($check === false) {
                        $uploadOk = 0;
                        echo "File is not an image.";
                    }

                    $imageFileType = strtolower(pathinfo($_FILES["eventPicture"]["name"], PATHINFO_EXTENSION));

                    if (!in_array($imageFileType, $allowedTypes)) {
                        $uploadOk = 0;
                        echo "Invalid file type.";
                    }

                    /* save file */
                    if ($uploadOk === 1) {

                        $newFileName = round(microtime(true)) . "." . $imageFileType;  //can use uniqid('', true) instead of round(microtime(true)), so it will be 100% unique, but it looks messy and I'm not sure if it's really necessary
                        $targetPath = $uploadFolder . $newFileName;

                        if (move_uploaded_file($_FILES['eventPicture']['tmp_name'], $targetPath)) {
                            $newImage = $newFileName;
                        } else {
                            echo "Error moving uploaded file.";
                            $newImage = $currentImage;
                        }
                    }
                }

                /* USER REMOVED IMAGE */
                elseif ($currentImage === "noImage.png") {

                    $originalImage = basename($_POST['original_image']);

                    if (!empty($originalImage) && $originalImage !== "noImage.png") {

                        $oldPath = $uploadFolder . $originalImage;

                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }

                    $newImage = "";
                }
                /* NO CHANGE */
                else {
                    $newImage = $currentImage;
                }
                /* picture handling - END */


                /* parameters for prepared statement */
                $params = [
                    $_POST['name'],
                    (int)$_POST['eventType'],
                    $_POST['date'],
                    $_POST['time'],
                    $newImage,
                    $_POST['description'],
                    $_POST['ageLimit'],
                    $_POST['adress'],
                    (int)$_POST['maxplaces'],
                    (int)$_POST['id']
                ];

                /* convert to references */
                $tmp = [];
                foreach ($params as $key => $value) {
                    $tmp[$key] = &$params[$key];
                }
                    
                if (!$stmt->bind_param($types, ...$tmp)) {
                    die("Bind param failed: " . $stmt->error);
                }
                
                echo var_dump($stmt);

                if ($stmt->execute()) {
                    echo "<h1>Success!</h1>";
                    header("Location: tapahtumat.php");
                } else {
                    echo "<h1>Error: " . $stmt->error . "</h1>";
                } 
            }
        ?>
    </div>

</main>
<?php include 'include/footer.php'; ?>

