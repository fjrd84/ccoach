var noteTester = {
    useSharps: true,
    currentNotes: [],
    allowEverything: false, // If false, a note and its sharped or flatted version cannot be pressed at the same time
    // Note: if allowEverything is set to true, the score may not show all the pressed notes.
    addListeners: function () {
        'use strict';
        $('#noteTesterPiano .note').click(function () {
            noteTester.pushKey($(this));
        });
    },
    pushKey: function (keyObject) {
        'use strict';
        var note,
            extraClass = '',
            noteNat;
        if(keyObject.length == 0){
            console.log('Non existing note pressed');
            return;
        }
        note = keyObject.data('note');
        if (!noteTester.useSharps) {
            note = noteTester.sharpToFlat(note);
        }
        if(!noteTester.allowEverything){
            // The complementary note cannot be pressed at the same time 
            noteTester.pullKey(noteTester.findComplementary(note));
        }
        noteNat = note.substr(0, 2);
        if (note.indexOf('b') > -1) {
            extraClass = '.flat';
        } else if (note.indexOf('#') > -1) {
            extraClass = '.sharp';
        }
        noteTester.showNote(note);
        // The note object is selected/unselected
        if (keyObject.hasClass('selected')) {
            keyObject.removeClass('selected');
            noteTester.removeNote(note);
            $('#noteTesterScore .note.' + noteNat + extraClass).hide();
        } else {
            keyObject.addClass('selected');
            noteTester.addNote(note);
            $('#noteTesterScore .note.' + noteNat).addClass(extraClass.slice(1));
            $('#noteTesterScore .note.' + noteNat).show();
        }
    },
    pullKey: function (note) {
        'use strict';
        var cssClass,
            noteNat = note.substr(0, 2),
            noteSharp = noteTester.flatToSharp(note).replace('#', 'Sharp');
        if (note.indexOf('b') > -1) {
            cssClass = 'flat';
        } else if (note.indexOf('#') > -1) {
            cssClass = 'sharp';
        }
        noteTester.removeNote(note);
        // On the piano only sharp notes are represented (thus the conversion to sharp)
        $('#noteTesterPiano .note.' + noteSharp).removeClass('selected');
        $('#noteTesterScore .note.' + noteNat).removeClass(cssClass);
    },
    findComplementary: function (note) {
        'use strict';
        if(note===undefined){
            console.log('Non existing note pressed');
            return;
        }
        if (note.indexOf('b') > -1) {
            return note.replace('b', '');
        } else if (note.indexOf('#') > -1) {
            return note.replace('#', '');
        } else if (noteTester.useSharps) {
            return note + '#';
        } else {
            return note + 'b';
        }
    },
    sharpToFlat: function (note) {
        'use strict';
        switch (note) {
            case 'C4#':
                return 'D4b';
            case 'D4#':
                return 'E4b';
            case 'F4#':
                return 'G4b';
            case 'G4#':
                return 'A4b';
            case 'A4#':
                return 'B4b';
            case 'C5#':
                return 'D5b';
            case 'D5#':
                return 'E5b';
            case 'F5#':
                return 'G5b';
            case 'G5#':
                return 'A5b';
            case 'A5#':
                return 'B5b';
        }
        // If it is not sharp, the same note is returned.
        return note;
    },
    flatToSharp: function (note) {
        'use strict';
        switch (note) {
            case 'D4b':
                return 'C4#';
            case 'E4b':
                return 'D4#';
            case 'G4b':
                return 'F4#';
            case 'A4b':
                return 'G4#';
            case 'B4b':
                return 'A4#';
            case 'D5b':
                return 'C5#';
            case 'E5b':
                return 'D5#';
            case 'G5b':
                return 'F5#';
            case 'A5b':
                return 'G5#';
            case 'B5b':
                return 'A5#';
        }
        // If it is not sharp, the same note is returned.
        return note;
    },
    addNote: function (note) {
        'use strict';
        noteTester.currentNotes.push(note);
    },
    removeNote: function (note) {
        'use strict';
        var index = noteTester.currentNotes.indexOf(note);
        if (index > -1) {
            noteTester.currentNotes.splice(index, 1);
        }
    },
    // It shows a note on the display panel
    showNote: function (note) {
        'use strict';
        $('#noteTesterFeedback').empty();
        $('#noteTesterFeedback').append('<div class="feedbackNote" style="display:none">' + note + '</div>');
        $('.feedbackNote').fadeIn(500);
        setTimeout(function () {
            $('.feedbackNote').fadeOut(300);
        }, 800);
    },
    // Used to simulate pressing a key on the keyboard for a specific note
    pushNote: function (note) {
        'use strict';
        var cssClass = (noteTester.flatToSharp(note)).replace('#', 'Sharp');
        noteTester.pushKey($('#noteTesterPiano .note.' + cssClass));
    },
    resetNotes: function () {
        'use strict';
        noteTester.currentNotes = [];
        $('.note.selected').each(function () {
            $(this).removeClass('selected');
        });
        $('#noteTesterScore .note').hide();
    }
};

noteTester.addListeners();
noteTester.useSharps = false;