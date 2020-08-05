$("#moduleTable .input-switch").on("change", function(e) {
    var path = $(this).data("url");
    
    window.location.href = path;
})