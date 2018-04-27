/*global $, baseUrl, jQuery, alert*/
var allTimeScores = true,
    flipTime = 5000;

function flipHighScores() {
    'use strict';
    if (allTimeScores) {
        $('#topUsers .allTimes').fadeOut(200, function () {
            $('#topUsers .thisWeek').fadeIn(300);

        });
    } else {
        $('#topUsers .thisWeek').fadeOut(200, function () {
            $('#topUsers .allTimes').fadeIn(300);

        });
    }
    allTimeScores = !allTimeScores;
    setTimeout(function () {
        flipHighScores();
    }, flipTime);
}

setTimeout(function () {
    'use strict';
    flipHighScores();
}, flipTime);

function showInfoText(text) {
    'use strict';
    $('#navText').empty();
    $('#navText').append('<div class="newText fitText" style="display:none">' + text + '</div>');
    $('#navText .newText').fadeIn('300');

}

$('.homeLogout, #logoutTool').mouseenter(function () {
    'use strict';
    showInfoText('Logout!');
}).mouseleave(function () {
        'use strict';
        $('#navText .newText').fadeOut('50');
    });

$('.showHome').mouseenter(function () {
    'use strict';
    showInfoText('Home!');
}).mouseleave(function () {
        'use strict';
        $('#navText .newText').fadeOut('50');
    });

$('.homePlay').mouseenter(function () {
    'use strict';
    showInfoText('Play!');
}).mouseleave(function () {
        'use strict';
        $('#navText .newText').fadeOut('50');
    });

$('.homeTraining').mouseenter(function () {
    'use strict';
    showInfoText('Training!');
}).mouseleave(function () {
        'use strict';
        $('#navText .newText').fadeOut('50');
    });

$('#configTool').mouseenter(function () {
    'use strict';
    showInfoText('Configuration!');
}).mouseleave(function () {
        'use strict';
        $('#navText .newText').fadeOut('50');
    });

function showTraining() {
    'use strict';
    $('.home').fadeOut(300, function () {
        $('.trainingView').fadeIn(600);
    });
}

function showHome() {
    'use strict';
    $('.trainingView').fadeOut(300, function () {
        $('.home').fadeIn(600);

    });
    //$('#homeContainer').flip();
}

function goPlay() {
    'use strict';
    $('.mainDiv *').fadeOut();
    $('.loadingMain').fadeIn(300);
    window.location = baseUrl + '/ccoach/index/game';
}

function goTrain(questionType) {
    'use strict';
    $('.mainDiv *').fadeOut();
    $('.loadingMain').fadeIn(300);
    window.location = baseUrl + '/ccoach/index/game?questionType=' + questionType;
}

function logoutMe() {
    'use strict';
    $('.mainDiv *').fadeOut();
    $('.loadingMain').fadeIn(300);
    window.location = baseUrl + '/home/logout';
}

function adjustFont() {
    'use strict';
    var newSize = $(window).width() / 40;
    //newSize = Math.floor(newSize);
    $('body').css('font-size', newSize + 'px');
}

function showConfigScreen() {
    'use strict';
    $('#configScreen').fadeIn();
}

function hideConfigScreen() {
    'use strict';
    $('#configScreen').fadeOut();
}

function validateEmail(email) {
    'use strict';
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function updateMyInfo() {
    'use strict';
    var password = $('#passwordInput').val(),
        username = $('#emailInput').val(),
        fullname = $('#fullNameInput').val(),
        confirmPass = $('#confirmPasswordInput').val();
    if(!validateEmail(username)){
        alert('You must enter a valid email address.');
        return;
    }
    if (password.length < 5 || fullname.length < 5) {
        alert('The password and your full name must be at least five characters long.');
        return;
    }
    if (password !== confirmPass) {
        alert('The entered passwords don\'t match. Please type the same password twice.');
        return;
    } 
    sendUpdateInfo(username, password, fullname);
}

function sendUpdateInfo(username, password, fullname){
    'use strict';
        $.post(baseUrl + '/ccoach/index/updateinfo', { 'username': username, 'password': password, 'fullname' : fullname })
        .done(function (data) {
            if(data.indexOf('success')>0){
                logoutMe();
            }else{
                alert(data);
            }
        });
}

///////////////////////////////////////////

$(window).resize(function () {
    'use strict';
    adjustFont();
});

adjustFont();

