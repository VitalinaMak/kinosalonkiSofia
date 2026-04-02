"use strict";

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
  const personalInfo = document.getElementsByClassName("personalInfo");
  const passwordReset = document.getElementsByClassName("passwordReset");

  //hide inputs for personal info
  for (let i = 0; i < personalInfo.length; i++) {
    personalInfo[i].style.display = "none";
  }

  //reveal inputs for password reset
  for (let i = 0; i < passwordReset.length; i++) {
    passwordReset[i].style.display = "flex";
  }
  toggleEdit();  //make input fields editable from the moment they appear
}
