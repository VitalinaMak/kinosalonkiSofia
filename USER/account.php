<?php
    include '../include/configuration.php';

    /* if the user is not registered, redirect to the login page */
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../us_account.php");
        exit();
    }

    $extraCSS = $baseUrl . "/CSS/account.css";
    $extraJS = $baseUrl . "/JavaScript/account.js";

    include '../include/header.php';  //connection to database and session start

    /* logout handling */
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        session_unset();  // remove all session variables
        session_destroy();  //destroy the session
        header("Location: ../index.php");
        exit();
    }

    $userID = (int)($_SESSION['user_id']);  // registered user's id

    /*  if the user is the admin, redirect to the admin account page */
    if ($userID == 1) {
        header("Location: ../ADMIN/ad_account.php");
       exit();
    }

    $deleteAccountBtn = "<button class='deleteaccount' type='button' id='deleteaccount-btn'>Poista tili</button>";

    $pageTitle = "Us_account";

    /* retrieve all information about the user from the database */
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?;");
    $stmt->execute([$userID]);
    /* $stmt->execute();
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc(); */

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $name = !empty($user['username']) ? htmlspecialchars($user['username']) : '';
    $email = !empty($user['email']) ? htmlspecialchars($user['email']) : '';

    $hasRows = false;  //check if there's any output


    /* cancel reservation */
    if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = :id");

        if (!$stmt) {
            die("Prepare failed");
        }

        $id = (int) $_GET['id'];

        if ($stmt->execute(['id' => $id])) {
            header("Location: account.php");  //redirect to the same page
            exit();
        }
    }
?>

<!-- - - - H T M L - - - -->
<main class="account_page">
    
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
            
            <h3>Ilmoitusasetukset</h3>

            <div class="actions">
                <div class="checkboxes">
                    <input type="checkbox" id="notify-tapahtumat" name="notify_tapahtumat" checked>
                    <label for="notify-tapahtumat">Lähetä sähköpostia uusista elokuvista</label>
                </div>

                <div class="checkboxes">
                    <input type="checkbox" id="notify-reservations" name="notify_reservations" checked>
                    <label for="notify-reservations">Lähetä varausmuistutuksia</label>
                </div>
            </div>
            
            <div class="account-actions">
                <a class="button logout" href=<?= $baseUrl ?>/USER/account.php?action=logout>Kirjaudu ulos</a>
                <?php echo $deleteAccountBtn ?>
            </div>

            <!-- dialog for account deleting -->
            <div id="confirm-dialog" title="Poista tili" style="display:none;">
                <p>Haluatko varmasti poistaa tilisi? Tätä toimintoa ei voi peruuttaa.</p>
            </div>

            <!-- hidden form for account deleting (will be submitted only after the user presses "Kyllä" in the dialog window) -->
            <form id="delete-form" method="POST" action="deleteAccount.php" style="display:none;">
                <input type="hidden" name="delete_account" value="1">
            </form>

        </div>

        <?php
            /* retrieve all bookings made for user's id from the database */
            $sql = "
                SELECT 
                    events.event_name, 
                    events.event_type, 
                    TO_CHAR(events.event_date, 'DD.MM.YYYY') AS event_formatted_date,
                    EXTRACT(HOUR FROM events.event_time) AS event_hour,
                    TO_CHAR(events.event_time, 'MI') AS event_minute,
                    events.event_image, 
                    events.description, 
                    events.age_limit, 
                    events.location,
                    bookings.event_id, 
                    MIN(bookings.id) AS booking_id,
                    STRING_AGG(bookings.seat_number::text, ',') AS seats,
                    (
                        SELECT COUNT(*)
                        FROM bookings AS b2 
                        WHERE b2.event_id = events.id AND b2.user_id = :user_id
                    ) AS total
                FROM events
                JOIN bookings ON events.id = bookings.event_id
                WHERE bookings.user_id = :user_id
                GROUP BY events.id, bookings.event_id
                ORDER BY events.event_date, events.event_time;
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $userID]);

            

            $seatList ="";  //list of seat numbers
            $total = "";  //total amount of booked seats for the event

            echo "<h1>Varauksesi</h1>";
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $hasRows = true;  //if while-loop is activated, then at least one row exists
                $places = "";  //variable for the string with all reserved seats
                $total = $row['total'];  
                $ageLimit = "";
                
                /* assign value to $places only for the 1st type of event */
                if ($row['event_type'] == 1) {
                    $places = "Paikat ".$row['seats'];
                    $seatList = str_replace(',', '', $row['seats']);  //remove commas from the list of booked seats, so it can be passed to the url later
                }

                if ($row['age_limit'] != "Ei luokiteltu") {
                    $ageLimit = $row['age_limit'];  //if the event has age limitetion, assign it to variable; otherwise leave it empty
                }

            
                echo <<<HTML
                <!--___TEMPLATE FOR USER'S RESERVED EVENTS___-->
                
                <div class="reserved">
                    <div class="details">
                        <!-- ROW 1 -->
                        <p class="time-place">
                            <time class="time">{$row['event_formatted_date']} klo {$row['event_hour']}.{$row['event_minute']}</time>
                            <data class="place">{$places}</data>
                        </p>
                        <!-- ROW 2 -->
                        <p class="eventname">
                            {$row['event_name']}
                            <data class="age" value="{$row['age_limit']}">{$ageLimit}</data>
                        </p>
                        <!-- ROW 3 -->
                        <p class="icon-adress">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#1f1f1f">
                                <path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z"/><circle cx="12" cy="9" r="2.5"/>
                            </svg> 
                            {$row['location']}
                        </p>                        
                    </div>
                    <div class="change">
                        <a class="button edit" href="{$baseUrl}/bookEvent.php?id={$row['event_id']}&seats={$seatList}&total={$total}">Muokkaa varaus</a>
                        <a class="button cancel" href="{$baseUrl}/USER/account.php?id={$row['booking_id']}">Peruuta varaus</a>
                    </div>

                </div>
                <!--___TEMPLATE FOR USER'S RESERVED EVENTS___-->
                HTML;

                
            } 

            if (!$hasRows) {
                echo <<<HTML
                    <div class="none-reserved">
                        <p>Sinulla ei ole aktiivisia varauksia.<br> <a href="{$baseUrl}/tapahtumat.php">Katso ohjelmisto</a> </p>
                    </div>
                HTML;
            } else {
                echo <<<HTML
                    <a class="button" href="{$baseUrl}/tapahtumat.php">Kaikki tapahtumat</a> <!-- maybe it can be some other color and/or layout, now it's added to just be at least a little visible (otherwise there's no links for getting back to tapahtumat for users) -->
                HTML;
            }
        
                

        ?>

        </div>

    </div>


</main>   
<?php include '../include/footer.php'; ?>