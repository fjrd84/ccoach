<?php
require_once "classes/General/Knowledge.php";

/**
 * Runs all tests
 */
function runTests(){
    $knowledge = \classes\General\Knowledge::getInstance();
    $knowledge->readFiles();
    intervalTests();

}

function intervalTests(){
    $knowledge = \classes\General\Knowledge::getInstance();
    $test1 = $knowledge->getDistance("D", "A"); // 3.5
    $test1 = $knowledge->getDistance("A", "D"); // 2.5
    $test1 = $knowledge->getDistance("F", "B"); // 3
    $test1 = $knowledge->getDistance("C", "C"); // 0
    $test1 = $knowledge->getDistance("Eb", "E"); // 0.5
    $test1 = $knowledge->getDistance("Db", "D#"); // 1
    $test1 = $knowledge->getDistance("Db", "A#"); // 4.5
}