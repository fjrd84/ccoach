/*global $, baseUrl, jQuery, alert*/
function aboutMe() {
    'use strict';
    showLoading();
    window.location = baseUrl + '/home/jdonado';
}

function sendAMessage() {
    'use strict';
    $('.homeContent.mainScreen').fadeOut(200, function () {
        $('.homeContent.sendAMessageScreen').fadeIn(500);
    });
}

function guestUser() {
    'use strict';
    sendLoginForm('guest@cassettecoach.com', '12341234');
}

function signUp() {
    'use strict';
    $('.signUpScreen').fadeIn(300);
}

function showLogIn() {
    'use strict';
    closeBox('.signUpScreen');
    $('.logInScreen').fadeIn(300);
}

function sendSignUp() {
    'use strict';
    var username = $('.signUpScreen input.user').val(),
        pass = $('.signUpScreen input.pass').val(),
        test = '';
    if (!validateEmail(username)) {
        homeFeedback('You must enter a valid email address.');
        return;
    } else if (pass.length < 5) {
        homeFeedback('Your password must be at least 5 characters long.');
        return;
    }
    test = username + pass;
    $.post(baseUrl + '/home/newuser', { 'username': username, 'password': pass })
        .done(function (data) {
            data = data.replace('["', '');
            data = data.replace('"]', '');
            if (data === 'success') {
                sendLoginForm(username, pass);
            } else {
                homeFeedback(data);
            }
        });
}

function sendLogIn() {
    'use strict';
    var username = $('.logInScreen input.user').val(),
        pass = $('.logInScreen input.pass').val();
    if (username === '') {
        homeFeedback('Your e-mail cannot be empty.');
        return;
    }
    if (pass === '') {
        homeFeedback('Your password cannot be empty.');
        return;
    }
    /*$.post(baseUrl + '/home/newuser', { 'username': username, 'password': pass })
     .done(function (data) {
     alert("Data Loaded: " + data);
     });*/
    sendLoginForm(username, pass);
}

function sendLoginForm(username, pass) {
    'use strict';
    var form = document.createElement("form"),
        element1 = document.createElement("input"),
        element2 = document.createElement("input"),
        element3 = document.createElement("input");

    form.className = 'hidden';

    form.method = "POST";
    form.action = baseUrl + '/home/authenticate';
    element1.value = username;
    element1.name = 'username';
    element2.value = pass;
    element2.name = 'password';
    element3.value = '0';
    element3.name = 'rememberme';
    form.appendChild(element1);
    form.appendChild(element2);
    form.appendChild(element3);
    document.body.appendChild(form);
    form.submit();
    showLoading();
}

function forgotPassword() {
    'use strict';
    closeBox('.signUpScreen');
    closeBox('.logInScreen');
    $('.forgotPasswordScreen').fadeIn(300);
}

function sendForgotPassword() {
    'use strict';
    var username = $('.forgotPasswordScreen input.user').val();
    if (!validateEmail(username)) {
        homeFeedback('You must enter a valid email address.');
        return;
    }
    $.post(baseUrl + '/home/forgotpass', { 'username': username })
        .done(function (data) {
            homeFeedback('Check your email. If you don\'t receive a message, look up in the spam folder.');
        });
    closeBox('.forgotPasswordScreen');
}


function showLoading() {
    'use strict';
    $('.homePageWrapper *').fadeOut(300, function () {
        $('.loadingMain').show();
    });
}

function validateEmail(email) {
    'use strict';
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function homeFeedback(text) {
    'use strict';
    $('.homePageFeedback').empty();
    $('.homePageFeedback').append(text);
    $('.homePageFeedback').fadeIn(300);
    setTimeout(function () {
        $('.homePageFeedback').fadeOut(500);
    }, 2000);
}

function closeBox(boxName) {
    'use strict';
    $(boxName).fadeOut(300);
}

function showTermsAndConditions() {
    'use strict';
    $("html, body").animate({ scrollTop: 0 }, "slow", function(){
        $('#termsAndConditions').fadeIn(500);
    });
}

function closeTermsAndConditions() {
    'use strict';
    $('#termsAndConditions').fadeOut(300);
}

function backHome(fromClass) {
    'use strict';
    $('.homeContent.' + fromClass).fadeOut(200, function () {
        $('.homeContent.mainScreen').fadeIn(500);
    });
}

$(document).ready(function () {
    'use strict';
    if (messages !== '') {
        homeFeedback(messages);
    }
});

