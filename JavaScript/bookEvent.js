console.log("JS loaded");

let selectedSeats = [];  //array for IDs of selected seats
let checkLogin = 0;  //variable to check if the user is logged in
let showBooking;  //variable to show booked seats

document.addEventListener("DOMContentLoaded", function () {
    
    const checkLoginElement = document.getElementById("checkLogin");

    if (checkLoginElement) {
        checkLogin = checkLoginElement.value;
    } 

    showBooking = document.getElementById("showSelectedSeats");  

    if (selectedSeats.length == 0) {
        showBooking.innerText = "Valitse paikat istuinkartasta.";  //default message if no seats is chosen yet
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
        e.preventDefault(); //  stops page reload

        const seats = document.getElementById("selectedSeatsInput").value;
        const eventID = new URLSearchParams(window.location.search).get("id");

        /* send data to php */
        fetch("bookEventFunctionality.php?id=" + eventID, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                seats: seats
            })
        })
        /* get respond from php */
        .then(res => res.text())
        .then(data => {
            console.log(data);
            document.getElementById("message").innerText = data;
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

    if (selectedSeats.includes(i)) {
        // if the seat is already selected → unselect
        selectedSeats = selectedSeats.filter(id => id !== i);
        seatID.style.backgroundColor = "";
    } else {
        // if more than 2 seats are selected, remove the selection of the first selected seat
        if (selectedSeats.length >= 2) {
            let unselectID = selectedSeats.shift(); //remove the first element of the array and saves it to the variable
            let unselectSeat = document.getElementById(unselectID);  //get the element by it's id
            unselectSeat.style.backgroundColor = "";  //remove selection
        }
        // if user already booked some seats before, restrict booking more than 2 seats in total
        if (userAlreadyBooked >= 2 || (userAlreadyBooked + selectedSeats.length) >= 2) {
            alert("On mahdollista varata enintään 2 paikkaa. Lisää paikkoja voi varata ottamalla yhteyttä yritykseen. Omat varaukset voi muokata oma tili -sivulla.");
            return;
        }
        /* if everything is OK, add seat's id to array and change it's style to selected */
        selectedSeats.push(i);
        seatID.style.backgroundColor = "black";
    }
    console.log(selectedSeats); // debug

    document.getElementById("selectedSeatsInput").value = selectedSeats.join(",");  //sends information about the selected seats to the hidden input for later php-handling
    showBooking.innerText = "Valitut paikat: " + selectedSeats.toString();  //show the numbers of selected seats on the page
}


/* check if at least one seat is selected */
function validateForm() {
    if (selectedSeats.length === 0) {
        alert("Valitse vähintään yksi paikka!");
        return false;
    }
    return true;
}