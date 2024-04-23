const appendButton = document.getElementById("append-button");
const popupWrapper = document.getElementById("popup-wrapper");
const close = document.getElementById("close");
const error = document.getElementById("error");

appendButton.addEventListener('click', () => {
    popupWrapper.style.display = "block";
});

popupWrapper.addEventListener('click', e => {
    if (e.target.id === popupWrapper.id || e.target.id === close.id) {
        popupWrapper.style.display = "none";
        error.style.display = "none";
    }
});

function errorDisplay() {
    error.style.display = "block";
}