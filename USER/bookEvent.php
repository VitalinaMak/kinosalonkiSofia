<?php 
    $pageTitle = "BookEvent";
    $extraCSS = "/kinosalonkiSofia/CSS/book_event.css";
    $extraJS = "/kinosalonkiSofia/JavaScript/bookEvent.js";
    include '../include/header.php'; 

    if (!isset($_GET['id'])) {
        die("Event ID is missing");
    }
    $eventID = (int)$_GET['id'];  //id of the event - cast to int for safety

    $user = $_SESSION['user_id'] ?? null;  //user's id
    $accountUrl = $user === 1 ? 'ADMIN/ad_account.php' : ($user ? 'USER/account.php' : 'login.php');

    /* retrieve all information about the event from the database */
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$eventID]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    /* check if event exists */
    if (!$event) {
        die("Event not found");
    }

    $ageLimit = ($event['age_limit']=="Ei luokiteltu") ? "" : " (".$event['age_limit'].")";  //if the age limit is defined, it appears in parenthesis after the name of the event

    $eventType = $event['event_type'];  //type of event
    $maxVisitors = $event['max_visitors'];  //max. amount of visitors for that event
    $eventName = $event['event_name'];  //event's name

    $stmt = $pdo->prepare("SELECT user_id, event_id, seat_number FROM bookings WHERE event_id = ?");
    $stmt->execute([$eventID]);

    $bookings = [];  //an array with numbers of all booked seats by others
    $userAlreadyBooked = 0;  //counter to check if the user has already booked any seats for that event before
    $editMode = false; //if the user came from the account.php, turn on the edit mode; otherwise, it stays false
    $seats = "";
    $usersSeats = [];  //an array with numbers of user's current seats

    /* check if the URL contains information about amount of bookings for that user (it might be passed from the account.php if the user clicked on change button) */
    if (isset($_GET['total'])) {
        $editMode = true;  //turn on the edit mode
        /* $userAlreadyBooked = (int)$_GET['total']; */

    } 
    /* also check if there any seats booked (only for event type 1) and assign it to a string variable */
    if (isset($_GET['seats'])) {
        $editMode = true;  //turn on the edit mode
        $seats = ($_GET['seats']);
    }
    /* retrieve from the database all booked seat numbers */
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        /* if ($row['user_id'] == $user) {
            if (str_contains($seats, $row['seat_number'])) {  //if the user came from the account.php and has already booked some seats, mark those seats as user's seats (to show them as selected in the seating chart)
                $usersSeats[] = $row['seat_number'];
            }
            $userAlreadyBooked += 1;
        } else {
            $bookings[] = $row['seat_number'];
        } */
        if ($row['user_id'] == $user) {  //
            $usersSeats[] = (int)$row['seat_number'];
            $userAlreadyBooked++;
        } else {
            $bookings[] = (int)$row['seat_number'];
        }
    }

    $bookedSeatsAmount = count($bookings);  //amount of booked seats
?>

<script>
    const editMode = <?= json_encode($editMode) ?>;  //send the edit mode marker to JavaScript
    const bookedSeats = <?= json_encode($bookings) ?>;  //send the array with booked seats to JavaScript
    let userAlreadyBooked = Number(<?= json_encode($userAlreadyBooked) ?>);  //send to JS amount of bookings user already did for this event
    const usersSeats = <?= json_encode($usersSeats) ?>;  // //send the array with user's seats to JavaScript
    console.log("usersSeats: ",usersSeats);
    console.log("bookedSeats: ", bookedSeats);
</script>

