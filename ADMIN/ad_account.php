<?php
    include '../include/configuration.php';

    /* if the user is not registered, redirect to the login page */
    if (!isset($_SESSION['user_id'])) {
        header("Location: $baseUrl/login.php");
        exit();
    }

    $extraCSS = $baseUrl . "/CSS/account.css";
    $extraJS = $baseUrl . "/JavaScript/account.js";

    include '../include/header.php';

    /* logout handling */
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        session_unset();  // remove all session variables
        session_destroy();  //destroy the session
        header("Location: $baseUrl/index.php");
        exit();
    }

    $userID = (int)($_SESSION['user_id']);  // registered user's id

    
    $deleteAccountBtn = ""; 
    /* if the user is not the admin, redirect to the user account page */
    if ($userID != 1) {
       header("Location: {$baseUrl}/USER/account.php");
       exit();
    }

    $pageTitle = "Ad_account";
    


    /* retrieve all information about the user from the database */
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?;");
    $stmt->execute([$userID]);


    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $name = !empty($user['username']) ? htmlspecialchars($user['username']) : '';
    $email = !empty($user['email']) ? htmlspecialchars($user['email']) : '';

    $hasRows = false;  //check if there's any output
?>

<!-- - - - H T M L - - - -->
<main class="ad_account_page">
    
    <div class="wrapper">


        <div class="profile info">
            
            <form id="profile" method="post" action="changeInfo.php"  enctype="multipart/form-data">
                    
                <h1>Tilitietosi</h1>
                <!-- inputs for personal information (email and username) -->
                <div class="personalInfo">
                    <label for="name-input">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M367-527q-47-47-47-113t47-113q47-47 113-47t113 47q47 47 47 113t-47 113q-47 47-113 47t-113-47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                    </label>
                    <input type="text" id="name-input" name="name" value="<?=$name?>" placeholder="Nimi" readonly required>
                </div>

                <div class="personalInfo">
                    <label for="email-input">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480v58q0 59-40.5 100.5T740-280q-35 0-66-15t-52-43q-29 29-65.5 43.5T480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480v58q0 26 17 44t43 18q26 0 43-18t17-44v-58q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93h200v80H480Zm85-315q35-35 35-85t-35-85q-35-35-85-35t-85 35q-35 35-35 85t35 85q35 35 85 35t85-35Z"/></svg>
                    </label>
                    <input type="email" id="email-input" name="email" value="<?=$email?>" placeholder="Sähköpostiosoite" readonly required>
                </div>
                
                <!-- input for password reset -->
                <div class="passwordReset">
                    <label for="oldPassword-input">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm296.5-223.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                    </label>
                    <input type="password" id="oldPassword-input" name="oldPassword" value="" placeholder="Vanha salasana" readonly disabled>
                </div>

                <div class="passwordReset">
                    <label for="newPassword-input">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm296.5-223.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                    </label>
                    <input type="password" id="newPassword-input" name="newPassword" value="" placeholder="Uusi salasana" readonly disabled>
                </div>

                <div class="passwordReset">
                    <label for="newPasswordRepeat-input">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm296.5-223.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                    </label>
                    <input type="password" id="newPasswordRepeat-input" name="newPasswordRepeat" value="" placeholder="Toista uusi salasana" readonly disabled>
                </div>

                <div class="form-buttons">
                    <button type="button" id="edit-btn" onclick="toggleEdit()">Muokkaa</button>
                    <button type="submit" id="save-btn" style="display: none;">Tallenna</button>
                    <button type="button" id="pswrd-btn" onclick="changePasswordForm()">Vaihda salasana</button>
                    <button type="button" id="persInfo-btn" onclick="changePersonalInfoForm()">Muokkaa henkilötietoja</button>
                </div>

            </form>
            <?php
            /* show the mesage if update was successful */         
                if (isset($_SESSION['success_message'])) {
                    echo '<p class="success-msg">'.$_SESSION['success_message'].'</p>';
                    unset($_SESSION['success_message']);  // remove it from session so it only appears once
                }
            ?>

            <div class="account-actions">
                <a class="button logout" href="ad_account.php?action=logout">Kirjaudu ulos</a>
                <?php echo $deleteAccountBtn ?>
            </div>

            <div class="reservations info">

                <?php
                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) {

                    echo <<<HTML
                        <!--___TEMPLATE FOR ADMIN'S ASKING FOR MORE PLACES___-->
                        <h1> Varauspyynnöt </h1>
                        HTML;

                    $eventID = "";  //initialize the variable for event's id
                    /* display requests for admin */
                    $event_info = $pdo->prepare("SELECT * FROM events WHERE id = ?;");
                    $stmt = $pdo->prepare("SELECT TO_CHAR(created_at, 'DD.MM.YYYY') AS created_at_date, EXTRACT(HOUR FROM created_at) AS created_hour, TO_CHAR(created_at, 'MI') AS created_minutes, user_id, event_id, places_amount, message, status FROM extra_bookings ORDER BY created_at ASC;");  //retrieve information about extra-bookings
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $hasRows = true;  //if while-loop is activated, then at least one row exists
                        $createdAt = $row['created_at_date'];  //date of the request
                        $createdAtHour = $row['created_hour'];  //hour of the request
                        $createdAtMinutes = $row['created_minutes'];  //minutes of the request
                        $userID = $row['user_id'];  //id of the user who made the request
                        $placesAmount = $row['places_amount'];
                        $eventID = $row['event_id'];
                        $event_info->execute([$eventID]);
                        while ($eventRow = $event_info->fetch(PDO::FETCH_ASSOC)) {
                            $description = $eventRow['description'];
                            $eventName = $eventRow['event_name'];
                            $location = $eventRow['location'];
                        }

                        echo <<<HTML
                            <div class="requested">
                                    <div class="details-ad">

                                        <p class="request"> Varauspyyntö $placesAmount paikasta</p>

                                        <div class="eventinfocard">
                                            <p class="eventname-ad">
                                                $eventName
                                            </p>    
                                            <p class="time-ad">
                                                    Lähetetty <time>$createdAt $createdAtHour:$createdAtMinutes</time>
                                            </p>
                                            <p class="icon-adress"> <!-- same class as user type  -->
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#1f1f1f">
                                                    <path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z"/><circle cx="12" cy="9" r="2.5"/>
                                                </svg> 
                                                $location
                                            </p>
                                        </div>
                                                                        
                                    </div>
                                    <div class="change">
                                        <a class="button confirm" href="{$baseUrl}/bookEvent.php?id={$eventID}">Avaa pyyntö</a>
                                    </div>

                                </div>
                            <!--___TEMPLATE FOR ADMIN'S ASKING FOR MORE PLACES___-->
                            HTML;
                    }

                    if (!$hasRows) {
                        echo <<<HTML
                            <div class="none-requested">
                                <p>Чувапчичи у тебя запросов нет.<br> <a href="{$baseUrl}/ADMIN/ad_tapahtumat.php">Kaikki tapahtumat</a> </p>
                            </div>
                        HTML;
                    } else {
                        echo <<<HTML
                            <a class="button"href="{$baseUrl}/ADMIN/ad_tapahtumat.php">Kaikki tapahtumat</a> <!-- maybe it can be some other color and/or layout, now it's added to just be at least a little visible (otherwise there's no links for getting back to tapahtumat for users) -->
                        HTML;
                    }

                }
                ?>
            </div>
        </div>

    </div>


</main>   
<?php include '../include/footer.php'; ?>