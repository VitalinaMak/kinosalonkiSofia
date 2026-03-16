"use strict";

// ТУТ чуть неправильно так как я поменяла в пхп названия классов поэтому не работает
function toggleEdit() {
  // Находим все инпуты в форме
  const inputs = document.querySelectorAll("#login input");
  const editBtn = document.getElementById("edit-btn");
  const saveBtn = document.getElementById("save-btn");

  inputs.forEach((input) => {
    if (input.readOnly) {
      // Включаем режим редактирования
      input.readOnly = false;
      input.style.backgroundColor = "var(--cinema-whiteish)"; // Делаем фон светлее, чтобы было видно, что можно писать
      input.style.border = "3px solid var(--blue)";
    }
  });

  // Скрываем кнопку "Редактировать" и показываем "Сохранить"
  editBtn.style.display = "none";
  saveBtn.style.display = "inline-block";
}
