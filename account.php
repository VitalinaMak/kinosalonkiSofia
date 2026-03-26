<?php
    require_once 'include/configuration.php';  //connection to database and session start

    /* if the user is not registered, kick them out of here to the login page*/
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $userID = ($_SESSION['user_id']);  // registered user's id

    $pageTitle = "Account";
    $extraCSS = "CSS/account.css";
    $extraJS = "JavaScript/account.js";
    include 'include/header.php'; 

    /* retrieve all information about the user from the database */
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $name = $user['username'];
    $email = $user['email'];
?>
<!-- - - - H T M L - - - -->
<main class="account_page">
    
    <div class="wrapper">


        <div class="profile info">
            
            <form id="profile" method="post" action="account.php"  enctype="multipart/form-data">
                    
                <h1>Tilitietosi</h1>

                <div>
                    <label for="name-input">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M367-527q-47-47-47-113t47-113q47-47 113-47t113 47q47 47 47 113t-47 113q-47 47-113 47t-113-47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                    </label>
                    <input type="text" id="name-input" name="name" value=<?=$name?> placeholder="Nimi" readonly required>
                </div>

                <div>
                    <label for="email-input">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480v58q0 59-40.5 100.5T740-280q-35 0-66-15t-52-43q-29 29-65.5 43.5T480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480v58q0 26 17 44t43 18q26 0 43-18t17-44v-58q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93h200v80H480Zm85-315q35-35 35-85t-35-85q-35-35-85-35t-85 35q-35 35-35 85t35 85q35 35 85 35t85-35Z"/></svg>
                    </label>
                    <input type="email" id="email-input" name="email" value=<?=$email?> placeholder="Sähköpostiosoite" readonly required>
                </div>

                <div>
                    <label for="password-input">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm296.5-223.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                    </label>
                    <input type="password" id="password-input" name="password" value="********" placeholder="Salasana" readonly required>
                </div>

                <div class="form-buttons">
                    <button type="button" id="edit-btn" onclick="toggleEdit()">Muokkaa</button>
                    <button type="submit" id="save-btn" style="display: none;">Tallenna</button>
                </div>

                <button class="deleteaccount" type="button" id="deleteaccount-btn">Delete Account</button>
            </form>

        </div>

        <div class="reservations info">

            <h1> Varauksesi </h1>
                
            <div class="reserved">
                <div class="details">
                    <!-- ROW 1 -->
                    <p class="time-place">
                        <time class="time">03.02.2023 klo 15:00</time>
                        <data class="place">places 22, 24</data>
                    </p>
                    <!-- ROW 2 -->
                    <p class="eventname">
                        Seniorikino: TOTUUS ON ARMOTON (1963)
                        <data class="age" value="7">K7</data>
                    </p>
                    <!-- ROW 3 -->
                    <p class="icon-adress">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#1f1f1f">
                            <path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z"/><circle cx="12" cy="9" r="2.5"/>
                        </svg> 
                        Kinosalonki Sofia, Reiponkatu 35, Raahe
                    </p>                        
                </div>
                <div class="change">
                    <!-- REDACT THE LINKS TO CORRECT ONES -->
                    <!-- REDACT THE LINKS TO CORRECT ONES -->
                    <!-- REDACT THE LINKS TO CORRECT ONES -->
                    <a class="button edit" href="bookEvent.php">Muokka varaus</a>
                    <a class="button cancel" href="account.php">Peruuta varaus</a>
                </div>
            </div>
                 
            <div class="reserved">
                <div class="details">
                    <p class="time-place">
                        <time class="time">03.02.2023 klo 15:00</time>
                        <data class="place">places 22, 24</data>
                    </p>

                    <p class="eventname">
                        Un fatto di sangue nel comune di Siculiana fra due uomini per causa di una vedova 
                        <data class="age" value="7">K18</data>
                    </p>             
                    
                    <p class="icon-adress">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#1f1f1f">
                            <path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z"/><circle cx="12" cy="9" r="2.5"/>
                        </svg> 
                        Kinosalonki Sofia, Reiponkatu 35, Raahe
                    </p>                        
                </div>
                <div class="change">
                    <!-- REDACT THE LINKS TO CORRECT ONES -->
                    <!-- REDACT THE LINKS TO CORRECT ONES -->
                    <!-- REDACT THE LINKS TO CORRECT ONES -->
                    <a class="button edit" href="bookEvent.php">Muokka varaus</a>
                    <a class="button cancel" href="account.php">Peruuta varaus</a>
                </div>
            </div>
                
            <div class="none-reserved">
                <p>Sinulla ei ole aktiivisia varauksia. <a href="tapahtumat.php">Katso ohjelmisto</a> </p>
            </div>

        </div>

    </div>





</main>   
<?php include 'include/footer.php'; ?>