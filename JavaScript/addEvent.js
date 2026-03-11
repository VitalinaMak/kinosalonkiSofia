const fileInput = document.getElementById("eventPicture-input");
const preview = document.getElementById("preview");

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

function removeImage() {
    const currentImage = document.getElementsByName("current_image")[0];

    fileInput.value = '';  //clear the field for file input
    preview.src = "kuvat/tapahtumaKuvat/noImage.png";
    if (currentImage) {
        currentImage.value = "noImage.png";
    }
}