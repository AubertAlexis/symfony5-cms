
// on remove item
$("body").on("click", ".collection-item-delete", e => {
    let collection = $(e.currentTarget).closest(".collection");
    let position = collection.hasClass("handle-position") ? true : false;
    
    $(e.currentTarget).closest("li").remove();
    $(e.currentTarget).closest("hr").remove();

    handleEmpty(collection, false);

    if (position) {
        handlePosition();
    }
});

// on load items
$(document).ready(function(e) {
    let collections = $(".collection");

    collections.each(function(index, collection) {
        let id = `#${$(collection).attr("id")}`;
        let collectionItem = $(`${id}.collection`);
        let position = collectionItem.hasClass("handle-position") ? true : false;

        handleEmpty(collectionItem, false);

        if (position) {
            handlePosition();
        }
    })
});

// on add item
$("body").on("click", ".collection-add", e => {
    let collection = $(`#${e.currentTarget.dataset.collection}`);
    let position = collection.hasClass("handle-position") ? true : false;
    let prototype = collection.data('prototype');
    let index = collection.data('index');

    collection.append(prototype.replace(/__name__/g, index));

    handleEmpty(collection, true)

    if (position) {
        handlePosition();
    }

    index++;
    collection.data('index', index);
})

// 
// Functions
// 

function handlePosition() {
    $(".position input").each(function(index, elem) {
        var newVal = index + 1;

        elem.value = newVal;
    })

    $(".bullet-position").each(function (index, elem) {
        var newVal = index + 1;

        elem.textContent = newVal;
    })
}

function handleEmpty(selector, add = false) {
    let empty = selector.find(".empty-collection");

    if (add === true) {
        empty.hide();
    } else {
        selector.find(".collection-item").length == 0 ? empty.show() : empty.hide();
    }
}