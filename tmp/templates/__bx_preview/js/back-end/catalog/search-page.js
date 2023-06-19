$(function ($) {
    var $body = $(document.body);

    $body.on('change', '#sort-by, #search-in-category', function (e) {
        var $this = $(this);
        window.location =  $this.val();
    });
});
