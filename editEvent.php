<?php 
    $pageTitle = "EditEvent";
    $extraCSS = "CSS/add_edit_event.css";
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
                        $ageLimit = $row['age_limit']; ?>
                            <input type="hidden" name="id" value="<?=$row['id'];?>" />  <!-- id (id doesn't have to be changed, it's here only to save it's value) -->
                            <!-- - - - HTML - - - -->
                            <div class="column1">
                                <div> <!-- Название -->
                                    <label for="name-input"></label> 
                                    <input type="text" id="name-input" name="name" value="<?=htmlspecialchars($row['event_name']);?>" required minlength="2">
                                </div>
                                <div> <!-- Фото -->
                                    <label for="eventPicture-input"> Lataa tapahtuman kuva! </label> <br>
                                    <input type="file" id="eventPicture-input" name="eventPicture" value="<?=htmlspecialchars($row['event_image']);?>">
                                </div>
                                <div> <!-- Описание -->
                                    <label for="description-input"></label> 
                                    <textarea type="text" id="description-input" name="description" required><?=htmlspecialchars($row['description']);?></textarea>
                                </div>
                                <div> <!-- Type -->
                                    <label for="eventType-input"></label> 
                                    <select id="eventType-input" name="eventType" required>
                                        <option value="option1" <?= $eventType == 'option1' ? 'selected' : '' ?>>Elokuvaesitys</option>
                                        <option value="option2" <?= $eventType == 'option2' ? 'selected' : '' ?>>Tapahtuma, jossa on rajattu osalisujamäärä</option>
                                        <option value="option3" <?= $eventType == 'option3' ? 'selected' : '' ?>>Tapahtuma, jossa on rajaton osalisujamäärä</option>
                                    </select>
                                </div>
                                <div> <!-- Места -->
                                    <label for="maxplaces-input">Max. osallistujamäärä: </label> <br>
                                    <input type="text" id="maxplaces-input" name="maxplaces" value="<?=htmlspecialchars($row['max_visitors']);?>" required>
                                </div>
                            </div>
                            <div class="column2">
                                <div> <!-- Place -->
                                    <label for="adress-input"></label> 
                                    <input type="text" id="adress-input" name="adress" value="<?=htmlspecialchars($row['location']);?>" autocomplete="street-address" required>
                                </div>
                                <div> <!-- Date -->
                                    <label for="date-input"></label>
                                    <input type="date" id="date-input" name="date" value="<?=$row['event_date'];?>" required>
                                </div>
                                <div> <!-- Time -->
                                    <label for="time-input"></label>
                                    <input type="time" id="time-input" name="time" value="<?=$row['event_time'];?>"required>
                                </div>
                                <div> <!-- Age limit -->
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
                                <button type="submit">Lähetä</button>
                            </div>
                            
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
                
                /* picture handling */
                $uploadFileName = ""; // default if no file uploaded
                if (!empty($_FILES['eventPicture']['name'])) {
                    $uploadDir = "uploads/"; // folder where files are saved
                    $uploadFileName = basename($_FILES['eventPicture']['name']); // only the file name
                    /* $uploadPath = $uploadDir . $uploadFileName; // full path to save the file

                    move_uploaded_file($_FILES['eventPicture']['tmp_name'], $uploadPath); */
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

