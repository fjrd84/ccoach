<?php
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
                    expected: "Dominant"
                    }
                    ]}';