"use strict";

const delAccButton = document.getElementById("deleteaccount-btn");
/* delAccButton.addEventListener("click", function() {
  alert("Haluatko varmasti poistaa tilisi? Tätä toimintoa ei voi peruuttaa");
}) */
$( function() {
  $( "#confirm-dialog" ).dialog({
    autoOpen: false,  //prevent opening on the page load
    resizable: false,
    height: "auto",
    width: 400,
    modal: true,  //this means a user cannot click the background page
    buttons: {
      "Kyllä": function() {
        $( this ).dialog( "close" );
        $("#delete-form").submit();  // submit the hidden form to delete the account
      },
      Peruuta: function() {
        $( this ).dialog( "close" );
      }
    }
  });

  // Open dialog when button is clicked
  $("#deleteaccount-btn").on("click", function() {
    $("#confirm-dialog").dialog("open");
  });
} );

//turned out it's integrated in html, so there's no real need in this function
/* const passwordInput = document.getElementById("oldPassword-input");
passwordInput.addEventListener("click", function() {
  if (this.getAttribute('type') == 'password') {
    this.setAttribute('type', 'text');
  } else {
    this.setAttribute('type', 'password');    
  }
})  */   

function toggleEdit() {
  // Находим все инпуты в форме
  const inputs = document.querySelectorAll("#profile input");
  const labels = document.querySelectorAll("#profile label");
  const editBtn = document.getElementById("edit-btn");
  const saveBtn = document.getElementById("save-btn");

  inputs.forEach((input) => {
    if (input.readOnly) {
      // Включаем режим редактирования
      input.removeAttribute("readonly");
      input.classList.add("activated");  //add classname for styling
    }
  });

  labels.forEach((label) => {
    label.classList.add("activatedLabel");  //add classname for styling
  })

  // Скрываем кнопку "Редактировать" и показываем "Сохранить"
  editBtn.style.display = "none";
  saveBtn.style.display = "inline-block";
}

/* function to display fields for password eset and remove fields with personal information */
function changePasswordForm() {
  const personalInfo = document.getElementsByClassName("personalInfo");  //gets div-elements with inputs and labels inside 
  const passwordReset = document.getElementsByClassName("passwordReset");  //gets div-elements with inputs and labels inside 

  //hide and disable inputs for personal info
  for (let i = 0; i < personalInfo.length; i++) {
    const input = personalInfo[i].querySelector("input");  //get input from the div
    personalInfo[i].style.display = "none";
    input.required = false;
    input.disabled = true;
  }

  //reveal and enable inputs for password reset
  for (let i = 0; i < passwordReset.length; i++) {
    const input = passwordReset[i].querySelector("input");  //get input from the div
    input.disabled = false;
    input.required = true;
    input.removeAttribute("readonly");
    input.classList.add("activated");  //add activated class for styling
    passwordReset[i].style.display = "flex";
  }
  toggleEdit();  //make input fields editable from the moment they appear
}
