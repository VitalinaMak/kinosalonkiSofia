/* const passwordInput = document.getElementById("oldPassword-input");
passwordInput.addEventListener("click", function () {
  if (this.getAttribute("type") == "password") {
    this.setAttribute("type", "text");
  } else {
    this.setAttribute("type", "password");
  }
}); */

function passwordToggle() {
  let x = document.getElementById("password-input");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

/* if ((document.getElementById("checkbox").checked = false)) {
  //svg closed
} else {
  //svg open
} */
