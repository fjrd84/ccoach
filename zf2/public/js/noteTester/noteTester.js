var noteTester = {
    useSharps: 'true',
    currentNotes: [],
    addListeners: function () {
        'use strict';
        $('#noteTesterPiano .note').click(function () {
            noteTester.pressNote($(this));
        });
    },
    pressNote: function (noteObject) {
        'use strict';
        var note = noteObject.data('note'),
            extraClass = '';
        if (!noteTester.useSharps) {
            note = noteTester.sharpToFlat(note);
        }
        if (note.indexOf('b') > -1) {
            extraClass = 'flat';
        } else if (note.indexOf('#') > -1) {
            extraClass = 'sharp';
        }
        noteTester.showNote(note);
        // The note object is selected/unselected
        if (noteObject.hasClass('selected')) {
            noteObject.removeClass('selected');
            noteTester.addNote(note);
            $('#noteTesterScore .note.' + note + '.' + extraClass).hide();
            $('#noteTesterScore .note.' + note).removeClass('flat');
            $('#noteTesterScore .note.' + note).removeClass('sharp');
        } else {
            noteObject.addClass('selected');
            noteTester.removeNote(note);
            $('#noteTesterScore .note.' + note).show();
            $('#noteTesterScore .note.' + note).addClass(extraClass);
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
    showNote: function (note) {
        'use strict';
        // It shows a note on the display panel
        $('#noteTesterFeedback').empty();
        $('#noteTesterFeedback').append('<div class="feedbackNote" style="display:none">' + note + '</div>');
        $('.feedbackNote').fadeIn(500);
        setTimeout(function () {
            $('.feedbackNote').fadeOut(300);
        }, 800);
    },
    simulatePress: function (note) {
        'use strict';
        var cssClass = (noteTester.flatToSharp(note)).replace('#', 'Sharp');
        noteTester.pressNote($('#noteTesterPiano .note.' + cssClass));
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