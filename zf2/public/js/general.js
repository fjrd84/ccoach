$('.homeLogout').mouseenter(function () {
    'use strict';
    $('#navText').fadeIn('300');
    $('#navText').text('Logout!');
}).mouseleave(function () {
        'use strict';
        $('#navText').fadeOut('50');
    });

$('.showHome').mouseenter(function () {
    'use strict';
    $('#navText').fadeIn('300');
    $('#navText').text('Home!');
}).mouseleave(function () {
        'use strict';
        $('#navText').fadeOut('50');
    });

$('.homePlay').mouseenter(function () {
    'use strict';
    $('#navText').fadeIn('300');
    $('#navText').text('Play!');
}).mouseleave(function () {
        'use strict';
        $('#navText').fadeOut('50');
    });

$('.homeTraining').mouseenter(function () {
    'use strict';
    $('#navText').fadeIn('300');
    $('#navText').text('Training!');
}).mouseleave(function () {
    'use strict';
    $('#navText').fadeOut('50');
});

function showTraining() {
    'use strict';
    $('.home').fadeOut(300,function(){$('.trainingView').fadeIn(600);});
    //$('#homeContainer').flip();
}

function showHome() {
    'use strict';
    $('.trainingView').fadeOut(300,function(){$('.home').fadeIn(600);});
    //$('#homeContainer').flip();
}