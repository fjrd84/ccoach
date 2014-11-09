<?php
require_once "classes/General/Knowledge.php";

/**
 * Runs all tests
 */
function runTests(){
    $knowledge = \classes\General\Knowledge::getInstance();
    $knowledge->readFiles();
    distanceTests();
    noteIntervalTests();
    noteScalesTests();
    noteChordTests();
    possibleChordsTests();
}

function possibleChordsTests(){
    $knowledge = \classes\General\Knowledge::getInstance();
    $notes = array();
    $notes[] = "C";
    $notes[] = "E";
    $notes[] = "G";
    $notes[] = "B";
    $test4 = $knowledge->getAllPossibleChords($notes, true); // CMaj7
    $notes[] = "D";
    $notes[] = "F";
    $notes[] = "A";
    $test4 = $knowledge->getAllPossibleChords($notes, false); // All chords of C ionian
}

function noteChordTests(){
    $knowledge = \classes\General\Knowledge::getInstance();
    $test3 = $knowledge->getNotesChord('C#Maj7');
    $test3 = $knowledge->getNotesChord('Em7');
    $test3 = $knowledge->getNotesChord('A7');
    $test3 = $knowledge->getNotesChord('Eb7');
    $test3 = $knowledge->getNotesChord('G#7');
    $test3 = $knowledge->getNotesChord('D#M');
}

function noteScalesTests(){
    $knowledge = \classes\General\Knowledge::getInstance();
    $test2 = $knowledge->getNotesScale('Db', 'ionian');
    $test2 = $knowledge->getNotesScale('F', 'ionian');
    $test2 = $knowledge->getNotesScale('D', 'dorian');
    $test2 = $knowledge->getNotesScale('D', 'ionian');
}

function noteIntervalTests(){
    $knowledge = \classes\General\Knowledge::getInstance();
    $test2 = $knowledge->getNoteInterval('C','3m'); // Eb
    $test2 = $knowledge->getNoteInterval('C','3M'); // E
    $test2 = $knowledge->getNoteInterval('C','5J'); // G
    $test2 = $knowledge->getNoteInterval('C','7m'); // Bb
    $test2 = $knowledge->getNoteInterval('C','7M'); // B
    $test2 = $knowledge->getNoteInterval('A','4+'); // D#
}

function distanceTests(){
    $knowledge = \classes\General\Knowledge::getInstance();
    $test1 = $knowledge->getDistance("D", "A"); // 3.5
    $test1 = $knowledge->getDistance("A", "D"); // 2.5
    $test1 = $knowledge->getDistance("F", "B"); // 3
    $test1 = $knowledge->getDistance("C", "C"); // 0
    $test1 = $knowledge->getDistance("Eb", "E"); // 0.5
    $test1 = $knowledge->getDistance("Db", "D#"); // 1
    $test1 = $knowledge->getDistance("Db", "A#"); // 4.5
}