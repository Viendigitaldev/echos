$(document).ready(function(){

    $(".mobile-menu-main .nav-item > .nav-link").click(function(e){

        const submenu = $(this).next(".sub-menu");

        if(submenu.length){

            e.preventDefault();

            $(".mobile-menu-main .sub-menu").not(submenu).slideUp(300);
            $(".mobile-menu-main .nav-item").not($(this).parent()).removeClass("active");

            submenu.stop(true,true).slideToggle(300);
            $(this).parent().toggleClass("active");

        }

    });

});
