/*global $, baseUrl, jQuery, alert*/
var allTimeScores = true,
    flipTime = 5000;

$(document).ready(function () {
    'use strict';
    
});

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
    window.location = baseUrl + '/index/game';
}

function goTrain(questionType) {
    'use strict';
    $('.mainDiv *').fadeOut();
    $('.loadingMain').fadeIn(300);
    window.location = baseUrl + '/index/game?questionType=' + questionType;
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

// Home functions /////////////////////////////////////////
function backHome(fromClass){
    $('.homeContent.'+fromClass).fadeOut(200, function (){
        $('.homeContent.mainScreen').fadeIn(500);
    });
}

///////////////////////////////////////////

$(window).resize(function () {
    adjustFont();
});

adjustFont();

