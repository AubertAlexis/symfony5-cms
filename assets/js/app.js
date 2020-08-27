import '../css/app.css';

// Show filename on inputs
const inputs = document.querySelectorAll("input[type='file']");
const lang = document.querySelector("#userLocale").textContent;

inputs.forEach(((input) => {
    input.setAttribute("lang", "fr");

    input.addEventListener('change', (event) => {
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
    });
}));

// Enable tooltip
$(function () {
    $('[data-toggle="tooltip"]').tooltip({
        container: '.table'
    })
})

// Manage card height
const cards = document.querySelectorAll(".card-same-height");
const cardsHeight = [];
var maxHeight = 0;

cards.forEach((card) => {
    cardsHeight.push(card.clientHeight);

    maxHeight = Math.max(...cardsHeight);
})

cards.forEach((card) => {
    card.style.height = Math.max(...cardsHeight) + "px";
})

const inputLabel = document.querySelectorAll(".custom-file-label");

inputLabel.forEach((label) => {
    if (lang === "fr") {
        label.setAttribute("data-content", "Parcourir");
    } else {
        label.setAttribute("data-content", "Browse");
    }
})