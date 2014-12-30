<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace MTGuru\Controller;

use MTGuru\Classes\Utilities\UserManagement;
use MTGuru\Classes\Theory\QuestionsGenerator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\I18n\Translator;

class AjaxController extends AbstractActionController
{

    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function gameAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $userManagement = new UserManagement($this->getServiceLocator());
        $currentUser = $userManagement->getCurrentUser();
        $questionsGenerator = new QuestionsGenerator($userManagement);
        $question = $questionsGenerator->generateQuestion($this->translator);
        //if ($request->isPost()) {
        //$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'new_note_id' => "test")));
        $response->setContent($question);
        return $response;
    }
}
