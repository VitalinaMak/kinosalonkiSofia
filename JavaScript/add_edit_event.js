// At the top of your file, get the button reference
const removeBtn = document.getElementById("remove-button");

const fileInput = document.getElementById("eventPicture-input"); // image input
const preview = document.getElementById("preview"); // image preview

let selectedEventType; // variable to store selected type of event (before form submition)
const maxPlacesInput = document.getElementById("maxplaces-input"); //input of max. amount of visitors

/* const passwordInput = document.getElementById("password-input");
passwordInput.addEventListener("click", function() {
  if (this.getAttribute('type') == 'password') {
    this.setAttribute('type', 'text');
  } else {
    this.setAttribute('type', 'password');    
  }
})  */

//show preview when image is uploaded
fileInput.addEventListener("change", function () {
  const file = this.files[0];

  if (file) {
    const reader = new FileReader();

    reader.addEventListener("load", function () {
      preview.src = reader.result;
      preview.style.display = "block";

      //   SHOW the button when an image is loaded DANGERLINE
      if (removeBtn) removeBtn.style.display = "inline-block";
    });

    reader.readAsDataURL(file);
  }
});

//save what type of event is selected
document
  .getElementById("eventType-input")
  .addEventListener("change", function () {
    selectedEventType = this.value;
    console.log("Selected value:", selectedEventType);
    disableMaxAmountInput();
  });

function removeImage() {
  if (document.getElementsByName("current_image")[0]) {
    const currentImage = document.getElementsByName("current_image")[0]; //this element exists only in editEvent
  }
  const removeFlag = document.getElementsByName("remove_image")[0];

  fileInput.value = ""; //clear the field for file input
  preview.src = ""; // Clear the source
  preview.style.display = "none"; // Hide the image
  //   preview.src = "kuvat/tapahtumaKuvat/noImage.png"; //change the image in preview to the default one

  // HIDE the button because there is no longer an image
  if (removeBtn) removeBtn.style.display = "none";
  //   if (removeFlag) removeFlag.value = "1";
  console.log("the link is pressed");

  //   DANGER
  removeFlag = document.getElementsByName("remove_image")[0];
  if (removeFlag) removeFlag.value = "1";
  // DANGER
}

function disableMaxAmountInput() {
  /* display cases for different types of events (in order 3 - 2 - 1) */
  if (selectedEventType == "3") {
    maxPlacesInput.value = ""; //remove any values from the input for 3rd type of event
    maxPlacesInput.placeholder = "Ei ole osallistujamäärä rajoitusta.";
    maxPlacesInput.setAttribute("readonly", ""); //make the field uneditable
    maxPlacesInput.removeAttribute("required"); //let it stay empty
  } else if (selectedEventType == "2") {
    if (maxPlacesInput.hasAttribute("readonly")) {
      maxPlacesInput.removeAttribute("readonly"); //if the field had readonly attriburte before, make sure it's editable again
      maxPlacesInput.setAttribute("required", ""); //make the field required again
    }
  }
}
