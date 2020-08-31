var collectionsSortable = $(".handle-position");

collectionsSortable.each(function(index, collection) {
    let id = `#${$(collection).data("sort")}`;

    sort(id);
})

function sort(selector) 
{
    $(selector).sortable({
        items: "> *:not(.unsortable)",
        forcePlaceholderSize: true,
        cursor: "move",
        update: function(event, ui) {
            var $lis = $(this).find('.collection-item-sortable');
            $lis.each(function(index, elem) {
                var $li = $(this);
                var newVal = index + 1;
                $(this).find('.position input').val(newVal);
                $(this).parent().find('.bullet-position').html(newVal);
            });
            $(selector).height($(selector).height("auto"));
        }
    });
    $(selector).disableSelection();
}