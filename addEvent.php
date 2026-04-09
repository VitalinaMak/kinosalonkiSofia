<?php 
    $pageTitle = "AddEvent";
    $extraCSS = "CSS/add_edit_event.css";
    $extraJS = "JavaScript/add_edit_event.js";
    include 'include/header.php';

    /* ini_set('display_errors', 1);
    error_reporting(E_ALL); */
?>

<main class="addEvent_page">
    <div class="wrapper">
        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
            <form method="post" enctype="multipart/form-data" class="addEvent form" id="addEventForm">
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

                    <div class="fileInputField"> <!-- Фото -->
                        <label for="eventPicture-input"> 
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M440-440ZM120-120q-33 0-56.5-23.5T40-200v-480q0-33 23.5-56.5T120-760h126l74-80h240v80H355l-73 80H120v480h640v-360h80v360q0 33-23.5 56.5T760-120H120Zm640-560v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80ZM440-260q75 0 127.5-52.5T620-440q0-75-52.5-127.5T440-620q-75 0-127.5 52.5T260-440q0 75 52.5 127.5T440-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Z"/></svg>
                            <span class="label-text">Tapahtuman kuva</span>
                        </label> 
                            <input type="file" id="eventPicture-input" name="eventPicture" hidden>  <!-- an actual file input, but it's hidden -->
                            <input type="hidden" name="remove_image" value="0">  <!-- a flag that signals if the user clicked “remove image”  -->
                            <img id="preview" style="display:none;"> <!-- preview -->
                            <a style="display:none;" class="button" id="remove-button" href="javascript:void(0)" onclick="removeImage()">Poistaa kuvaa</a>  <!-- a link to remove the picture -->
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
                            <input type="number" id="maxplaces-input" name="maxplaces" placeholder="Max. osallistujamäärä: 24" required>
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
            <!-- <div id="message" style="display:none;">
                <p></p>
            </div> -->
        <?php endif ?>
    </div>



</main>

<?php include 'include/footer.php'; ?>

