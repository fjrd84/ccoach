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

    public function testGeneralTheory(){
        $knowledge = \MTGuru\Classes\General\Knowledge::getInstance();
        $knowledge->readFiles();

        // Todo: add assertions and divide into different tests
        // All possible chords tests
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
        // Notes of chords tests
        $knowledge->getNotesChord('C#Maj7');
        $knowledge->getNotesChord('Em7');
        $knowledge->getNotesChord('A7');
        $knowledge->getNotesChord('Eb7');
        $knowledge->getNotesChord('G#7');
        $knowledge->getNotesChord('D#M');

        // Notes scales tests
        $knowledge->getNotesScale('Db', 'ionian');
        $knowledge->getNotesScale('F', 'ionian');
        $knowledge->getNotesScale('D', 'dorian');
        $knowledge->getNotesScale('D', 'ionian');

        // Note intervals tests
        $this->assertEquals($knowledge->getNoteInterval('C', '3m'),'Eb');
        $this->assertEquals($knowledge->getNoteInterval('C', '3M'),'E');
        $this->assertEquals($knowledge->getNoteInterval('C', '5J'),'G');
        $this->assertEquals($knowledge->getNoteInterval('C', '7m'),'Bb');
        $this->assertEquals($knowledge->getNoteInterval('C', '7M'),'B');
        $this->assertEquals($knowledge->getNoteInterval('A', '4+'),'D#');

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