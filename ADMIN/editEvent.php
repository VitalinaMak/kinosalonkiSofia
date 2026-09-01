<?php 
    $pageTitle = "EditEvent";
    $extraCSS = "/kinosalonkiSofia/CSS/add_edit_event.css";
    $extraJS = "/kinosalonkiSofia/JavaScript/add_edit_event.js";
    include '../include/header.php';
    
    /* delete event (it has to be placed before any output) */
    if (isset($_GET['delete']) && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?;");
        $id = (int) $_GET['id'];
        $stmt->execute([$id]);
        header("Location: $baseUrl/ADMIN/ad_tapahtumat.php");
        exit();
    }
?>

<main class="editEvent_page">
    <div class="wrapper">
    
        <form action="editEvent.php" method="post"  enctype="multipart/form-data" class="editEvent">

            <div class="title-link">
                <h1> Edit event</h1>
                <a href="editEvent.php?id=<?php echo $_GET['id']; ?>&delete=true"
                    class="button-danger"
                    onclick="return confirm('Are you sure you want to delete this event?');">
                    Poista
                </a>
            </div>
            <?php
                if ($_SERVER['REQUEST_METHOD'] !== 'POST'):
                    $ehto = (int)$_GET['id'];  //get id from the URL
                    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");  //retrieve event data from the database to fill the form with it
                    $stmt->execute([$ehto]);

                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                        $eventType = $row['event_type'];
                        $ageLimit = $row['age_limit'];
                        $picture = !empty($row['event_image']) ? $row['event_image'] : "noImage.png"; ?>

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
                                <label for="eventPicture-input"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M440-440ZM120-120q-33 0-56.5-23.5T40-200v-480q0-33 23.5-56.5T120-760h126l74-80h240v80H355l-73 80H120v480h640v-360h80v360q0 33-23.5 56.5T760-120H120Zm640-560v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80ZM440-260q75 0 127.5-52.5T620-440q0-75-52.5-127.5T440-620q-75 0-127.5 52.5T260-440q0 75 52.5 127.5T440-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Z"/></svg>
                                   
                                    <span class="label-text">Tapahtuman kuva</span> 

                                    <input type="file" id="eventPicture-input" name="eventPicture" hidden>  <!-- input-field for the image. It's accessed through the label but it cannot contain information about the previous image-->
                                    <input type="hidden" name="current_image" value="<?= htmlspecialchars($row['event_image']); ?>">  <!-- ..so here is another input (basicaly it keeps track of what the user currently sees) -->
                                    <input type="hidden" name="original_image" value="<?= htmlspecialchars($row['event_image']); ?>"> <!-- and one more hidden input to store the original image from the database -->
                                    <input type="hidden" name="remove_image" value="0">  <!-- a flag that signals if the user clicked “remove image”  -->
                                    <img id="preview" src="<?= $baseUrl ?>/kuvat/tapahtumaKuvat/<?= htmlspecialchars($picture) ?>" alt="Uploaded Image">
                                    <a id="remove-button" class="button" href="javascript:void(0)" onclick="removeImage()"> Poistaa kuvaa</a>  <!-- a link to remove the picture -->
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
                                    <label for="maxplaces-input"> </label> <br>
                                    <input type="number" id="maxplaces-input" name="maxplaces" placeholder="Max. osallistujamäärä" value="<?=htmlspecialchars($row['max_visitors']);?>" required>
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
                $stmt = $pdo->prepare("UPDATE events SET event_name=?, event_type=?, event_date=?, event_time=?, event_image=?, description=?, age_limit=?, location=?, max_visitors=? WHERE id=?;");
                
                /* picture handling - START */

                $uploadFolder = __DIR__ . "/../kuvat/tapahtumaKuvat/";
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
                            if ($currentImage !== "noImage.png") {  //check one more time that it's not the default image, I don't trust this code anymore
                                unlink($oldPath);
                            }
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
                elseif (isset($_POST['remove_image']) && $_POST['remove_image'] === "1") {

                    $originalImage = basename($_POST['original_image']);

                    if (!empty($originalImage) && $originalImage !== "noImage.png") {

                        $oldPath = $uploadFolder . $originalImage;

                        if (file_exists($oldPath)) {
                            if ($originalImage !== "noImage.png") {  //once again check if the program SURE it's not the default picture it's going to delete
                                unlink($oldPath);
                            }
                        }
                    }

                    $newImage = "";  //leaves image-field empty (the default picture should never be passed to the datebase)
                }
                /* NO CHANGE */
                else {
                    $newImage = $currentImage;  // keeps current image as is
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
                    
                try {
                    if ($stmt->execute($params)) {
                        header("Location: $baseUrl/ADMIN/ad_tapahtumat.php");
                        exit();
                    }
                } catch (PDOException $e) {
                    echo "<h1>Error: " . $e->getMessage() . "</h1>";
                }
            }
        ?>
    </div>

</main>
<?php include '../include/footer.php'; ?>

