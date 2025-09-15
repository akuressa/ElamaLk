// Burger menus
document.addEventListener('DOMContentLoaded', function () {
    "use strict";
    // open
    const burger = document.querySelectorAll('.navbar-burger');
    const menu = document.querySelectorAll('.navbar-menu');

    if (burger.length && menu.length) {
        for (var i = 0; i < burger.length; i++) {
            burger[i].addEventListener('click', function () {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }

    // close
    const close = document.querySelectorAll('.navbar-close');
    const backdrop = document.querySelectorAll('.navbar-backdrop');

    if (close.length) {
        for (var i = 0; i < close.length; i++) {
            close[i].addEventListener('click', function () {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }

    if (backdrop.length) {
        for (var i = 0; i < backdrop.length; i++) {
            backdrop[i].addEventListener('click', function () {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }
});

// Choose langages
$('#chooseLang').change(function () {
    "use strict";
    // set the window's location property to the value of the option the user has selected
    window.location = `?change_language=` + $(this).val();
});

// Print function
function doPrint() {
    "use strict";
    // Printable area
    var printContents = $('.printable-area').html();

    // replace body
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    // Print
    window.print();
    document.body.innerHTML = originalContents;
}


function prevent() {
    "use strict";
    window.alert = function () { };
}