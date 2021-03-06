$(document).ready(function() {
    $('#userTable').DataTable({
        "order": [
            [ 0, "asc" ]
        ],
        "language": { "url": `//cdn.datatables.net/plug-ins/1.10.7/i18n/${getLanguageTraduction()}.json` }
    });
});

const getLanguageTraduction = () => 
{
    const userLocale = document.querySelector("#userLocale").textContent;
    
    if(userLocale == "fr") {
        return "French";
    } else if (userLocale == "en") {
        return "English";
    }
}