<?php 
    $pageTitle = "AddEvent";
    include 'include/header.php'; 
?>

<main class="addEvent_page">
    <h1>Uusi tapahtuma</h1>

        <form class="addEvent form">
            <div>
                <label for=""></label> 
                <input type="text" id="" name="" placeholder="Nimi" required minlength="2">
            </div>
            <div>
                <label for="eventType-input"></label> 
                <select id="eventType-input" name="select" required>
                <option value="option1">Elokuvaesitys</option>
                <option value="option2">Option 2</option>
                <option value="option3">Option 3</option>
                </select>
            </div>
            <div>
                <label for="description-input"></label> 
                <input type="text" id="description-input" name="description" placeholder="Kuvaus" required>
            </div>
            <div>
                <label for="eventPicture-input"> Lataa tapahtuman kuva! </label> <br>
                <input type="file" id="eventPicture-input" name="eventPicture">
            </div>
            <div>
                <label for="maxplaces-input">Max. osallistujamäärä: </label> <br>
                <input type="text" id="maxplaces-input" name="maxplaces" placeholder="24" required>
            </div>
            <button type="submit">Submit</button>
        </form>

</main>

<?php include 'include/footer.php'; ?>

