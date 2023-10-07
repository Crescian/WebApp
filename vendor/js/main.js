// Active Navigation Link
$('.navbar-nav li a').click((e) => {
    e.stopPropagation();
    $('.navbar-nav li a').removeClass('active');
    $(this).addClass('active');
});

// Multilevel-Dropdown
$(".dropdown-toggle").on("click", function () {
    $(this).parent().siblings('.dropdown').children('a').attr("aria-expanded", "false").parent().find('.dropdown-menu').removeClass("show");
});
