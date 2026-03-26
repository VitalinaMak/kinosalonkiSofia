const fileInput = document.getElementById("eventPicture-input");  // image input
const preview = document.getElementById("preview");  // image preview

let selectedEventType;  // variable to store selected type of event (before form submition)
const maxPlacesInput = document.getElementById("maxplaces-input");  //input of max. amount of visitors

//show preview when image is uploaded
fileInput.addEventListener("change", function () {

    const file = this.files[0];

    if (file) {
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            preview.src = reader.result;
            preview.style.display = "block";
        });

        reader.readAsDataURL(file);
    }
});

//save what type of event is selected
document.getElementById("eventType-input").addEventListener("change", function() {
    selectedEventType = this.value;
    console.log("Selected value:", selectedEventType);
    disableMaxAmountInput();
});

function removeImage() {
    if (document.getElementsByName("current_image")[0]) {
        const currentImage = document.getElementsByName("current_image")[0];  //this element exists only in editEvent
    }
    const removeFlag = document.getElementsByName("remove_image")[0];

    fileInput.value = '';  //clear the field for file input
    
    preview.src = "kuvat/tapahtumaKuvat/noImage.png";  //change the image in preview to the default one

    if (removeFlag) removeFlag.value = "1";
    console.log("the link is pressed");
}

function disableMaxAmountInput() {
    /* display cases for different types of events (in order 3 - 2 - 1) */
    if (selectedEventType == "3") {
        maxPlacesInput.value = "";  //remove any values from the input for 3rd type of event
        maxPlacesInput.placeholder = "Ei ole osallistujamäärä rajoitusta.";
        maxPlacesInput.setAttribute("readonly", "");  //make the field uneditable
        maxPlacesInput.removeAttribute("required");  //let it stay empty
    } else if (selectedEventType != "3" && maxPlacesInput.hasAttribute("readonly")) {
        maxPlacesInput.removeAttribute("readonly");  //if the user changes type of event to 2 (limited number of visitors), make sure it editable again
        maxPlacesInput.setAttribute("required", "");  //make the field required again 
        maxPlacesInput.placeholder = "Max. osallistujamäärä";
    }
}