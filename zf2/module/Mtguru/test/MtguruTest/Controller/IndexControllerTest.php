<?php

namespace MTGuru\Controller;

use MTGuruTest\Bootstrap;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use MTGuru\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class IndexControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        chdir(dirname(__DIR__). '/../../../..');
        $serviceManager = Bootstrap::getServiceManager();
        $translator = $serviceManager->get('translator');
        $this->controller = new IndexController($translator);
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testKnowledgeCanBeLoaded()
    {
        $knowledge = \MTGuru\Classes\General\Knowledge::getInstance();
        $knowledge->readFiles();
        $allQuestionTypes = $knowledge->getQuestionTypes();
        $this->assertFalse(empty($allQuestionTypes));
    }

    /**
     * @depends testKnowledgeCanBeLoaded
     */
    public function testPossibleChords(){
        $knowledge = \MTGuru\Classes\General\Knowledge::getInstance();
        // All possible chords tests
        $notes = array();
        $notes[] = "C";
        $notes[] = "E";
        $notes[] = "G";
        $notes[] = "B";
        $CMaj7 = $knowledge->getAllPossibleChords($notes, true); // CMaj7
        $this->assertTrue(in_array('CMaj7', $CMaj7));
        $notes[] = "D";
        $notes[] = "F";
        $notes[] = "A";
        $CIonianChords = $knowledge->getAllPossibleChords($notes, false); // All chords of C ionian
        $expectedArray = array( 0 => 'CM',
                                1 => 'CMaj7',
                                2 => 'Em',
                                3 => 'Em7',
                                4 => 'GM',
                                5 => 'G7',
                                6 => 'Bdim',
                                7 => 'Bdim7',
                                8 => 'Dm',
                                9 => 'Dm7',
                                10 => 'FM',
                                11 => 'FMaj7',
                                12 => 'Am',
                                13 => 'Am7');
        $differentElements = count(array_diff($CIonianChords, $expectedArray));
        $this->assertTrue($differentElements===0);
    }

    /**
     * Tests the notes of a generated scale
     * @depends testKnowledgeCanBeLoaded
     */
    public function testNotesChord(){
        $knowledge = \MTGuru\Classes\General\Knowledge::getInstance();
        // Notes of chords tests
        $knowledge->getNotesChord('C#Maj7');
        $knowledge->getNotesChord('Em7');
        $knowledge->getNotesChord('A7');
        $knowledge->getNotesChord('Eb7');
        $knowledge->getNotesChord('G#7');
        $knowledge->getNotesChord('D#M');
    }

    /**
     * Tests the notes of a generated scale
     * @depends testKnowledgeCanBeLoaded
     */
    public function testNotesScale(){
        $knowledge = \MTGuru\Classes\General\Knowledge::getInstance();
        // Notes scales tests
        $DbIonian = $knowledge->getNotesScale('Db', 'ionian');
        $this->assertTrue(in_array('Eb',$DbIonian));
        $fIonian = $knowledge->getNotesScale('F', 'ionian');
        $this->assertTrue(in_array('Bb',$fIonian));
        $cIonian = $knowledge->getNotesScale('C', 'ionian');
        $dDorian = $knowledge->getNotesScale('D', 'dorian');
        $ePhrygian = $knowledge->getNotesScale('E', 'phrygian');
        // C ionian and D dorian have the same notes
        $differentElements = count(array_diff($cIonian, $dDorian));
        $this->assertTrue($differentElements===0);
        $differentElements = count(array_diff($cIonian, $ePhrygian));
        $this->assertTrue($differentElements===0);
    }

    /**
     * Tests some random intervals
     * @depends testKnowledgeCanBeLoaded
     */
    public function testNoteInterval(){
        $knowledge = \MTGuru\Classes\General\Knowledge::getInstance();
        // Note intervals tests
        $this->assertEquals($knowledge->getNoteInterval('C', '3m'),'Eb');
        $this->assertEquals($knowledge->getNoteInterval('C', '3M'),'E');
        $this->assertEquals($knowledge->getNoteInterval('C', '5J'),'G');
        $this->assertEquals($knowledge->getNoteInterval('C', '7m'),'Bb');
        $this->assertEquals($knowledge->getNoteInterval('C', '7M'),'B');
        $this->assertEquals($knowledge->getNoteInterval('A', '4+'),'D#');

    }

    /**
     * Tests some random distances
     * @depends testKnowledgeCanBeLoaded
     */
    public function testDistances(){
        $knowledge = \MTGuru\Classes\General\Knowledge::getInstance();
        // Distance Tests
        $this->assertEquals($knowledge->getDistance("D", "A"),3.5);
        $this->assertEquals($knowledge->getDistance("A", "D"),2.5);
        $this->assertEquals($knowledge->getDistance("F", "B"),3);
        $this->assertEquals($knowledge->getDistance("C", "C"), 0);
        $this->assertEquals($knowledge->getDistance("Eb", "E"), 0.5);
        $this->assertEquals($knowledge->getDistance("Db", "D#"), 1);
        $this->assertEquals($knowledge->getDistance("Db", "A#"), 4.5);
    }
}