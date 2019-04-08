export function megamenu() {
    $(document).ready(function () {

        "use strict";

        $('.menu > ul > li:has( > ul)').addClass('menu-dropdown-icon');
        $('.menu > ul > li > ul:not(:has(ul))').addClass('normal-sub');
        $(".menu > ul").before("<a class=\"menu-mobile\" href=\"#\"><img class=\"navbar_logo\" src=\"/assets/public/images/logo-mobile.png\" alt=\"FitClothing\"></a>");
        $(".menu > ul > li").hover(
            function (e) {
                if ($(window).width() > 1025) {
                    $(this).children("ul").fadeIn(150);
                    e.preventDefault();
                }
            }, function (e) {
                if ($(window).width() > 1025) {
                    $(this).children("ul").fadeOut(150);
                    e.preventDefault();
                }
            }
        );
        //If width is more than 943px dropdowns are displayed on hover


        //the following hides the menu when a click is registered outside
        $(document).on('click', function(e){
            if($(e.target).parents('.menu').length === 0)
                $(".menu > ul").removeClass('show-on-mobile');
        });

        $(".menu > ul > li").click(function() {
            //no more overlapping menus
            //hides other children menus when a list item with children menus is clicked
            var thisMenu = $(this).children("ul");
            var prevState = thisMenu.css('display');
            $(".menu > ul > li > ul").fadeOut();
            if ($(window).width() < 1025) {
                if(prevState !== 'block')
                    thisMenu.fadeIn(150);
            }
        });
        //If width is less or equal to 943px dropdowns are displayed on click

        $(".menu-mobile").click(function (e) {
            $(".menu > ul").toggleClass('show-on-mobile');
            e.preventDefault();
        });
        //when clicked on mobile-menu, normal menu is shown as a list, classic rwd menu story

    });
    $(function(){

        $('.spinner .btn:first-of-type').on('click', function() {
            var btn = $(this);
            var input = btn.closest('.spinner').find('input');
            if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
                input.val(parseInt(input.val(), 10) + 1);
            } else {
                btn.next("disabled", true);
            }
        });
        $('.spinner .btn:last-of-type').on('click', function() {
            var btn = $(this);
            var input = btn.closest('.spinner').find('input');
            if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
                input.val(parseInt(input.val(), 10) - 1);
            } else {
                btn.prev("disabled", true);
            }
        });

    });
}