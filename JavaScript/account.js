"use strict";

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
      /* input.style.backgroundColor = "var(--cinema-whiteish)"; // Делаем фон светлее, чтобы было видно, что можно писать
      input.style.border = "3px solid var(--blue)"; */
    }
  });

  labels.forEach((label) => {
    label.classList.add("activatedLabel");  //add classname for styling
  })

  // Скрываем кнопку "Редактировать" и показываем "Сохранить"
  editBtn.style.display = "none";
  saveBtn.style.display = "inline-block";
}
