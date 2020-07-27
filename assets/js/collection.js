var $collectionHolder;

var $addNavLinkButton = $('<button type="button" class="btn btn-success btn-icon-split ml-auto btn-sm add-nav-link"><span class="icon text-white-50"><i class="fas fa-plus"></i></span><span class="text">' + collectionButtonTextAdd + '</span></button>');
var $newLinkDiv = $addNavLinkButton.insertAfter('.collection-title');

$(document).ready(function() {
    $collectionHolder = $('ul.nav-links-collection');

    $collectionHolder.find('li').each(function() {
        addNavLinkFormDeleteLink($(this));
    });

    $collectionHolder.data('index', $collectionHolder.find('input').length);

    handlePosition();

    $addNavLinkButton.on('click', function(e) {
        $("#sort").height($("#sort").height("auto"));

        $(".empty-navlink").hide();

        addNavLinkForm($collectionHolder);
    });
});

function addNavLinkForm($collectionHolder) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $collectionHolder.append($newFormLi);

    setTimeout(() => {
        handlePosition();

    }, 1);

    addNavLinkFormDeleteLink($newFormLi);
}

function addNavLinkFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn btn-danger btn-icon-split ml-auto btn-sm collection-remove-link"><span class="icon text-white-50"><i class="fas fa-trash"></i></span><span class="text">' + collectionButtonTextRemove + '</span></button>');
    $tagFormLi.find(".nav-item-content").append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();

        $("#sort").height($("#sort").height("auto"));

        var navLinkEmpty = $(".empty-navlink");

        $(".collection .nav-item").length == 0 ? navLinkEmpty.show() : navLinkEmpty.hide();

       handlePosition();
    });
}

function handlePosition() {
    $(".nav-input-position input").each(function(index, elem) {
        var newVal = index + 1;

        elem.value = newVal;
        $(".nav-bullet-position").last().html(newVal);
    })

    $(".nav-bullet-position").each(function (index, elem) {
        var newVal = index + 1;

        elem.textContent = newVal;
    })
}