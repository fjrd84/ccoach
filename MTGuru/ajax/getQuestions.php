<?php
$test = 1123;
$thisIsAnotherThing = "";
/*

More questions:
Intervals
Given the notes, say the chord.
Which of the given notes does not belong to the scale?
Which scale sounds now?
Which chord/interval sounds now?

*/
echo '{
        questions: [
                    {
                    key: "C",
                    mode: "ionian",
                    text: "Tell me the notes of this chord",
                    type: "notesOfChord",
                    chord: "CMaj7",
                    expected: "C,E,G,B"
                    },
                    {
                    key: "C",
                    mode: "ionian",
                    text: "Select the right degree",
                    type: "degreeOfChord",
                    chord: "Am7",
                    expected: "VI"
                    },
                    {
                    key: "C",
                    mode: "ionian",
                    text: "Select the right area",
                    type: "areaOfChord",
                    chord: "G7",
                    expected: "D"
                    },
                    {
                    key: "C",
                    mode: "ionian",
                    text: "Select the right chord substitution",
                    type: "substitutionOfChord",
                    chord: "CMaj7",
                    expected: "Am7"
                    }
                    ]}';