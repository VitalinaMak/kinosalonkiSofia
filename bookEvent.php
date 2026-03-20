<?php 
    $pageTitle = "BookEvent";
    $extraCSS = "CSS/book_event.css";
    $extraJS = "JavaScript/bookEvent.js";
    include 'include/header.php'; 

    if (!isset($_GET['id'])) {
        die("Event ID is missing");
    }
    $eventID = $_GET['id'];  //id of the event

    $user = $_SESSION['user_id'] ?? null;  //user's id

    /* retrieve all information about the event from the database */
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("s", $eventID);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    $ageLimit = ($event['age_limit']=="Ei luokiteltu") ? "" : " (".$event['age_limit'].")";  //if the age limit is defined, it appears in parenthesis after the name of the event

    /* retrieve information about booked setas for that event */
    $stmt = $conn->prepare("SELECT user_id, event_id, seat_number FROM bookings WHERE event_id = ?");
    $stmt->bind_param("s", $eventID);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];  //an array with numbers of all booked seats
    $userAlreadyBooked = 0;  //counter to check if the user has already booked any seats for that event before

    while ($row = $result->fetch_assoc()) {
    $bookings[] = $row['seat_number']; 

    if ($row['user_id'] == $user) {
        $userAlreadyBooked += 1;
    }
}
?>

<script>
    const bookedSeats = <?= json_encode($bookings) ?>;  //send the array with booked seats to JavaScript
    const userAlreadyBooked = <?= json_encode($userAlreadyBooked) ?>;  //send to JS amount of bookings user already did for this event
</script>

<main class="bookEvent_page">
    <h1><?= $event['event_name'].$ageLimit ?></h1>

    <div class="wrapper"> 

        <p><?= $event['description'] ?></p>  <!-- Here add all the details about the event - image, decription etc. onclick on place it checks if you're logged in, and if not - sends you to SIGN UP page! -->

        <div class="booking">
            <!-- table for seating chart -->
            <table><tbody>
                <caption> näytto </caption>
                <tr>
                    <td id="1" <?= in_array(1, $bookings) ? "class='booked'" : "";?> onclick="selection('1')"></td>
                    <td id="2" <?= in_array(2, $bookings) ? "class='booked'" : "";?> onclick="selection('2')"></td>
                    <td id="3" <?= in_array(3, $bookings) ? "class='booked'" : "";?> onclick="selection('3')"></td>
                    <td id="4" <?= in_array(4, $bookings) ? "class='booked'" : "";?> onclick="selection('4')"></td>
                    <td id="5" <?= in_array(5, $bookings) ? "class='booked'" : "";?> onclick="selection('5')"></td>
                    <td id="6" <?= in_array(6, $bookings) ? "class='booked'" : "";?> onclick="selection('6')"></td>
                </tr>
                <tr>
                    <td id="7" <?= in_array(7, $bookings) ? "class='booked'" : "";?> onclick="selection('7')"></td>
                    <td id="8" <?= in_array(8, $bookings) ? "class='booked'" : "";?> onclick="selection('8')"></td>
                    <td id="9" <?= in_array(9, $bookings) ? "class='booked'" : "";?> onclick="selection('9')"></td>
                    <td id="10" <?= in_array(10, $bookings) ? "class='booked'" : "";?> onclick="selection('10')"></td>
                    <td id="11" <?= in_array(11, $bookings) ? "class='booked'" : "";?> onclick="selection('11')"></td>
                    <td id="12" <?= in_array(12, $bookings) ? "class='booked'" : "";?> onclick="selection('12')"></td>
                </tr>
                <tr>
                    <td id="13" <?= in_array(13, $bookings) ? "class='booked'" : "";?> onclick="selection('13')"></td>
                    <td id="14" <?= in_array(14, $bookings) ? "class='booked'" : "";?> onclick="selection('14')"></td>
                    <td id="15" <?= in_array(15, $bookings) ? "class='booked'" : "";?> onclick="selection('15')"></td>
                    <td id="16" <?= in_array(16, $bookings) ? "class='booked'" : "";?> onclick="selection('16')"></td>
                    <td id="17" <?= in_array(17, $bookings) ? "class='booked'" : "";?> onclick="selection('17')"></td>
                    <td id="18" <?= in_array(18, $bookings) ? "class='booked'" : "";?> onclick="selection('18')"></td>
                </tr>
                <tr>
                    <td id="19" <?= in_array(19, $bookings) ? "class='booked'" : "";?> onclick="selection('19')"></td>
                    <td id="20" <?= in_array(20, $bookings) ? "class='booked'" : "";?> onclick="selection('20')"></td>
                    <td id="21" <?= in_array(21, $bookings) ? "class='booked'" : "";?> onclick="selection('21')"></td>
                    <td id="22" <?= in_array(22, $bookings) ? "class='booked'" : "";?> onclick="selection('22')"></td>
                    <td id="23" <?= in_array(23, $bookings) ? "class='booked'" : "";?> onclick="selection('23')"></td>
                    <td id="24" <?= in_array(24, $bookings) ? "class='booked'" : "";?> onclick="selection('24')"></td>
                </tr>
            </tbody></table>

            <div class="bookingInfo">
                <p id="showSelectedSeats"></p>
                <form id="bookingForm" onsubmit="return validateForm()">
                    <input type="hidden" id="checkLogin" name="checkLogin" value="<?php echo ($user) ? 1 : 0; ?>">  <!-- an input to check if the user logged in -->
                    <input type="hidden" id="selectedSeatsInput" name="seats">  <!-- an input to store the ID's of selected seats -->
                    <input type="submit" name="submit" value="Vahvista varaus">
                </form>

                

                <p id="message"></p>
            </div>
        </div>

        <p>Huom! Yhdellä tunnuksella voi varata enintään 3 paikkaa. Mikäli haluat varata useampia paikkoja, olethan yhteydessä yhdistykseen, jonka kautta se on mahdollista.</p>  <!-- paste here phone number or email, idk, on therir webpage they say they don't accept reservations via email, phone or social media -->

    </div>

</main>  
<?php include 'include/footer.php'; ?>

