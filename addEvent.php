<?php 
    $pageTitle = "AddEvent";
    $extraCSS = "CSS/add_edit_event.css";
    include 'include/header.php'; 
?>

<main class="addEvent_page">
    <div class="wrapper">
        <form class="addEvent form">
            <h1>Uusi tapahtuma</h1>
            
            <div class="column1">
                <div> <!-- Название -->
                    <label for=""></label> 
                    <input type="text" id="" name="" placeholder="Nimi" required minlength="2">
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
            </div>

            <div class="column2">
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
                <button type="submit">Submit</button>
            </div>
            
        </form>
    </div>

</main>

<?php include 'include/footer.php'; ?>

