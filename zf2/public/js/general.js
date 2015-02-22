/*global $, baseUrl, jQuery, alert*/
var allTimeScores = true,
    flipTime = 5000;


function flipHighScores(){
    if(allTimeScores){
        $('#topUsers .allTimes').fadeOut(200,function(){
            $('#topUsers .thisWeek').fadeIn(300);
        });
    }else{
        $('#topUsers .thisWeek').fadeOut(200,function(){
            $('#topUsers .allTimes').fadeIn(300);
        });
    }
    allTimeScores = !allTimeScores;
    setTimeout(function(){
        flipHighScores();
    }, flipTime);
}

setTimeout(function(){
    flipHighScores();
}, flipTime);

function showInfoText(text) {
    'use strict';
    $('#navText').empty();
    $('#navText').append('<div class="newText" style="display:none">' + text + '</div>');
    $('#navText .newText').fadeIn('300');
}

$('.homeLogout').mouseenter(function () {
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

function showTraining() {
    'use strict';
    $('.home').fadeOut(300, function () {
        $('.trainingView').fadeIn(600);
    });
    //$('#homeContainer').flip();
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
    $('.loadingMain').fadeIn(300);
    window.location = baseUrl + '/index/game';
}

function logoutMe(){
    'use strict';
    $('.loadingMain').fadeIn(300);
    window.location = baseUrl + '/auth/logout';
}