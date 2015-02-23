<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace MTGuru\Controller;

use MTGuru\Classes\Theory\QuestionsGenerator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\I18n\Translator;
use MTGuru\Classes\Theory\Knowledge;
use MTGuru\Classes\Utilities\UserManagement;

class IndexController extends AbstractActionController
{

    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function indexAction()
    {
        $userManagement = new UserManagement($this->getServiceLocator());
        $currentUser = $userManagement->getCurrentUser();
        if ($currentUser == null) {
            return $this->redirect()->toRoute('login');
        }

        $questionTypes = $userManagement->getQuestionTypes();
        $topUsers = $userManagement->getTopUsers();
        $topUsersThisWeek = $userManagement->getTopUsersThisWeek();; // Until this information is ready, we'll use the same list for both
        $viewModel = new ViewModel();
        $viewModel->setVariable('ident', $currentUser->getFullName());
        $viewModel->setVariable('numPoints', $currentUser->getPoints());
        $viewModel->setVariable('currentLevel', $currentUser->getLevel());
        $viewModel->setVariable('questionTypes', $questionTypes);
        $viewModel->setVariable('topUsers', $topUsers);
        $viewModel->setVariable('topUsersThisWeek', $topUsersThisWeek);



        return $viewModel;
    }

    public function trainingAction()
    {
        $knowledge = Knowledge::getInstance();
        $knowledge->readFiles();
        $questionTypes = $knowledge->getQuestionTypes($this->translator);
        $userManagement = new UserManagement($this->getServiceLocator());
        $currentUser = $userManagement->getCurrentUser();
        if ($currentUser == null) {
            $this->redirect()->toRoute('login');
            return;
        }
        $viewModel = new ViewModel();
        $viewModel->setVariable('ident', $currentUser->getFullName());
        $viewModel->setVariable('numPoints', $currentUser->getPoints());
        $viewModel->setVariable('currentLevel', $currentUser->getLevel());
        $viewModel->setVariable('questionTypes', $questionTypes);

        return $viewModel;
    }

    public function gameAction()
    {
        $userManagement = new UserManagement($this->getServiceLocator());
        $currentUser = $userManagement->getCurrentUser();
        if ($currentUser == null) {
            $this->redirect()->toRoute('login');
            return;
        }
        $viewModel = new ViewModel();
        $viewModel->setVariable('ident', $currentUser->getFullName());
        $viewModel->setVariable('numPoints', $currentUser->getPoints());
        $viewModel->setVariable('currentLevel', $currentUser->getLevel());
        // Create a model for the help pages
        $helpPagesModel = new ViewModel();
        // Set the help pages template. Todo: select the language!
        $helpPagesModel->setTemplate('mt-guru/index/helpPageEn.phtml');
        $viewModel->addChild($helpPagesModel, 'helpPage');
        return $viewModel;
    }

    public function resultsAction()
    {
        $userManagement = new UserManagement($this->getServiceLocator());
        $answers = json_decode($_POST['answers']);
        $numAnswers = count($answers);
        $means = array();
        for ($indexAnswer = 0; $indexAnswer < $numAnswers; $indexAnswer++) {
            $questionType = $answers[$indexAnswer][0];
            $numResults = count($answers[$indexAnswer]);
            if (!isset($means[$questionType])) {
                $means[$questionType] = array();
                $means[$questionType]['questionType'] = $questionType;
                $means[$questionType]['numAnswers'] = 0;
                $means[$questionType]['right'] = 0;
            }
            for ($indexResult = 1; $indexResult < $numResults; $indexResult++) {
                $means[$questionType]['numAnswers'] += 1;
                $means[$questionType]['right'] += $answers[$indexAnswer][$indexResult];
            }
        }
        foreach($means as $results){
            $userManagement->addResults($results['questionType'],$results['numAnswers'],$results['right']);
        }
        $results = json_encode($means);
        $viewModel = new ViewModel();
        $viewModel->setVariable('results', $results);
        return $viewModel;
    }

    /**
     * Only some doctrine examples!! Never run!!
     */
    public function doctrineAction()
    {
        /*
        $objectManager = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $user = new \MTGuru\Entity\User();
        $user->setFullName('Paco Porras');
        $objectManager->persist($user); // $user is now "managed"

        $user2 = new \MTGuru\Entity\User();
        $user2->setFullName('Michaël Gallego');
        $objectManager->persist($user2);

        $objectManager->flush(); // commit changes to db

        // Retrieve an object
        $user1 = $objectManager->find('MTGuru\Entity\User', 1);

        var_dump($user1->getFullName()); // Marco Pivetta

        $user2 = $objectManager
            ->getRepository('MTGuru\Entity\User')
            ->findOneBy(array('fullName' => 'Michaël Gallego'));

        var_dump($user2->getFullName()); // Michaël Gallego

        // update an object
        $user = $objectManager->find('MTGuru\Entity\User', 1);

        $user->setFullName('Guilherme Blanco');

        $objectManager->flush();

        // Remove an object
        $user = $objectManager->find('MTGuru\Entity\User', 1);

        $objectManager->remove($user);

        $objectManager->flush();


        die(var_dump($user->getId()));
        */
    }
}
