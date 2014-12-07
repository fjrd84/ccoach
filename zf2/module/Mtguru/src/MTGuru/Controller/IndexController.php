<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace MTGuru\Controller;

use MTGuru\Classes\General\QuestionsGenerator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $questionsGenerator = new QuestionsGenerator();
        // TODO: Move the initial configuration to the framework
        $questionsGenerator->loadMigrationConfig();
        return new ViewModel();
    }
    public function gameAction()
    {
        $questionsGenerator = new QuestionsGenerator();
        // TODO: Move the initial configuration to the framework
        $questionsGenerator->loadMigrationConfig();
        return new ViewModel();
    }

    public function trainingAction()
    {
        $questionsGenerator = new QuestionsGenerator();
        // TODO: Move the initial configuration to the framework
        $questionsGenerator->loadMigrationConfig();
        $_SESSION['questionTypes'] = $questionsGenerator->knowledge->getQuestionTypes();;
        return new ViewModel();
    }
}
