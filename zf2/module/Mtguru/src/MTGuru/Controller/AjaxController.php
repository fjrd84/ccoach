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

class AjaxController extends AbstractActionController
{
    public function gameAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $questionsGenerator = new QuestionsGenerator();
        // TODO: Move the initial configuration to the framework
        $questionsGenerator->loadMigrationConfig();
        $question = $questionsGenerator->generateQuestion();
        //if ($request->isPost()) {
        //$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'new_note_id' => "test")));
        $response->setContent($question);
        return $response;
    }
}
