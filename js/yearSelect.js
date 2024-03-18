const appendButton = document.getElementById("append-button");
const popupWrapper = document.getElementById("popup-wrapper");
const close = document.getElementById("close");

appendButton.addEventListener('click', () => {
    popupWrapper.style.display = "block";
});

popupWrapper.addEventListener('click', e => {
    if (e.target.id === popupWrapper.id || e.target.id === close.id) {
        popupWrapper.style.display = "none";
    }
});