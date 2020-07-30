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

    console.log(input.files);
}));

// Enable tooltip
$(function () {
    $('[data-toggle="tooltip"]').tooltip({
        container: '.table'
    })
})

const inputLabel = document.querySelectorAll(".custom-file-label");

inputLabel.forEach((label) => {
    if (lang === "fr") {
        label.setAttribute("data-content", "Parcourir");
    } else {
        label.setAttribute("data-content", "Browse");
    }
})