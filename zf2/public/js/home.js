/*global $, baseUrl, jQuery, alert*/
function aboutMe() {
    'use strict';
    $('.homeContent.mainScreen').fadeOut(200, function () {
        $('.homeContent.aboutMeScreen').fadeIn(500);
    });
}

function sendAMessage() {
    'use strict';
    $('.homeContent.mainScreen').fadeOut(200, function () {
        $('.homeContent.sendAMessageScreen').fadeIn(500);
    });
}

function guestUser() {
    'use strict';
    //
}

function signUp() {
    'use strict';
    $('.homeContent.mainScreen').fadeOut(200, function () {
        $('.homeContent.signUpScreen').fadeIn(500);
    });
}