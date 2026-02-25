<?php 
    $pageTitle = "EditEvent";
    include 'include/header.php'; 
?>
<main class="editEvent_page">
    <div class="wrapper">
    
        <form class="editEvent">
        <h1> Edit event</h1>
        <?php
            if (!isset($_POST['nimi'])) {

                $ehto = (int)$_GET['id'];  //get id from the URL
                $sql = "SELECT * FROM events WHERE id = $ehto";  //search for the event with that id in the DB
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<input type='hidden' name='id' value='".$row['id']."/>";  //id (it doesn't have to be changed, but we need it for the sql-query later)
                        echo "<div> <!-- Название -->
                            <label for=""></label> 
                            <input type='text' id='name-input' name='name' value='".$row['event-name']."' required minlength="2">
                            </div>"
                        echo "<div> <!-- Фото -->
                            <label for='eventPicture-input'> Lataa tapahtuman kuva! </label> <br>
                            <input type='file' id='eventPicture-input' name='eventPicture' value='".$row['event-image']."'>
                            </div>"
                        echo "<div> <!-- Описание -->
                            <label for='description-input'></label> 
                            <textarea type='text' id='description-input' name='description' value='".$row['description']." required></textarea>
                            </div>"
                        echo "<div> <!-- Place -->
                            <label for='adress-input'></label> 
                            <input type='text' id='adress-input' name='adress' value='".$row['location']." autocomplete='street-address' required>
                            </div>"
                        echo "<div> <!-- Date -->
                            <label for="date-input"></label>
                            <input type="date" id="date-input" name="date" required>
                            </div>"
                        <div> <!-- Time -->
                        <label for="time-input"></label>
                        <input type="time" id="time-input" name="time" required>
                        </div>
                        <div> <!-- Type -->
                        <label for="eventType-input"></label> 
                        <select id="eventType-input" name="select" required>
                        <option value="option1">Elokuvaesitys</option>
                        <option value="option2">Option 2</option>
                        <option value="option3">Option 3</option>
                        </select>
                        </div>
                        <div> <!-- Места -->
                        <label for="maxplaces-input">Max. osallistujamäärä: </label> <br>
                        <input type="text" id="maxplaces-input" name="maxplaces" placeholder="24" required>
                        </div>
                        <button type="submit">Submit</button>
                    }
                }
            }
            ?>
    </form>
</div>
    
</main>
<?php include 'include/footer.php'; ?>

