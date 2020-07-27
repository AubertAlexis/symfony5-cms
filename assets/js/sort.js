$("#sort").sortable({
    items: "> *:not(.unsortable)",
    placeholder: "placeholderClass",
    forcePlaceholderSize: true,
    cursor: "move",
    update: function(event, ui) {
        var $lis = $(this).find('.nav-item-sortable');
        $lis.each(function(index, elem) {
            var $li = $(this);
            var newVal = index + 1;
            $(this).find('.nav-input-position input').val(newVal);
            $(this).parent().find('.nav-bullet-position').html(newVal);
        });
        $("#sort").height($("#sort").height("auto"));
    },
    create: function() {
        $(this).height($(this).height());
    }
});
$("#sort").disableSelection();