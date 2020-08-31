$(document).ready(function() {
    var items = document.querySelectorAll(".collection select[id*=_internal]");
    var buttonAdd = document.querySelector(".collection-add");

    manageInputs(items);

    buttonAdd.addEventListener("click", () => {
       setTimeout(() => {
            var newItems = document.querySelectorAll(".collection select[id*=_internal]");
            
            manageInputs(newItems);
       }, 1)
    })
})

function manageInputs (elements)
{
    elements.forEach((item) => {
        changeState(item);

        item.addEventListener("change", (e) => {
            changeState(e.target);
        })
    })

}

function changeState (item)
{
    var navItem = item.closest('.collection-item');
    var internal = item.value == 1 ? true : false;

    if (internal === true) {
        navItem.querySelector(".nav-input-page").style.display = 'block';
        navItem.querySelector(".nav-input-link").style.display = 'none';

        navItem.querySelector("input[id*=_title]").required = false;
        navItem.querySelector(".nav-input-title label").classList.remove("required");
    } else {
        navItem.querySelector(".nav-input-page").style.display = 'none';
        navItem.querySelector(".nav-input-link").style.display = 'block';

        navItem.querySelector("input[id*=_title]").required = true;
        navItem.querySelector(".nav-input-title label").classList.add("required");
    }
}