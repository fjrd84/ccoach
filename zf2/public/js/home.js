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
    sendLoginForm('guest', 'guest')
}

function signUp() {
    'use strict';
    $('.homeContent.mainScreen').fadeOut(200, function () {
        $('.homeContent.signUpScreen').fadeIn(500);
    });
}

function showLogIn() {
    'use strict';
    $('.homeContent.mainScreen').fadeOut(200, function () {
        $('.homeContent.logInScreen').fadeIn(500);
    });
}

function sendSignUp() {
    'use strict';
    var username = $('.signUpScreen input.user').val(),
        pass = $('.signUpScreen input.pass').val(),
        test = '';
    if(!validateEmail(username)){
        alert('You must enter a valid email address.');
        return;
    }else if(pass === ''){
        alert('Your password cannot be empty.');
        return;
    }
    test = username + pass;
    $.post(baseUrl + '/home/newuser', { 'username': username, 'password': pass })
        .done(function (data) {
            alert("Data Loaded: " + data);
        });
}

function sendLogIn() {
    'use strict';
    var username = $('.logInScreen input.user').val(),
        pass = $('.logInScreen input.pass').val();
    /*$.post(baseUrl + '/home/newuser', { 'username': username, 'password': pass })
        .done(function (data) {
            alert("Data Loaded: " + data);
        });*/
    sendLoginForm(username, pass);
}

function sendLoginForm(username, pass){
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
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}