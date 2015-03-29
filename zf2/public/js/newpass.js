/*global $,baseUrl*/
function sendNewPassword() {
    'use strict';
    if ($('#newPassword').val().length < 5) {
        alert('The password must be at least five characters long.');
    } else if ($('#newPassword').val() !== $('#newPasswordRepeat').val()) {
        alert('The entered passwords don\'t match. Please type the same password twice.');
    } else {
        sendForm();
    }
}

function sendForm() {
    'use strict';
    var form = document.createElement("form"),
        element1 = document.createElement("input"),
        element2 = document.createElement("input"),
        element3 = document.createElement("input");

    form.className = 'hidden';

    form.method = "POST";
    form.action = baseUrl + '/home/updatepass';
    element1.value = $('#username').val();
    element1.name = 'username';
    element2.value = $('#newPassword').val();
    element2.name = 'password';
    element3.value = $('#hash').val();
    element3.name = 'hash';
    form.appendChild(element1);
    form.appendChild(element2);
    form.appendChild(element3);
    document.body.appendChild(form);
    form.submit();
}