console.log("JS loaded");

let selectedSeats = [];  //array for IDs of selected seats
let checkLogin = 0;  //variable to check if the user is logged in
let showBooking;  //variable to show booked seats
let eventType = 1;  //variable for event type
let limitAlertShown = false;  //flag to check if the allert was already shown

/* function start only when all elements on the page are loaded */
document.addEventListener("DOMContentLoaded", function () {
    
    eventType = Number(document.getElementById("typeOfEvent").value);  //get the type of event from the hidden input

    const checkLoginElement = document.getElementById("checkLogin");  //get information about user's logging in

    if (checkLoginElement) {
        checkLogin = checkLoginElement.value;
    } 

    /* if there's any seats booked by that user, add them to the array with selected seats */
    if (usersSeats.length >= 1) {
        for (let i=0; i<usersSeats.length; i++) {
            selectedSeats[i] = String(usersSeats[i]);
        }
    }
    console.log("selectedSeats after copying from usersSeats: ", selectedSeats);

    showBooking = document.getElementById("showSelectedSeats");  //<p>-element that stores message about seats selecting

    if (eventType == 1) {
        if (selectedSeats.length == 0) {
            showBooking.innerText = "Valitse paikat istuinkartasta.";  //default message if no seat is chosen yet
        } else {
            showBooking.innerText = "Valitut paikat: " + selectedSeats.toString();
        }
    }

    /* prevent too early form submitting */
    let allowSubmit = false;
    document.querySelector("#bookingForm input[type='submit']").addEventListener("click", function() {
        allowSubmit = true;  //change the value ONLY when submit button is pressed
    });
    document.getElementById("bookingForm").addEventListener("submit", function(e) {
        if (!allowSubmit) {
            e.preventDefault();
        }
    });

    /* handle form submitting */
    document.getElementById("bookingForm").addEventListener("submit", function(e) {

        if (!validateForm()) {  //manually call the function to validate input
            e.preventDefault();
            return;
        }

        e.preventDefault(); //  stops page reload

        const seatsString = document.getElementById("selectedSeatsInput").value;
        const eventID = new URLSearchParams(window.location.search).get("id");

        console.log("seatsString: ", seatsString);

        /* send data to php */
        fetch("bookEventFunctionality.php?id=" + eventID, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                seats: seatsString,
                editMode: editMode,
                usersSeats: usersSeats.join(",")
            })
        })
        /* get respond from php */
        .then(res => res.json())
        .then(data => {
            console.log(data);
            document.getElementById("message").innerText = data.message;
            if (data.success) {
                userAlreadyBooked = selectedSeats.length;  //update the variable with amount of booked seats
                selectedSeats = [];
                /* change the information about seats left or total amount of participants (depends on the event type) */
                const placesLeft = document.getElementById("placesLeft");
                const bookedCount = document.getElementById("bookedCount");
                if (placesLeft) {
                    placesLeft.innerText = data.placesLeft;
                }
                if (bookedCount) {
                    bookedCount.innerText = data.bookedSeatsCount;
                }
            } else {
                if (data.message == "Voit varata enintään 2 paikkaa.") {
                    alert("Voit varata enintään 2 paikkaa.");

                }
            }
            if (data.message == "Varaus päivitetty!") {
                document.getElementById("backToAccount").style.display = "block";  // show the button to get back to the account page
                document.getElementById("backToEvents").innerText = "Kaikki tapahtumat";  //change the text of the button for event list
                document.getElementById("backToEvents").style.display = "block";  // show the button to get to the event list
            } else {
                document.getElementById("backToEvents").style.display = "block";  // show only the button to get back to the event list
            }
        });
    });
});
    
/* handle seat's selection */
window.selection = function(i) {

    const seatID = document.getElementById(i);  //get the id of the selected seat

    if (bookedSeats.includes(Number(i))) {  //check if the seat is already booked by someone
        return;
    }

    if (checkLogin == "0") {
        alert("Kirjaudu sisään paikan varamiselle!");  //check if the user is logged in, and if not, show the message and deselect
        return;
    }

    if (selectedSeats.includes(i) && eventType == 1) {
        // if the seat is already selected → unselect. Only for event type 1
        selectedSeats = selectedSeats.filter(id => id !== i);
        /* seatID.style.backgroundColor = ""; */
        seatID.classList.remove("selected");
    } else {
           
        // if user already booked some seats before, restrict booking more than 2 seats in total
        if (exceedsLimit(1)) {
            if (!limitAlertShown) {
                alert("On mahdollista varata enintään 2 paikkaa. Lisää paikkoja voi varata täyttämällä lomakkeen. Omat varaukset voi muokata oma tili -sivulla.");
                limitAlertShown = true;
            }
            return;
        }
        limitAlertShown = false;

        /* if everything is OK, add seat's id to array and change it's style to selected */
        selectedSeats.push(i);
        /* seatID.style.backgroundColor = "black"; */
        seatID.classList.add("selected");
    }
    console.log(selectedSeats); // debugging. This line can be deleted later

    document.getElementById("selectedSeatsInput").value = selectedSeats.join(",");  //sends information about the selected seats to the hidden input for later php-handling
    if (selectedSeats.length == 0) {
        showBooking.innerText = "Valitse paikat istuinkartasta.";
    } else {
        showBooking.innerText = "Valitut paikat: " + selectedSeats.toString();
    }
}


/* validate input on submition */
function validateForm() {
    if (checkLogin == "0") {
        alert("Kirjaudu sisään paikan varamiselle!");  //once again check if the user is logged in, because the first check doesn't work for 2nd and 3rd types of events
        return false;
    }
    if (selectedSeats.length === 0 && eventType == 1) {  //check if at least one seat is selected. Only for event type 1!
        alert("Valitse vähintään yksi paikka!");
        return false;
    }
    return true;
}

/* check if the amount of booked places exeeds allowed number */
function exceedsLimit(newSeatsCount = 0) {
    return (selectedSeats.length + newSeatsCount) > 2;
}

/* show the form for booking more seats */
function revealTheForm() {
    let form = document.getElementsByClassName("morePlaces")[0];
    form.style.display = "block";
}

/* send all data from the form to admin's email */
document.getElementById("morePlaces").addEventListener("submit", function(e) {
    e.preventDefault();  // stops page reload

    let formData = new FormData(this);  // collects all form inputs automatically

    fetch("moreBookings.php", {  // sends the data to php using POST
        method: "POST",
        body: formData
    })
    .then(response => response.json())  // gets response from the php and converts it into text
    .then(data => {
        console.log("Server response: ", data);  // print all the data into console
        if (data.status === "success") {
            console.log("Email content:", data.debug);  //show the content of sent email in console
            alert(data.message);  // if access, show the message "Viesti lähetetty"
        } else {
            console.error("Server error:", data);
            alert("Virhe lähetyksessä");
        }
        document.getElementById("backToEvents2").style.display = "block";  // show the button (it's the same as before, only id is changed)
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Yhteysvirhe (palvelin ei vastaa)");
        document.getElementById("backToEvents2").style.display = "block";  // show the button
    });
});