<main class="bookEvent_page">
    <h1><?= htmlspecialchars($event['event_name'].$ageLimit) ?></h1>

    <div class="wrapper"> 

        <div class="eventDetails column">
            <!-- elokuvan info + physical seats:
             1.
                - title + age-restriction
                - time
                - description
                - image??
                - type of event?

            2. if the event type is 1 (movie), show the table with seats, if the event type is 2 (limited amount of places), show the amount of places left, if the event type is 3 (unlimited amount of places), show the total number of participants
            -->
            <?php if (!empty($event["event_image"])): ?>    
                <img src="<?= $baseUrl ?>/kuvat/tapahtumaKuvat/<?= $event['event_image'] ?>" alt="Event Image">  <!-- event's image (if it exists) -->
            <?php endif ?>
            <p><?= $event['description'] ?></p>  <!-- Here add all the details about the event - image, decription etc. onclick on place it checks if you're logged in, and if not - sends you to SIGN UP page! -->

            <div class="booking">
                <?php if ($eventType == '1'): ?>
                    <!-- table for seating chart (visible only if event type is 1 (movie) -->
                    <table><tbody>
                        <caption> näytto </caption>
                        <tr>
                            <td id="1" <?= in_array(1, $usersSeats) ? "class='selected'" : (in_array(1, $bookings) ? "class='booked'" : "");?> onclick="selection('1')"></td>
                            <td id="2" <?= in_array(2, $usersSeats) ? "class='selected'" : (in_array(2, $bookings) ? "class='booked'" : "");?> onclick="selection('2')"></td>
                            <td id="3" <?= in_array(3, $usersSeats) ? "class='selected'" : (in_array(3, $bookings) ? "class='booked'" : "");?> onclick="selection('3')"></td>
                            <td id="4" <?= in_array(4, $usersSeats) ? "class='selected'" : (in_array(4, $bookings) ? "class='booked'" : "");?> onclick="selection('4')"></td>
                            <td id="5" <?= in_array(5, $usersSeats) ? "class='selected'" : (in_array(5, $bookings) ? "class='booked'" : "");?> onclick="selection('5')"></td>
                            <td id="6" <?= in_array(6, $usersSeats) ? "class='selected'" : (in_array(6, $bookings) ? "class='booked'" : "");?> onclick="selection('6')"></td>
                        </tr>
                        <tr>
                            <td id="7" <?= in_array(7, $usersSeats) ? "class='selected'" : (in_array(7, $bookings) ? "class='booked'" : "");?> onclick="selection('7')"></td>
                            <td id="8" <?= in_array(8, $usersSeats) ? "class='selected'" : (in_array(8, $bookings) ? "class='booked'" : "");?> onclick="selection('8')"></td>
                            <td id="9" <?= in_array(9, $usersSeats) ? "class='selected'" : (in_array(9, $bookings) ? "class='booked'" : "");?> onclick="selection('9')"></td>
                            <td id="10" <?= in_array(10, $usersSeats) ? "class='selected'" : (in_array(10, $bookings) ? "class='booked'" : "");?> onclick="selection('10')"></td>
                            <td id="11" <?= in_array(11, $usersSeats) ? "class='selected'" : (in_array(11, $bookings) ? "class='booked'" : "");?> onclick="selection('11')"></td>
                            <td id="12" <?= in_array(12, $usersSeats) ? "class='selected'" : (in_array(12, $bookings) ? "class='booked'" : "");?> onclick="selection('12')"></td>
                        </tr>
                        <tr>
                            <td id="13" <?= in_array(13, $usersSeats) ? "class='selected'" : (in_array(13, $bookings) ? "class='booked'" : "");?> onclick="selection('13')"></td>
                            <td id="14" <?= in_array(14, $usersSeats) ? "class='selected'" : (in_array(14, $bookings) ? "class='booked'" : "");?> onclick="selection('14')"></td>
                            <td id="15" <?= in_array(15, $usersSeats) ? "class='selected'" : (in_array(15, $bookings) ? "class='booked'" : "");?> onclick="selection('15')"></td>
                            <td id="16" <?= in_array(16, $usersSeats) ? "class='selected'" : (in_array(16, $bookings) ? "class='booked'" : "");?> onclick="selection('16')"></td>
                            <td id="17" <?= in_array(17, $usersSeats) ? "class='selected'" : (in_array(17, $bookings) ? "class='booked'" : "");?> onclick="selection('17')"></td>
                            <td id="18" <?= in_array(18, $usersSeats) ? "class='selected'" : (in_array(18, $bookings) ? "class='booked'" : "");?> onclick="selection('18')"></td>
                        </tr>
                        <tr>
                            <td id="19" <?= in_array(19, $usersSeats) ? "class='selected'" : (in_array(19, $bookings) ? "class='booked'" : "");?> onclick="selection('19')"></td>
                            <td id="20" <?= in_array(20, $usersSeats) ? "class='selected'" : (in_array(20, $bookings) ? "class='booked'" : "");?> onclick="selection('20')"></td>
                            <td id="21" <?= in_array(21, $usersSeats) ? "class='selected'" : (in_array(21, $bookings) ? "class='booked'" : "");?> onclick="selection('21')"></td>
                            <td id="22" <?= in_array(22, $usersSeats) ? "class='selected'" : (in_array(22, $bookings) ? "class='booked'" : "");?> onclick="selection('22')"></td>
                            <td id="23" <?= in_array(23, $usersSeats) ? "class='selected'" : (in_array(23, $bookings) ? "class='booked'" : "");?> onclick="selection('23')"></td>
                            <td id="24" <?= in_array(24, $usersSeats) ? "class='selected'" : (in_array(24, $bookings) ? "class='booked'" : "");?> onclick="selection('24')"></td>
                        </tr>
                    </tbody></table>
                <?php elseif ($eventType == '2'): ?>
                    <p>Paikkoja jäljellä: <span id="placesLeft"><?=$maxVisitors - $bookedSeatsAmount?></span></p>  <!-- if event type is 2 (limited amount of places), show the amount of places left -->
                <?php else: ?>
                    <p>Ilmoittautuneiden määrä: <span id="bookedCount"><?=$bookedSeatsAmount?></span></p>  <!-- if event type is 3 (unlimited amount of places), show the total number of participants-->
                <?php endif; ?>

                <div class="bookingInfo">
                    <p id="showSelectedSeats"></p>
                    <form id="bookingForm" onsubmit="return validateForm()">
                        <input type="hidden" id="checkLogin" name="checkLogin" value="<?php echo ($user) ? 1 : 0; ?>">  <!-- an input to check if the user logged in -->
                        <input type="hidden" id="selectedSeatsInput" name="seats">  <!-- an input to store the ID's of selected seats -->
                        <input type="hidden" id="typeOfEvent" name="typeOfEvent" value="<?=$eventType?>">  <!-- an input to store event's type -->
                        <input type="submit" name="submit" class="button" value="Vahvista varaus">
                    </form>

                    <p id="message"></p>

                    <!-- buttons with links to tapahtumat.php and account.php. If the edit-mode is on, both of them are displayed and the text of the second button is changed to "Kaikki tapahtumat", otherwise only the button for tapahtumat.php -->
                    <div class="links">
                        <a id="backToAccount" href="<?= $baseUrl ?>/<?= $accountUrl ?>" class="backToAccount">Takaisin tilisivulle</a>
                        <a id="backToEvents" href="<?= $baseUrl ?>/USER/tapahtumat.php" class="backToEvents">Takaisin tapahtuma-sivulle</a>
                    </div>
                </div>
            </div>

            <p>Huom! Yhdellä tunnuksella voi varata enintään 2 paikkaa. Mikäli haluat varata useampia paikkoja, olethan yhteydessä yhdistykseen, jonka kautta se on mahdollista.</p>  <!-- paste here phone number or email, idk, on their webpage they say they don't accept reservations via email, phone or social media -->
            <button onclick="revealTheForm()">Varaa enemmän paikkoja</button>


            <form class="morePlaces" method="POST" action="" id="morePlaces" style="display: none">

                <h2>Täytä lomake</h2>

                <input type="hidden" id="user_id-input" name="user_id" value="<?php echo htmlspecialchars($user); ?>">  <!-- pass the user's id to the form -->
                <input type="hidden" id="event_id-input" name="event_id" value="<?php echo htmlspecialchars($eventID); ?>">  <!-- pass the event's id to the form -->
                <div>
                    <label for="name-input"></label>
                    <input type="text" id="name-input" name="name" placeholder="Etunimi ja sukuninmi" required>
                </div>
                <div>
                    <label for="email-input"></label>
                    <input type="email" id="email-input" name="email" placeholder="Sähköpostiosoite" required> <!-- may do autofill for email and maybe name, but it requires one more sql-query to retrive it from DB... -->
                </div>
                <div>
                    <label for="phone-input"></label>
                    <input type="tel" id="phone-input" name="phone" placeholder="Puhelinnumero" required>
                </div>
                <div>
                    <label for="places-input"></label>
                    <input type="number" id="places-input" name="places" placeholder="Paikkojen määrä" required>
                </div>
                <div>
                    <label for="comment-input"></label>
                    <textarea type="comment" id="comment-input" name="comment" placeholder="Lisähuomautukset"></textarea>
                </div>
                <input type="hidden" name="eventName" value="<?php echo htmlspecialchars($eventName); ?>">  <!-- pass the name of event as well -->
                <button type="submit" class="">Lähettää</button>
                
            </form>

            <a id="backToEvents2" href="{$baseUrl}/USER/tapahtumat.php" class="backToEvents">Takaisin tapahtuma-sivulle</a>

            </div>
            
            <div class="moreDetails column">
                <!-- book more physical seats  -->
                    
            </div>
    </div>

</main>  

<?php include '../include/footer.php'; ?>