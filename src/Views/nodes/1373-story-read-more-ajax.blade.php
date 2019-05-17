/* resources/views/vendor/openpolice/nodes/1373-story-read-more-ajax.blade.php */
$(document).on("click", "#showBtnStoryMore", function() {
    $("#showStoryLess").slideUp(300);
    setTimeout(function() { $("#showStoryMore").slideDown(300); }, 301);
    window.location="#n1479";
});
$(document).on("click", "#showBtnStoryLess", function() {
    $("#showStoryMore").slideUp(300);
    setTimeout(function() { $("#showStoryLess").slideDown(300); }, 301);
    window.location="#n1479";
});