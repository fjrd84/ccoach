<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ccoach\Controller;

use Ccoach\Classes\Theory\QuestionsGenerator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\I18n\Translator;
use Ccoach\Classes\Theory\Knowledge;
use Ccoach\Classes\Utilities\UserManagement;

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
        $topUsersThisWeek = $userManagement->getTopUsersThisWeek();
        $viewModel = new ViewModel();
        $viewModel->setVariable('userid', $currentUser->getUserId());
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
        $knowledge->loadKnowledge();
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
        $helpPagesModel->setTemplate('ccoach/index/helpPageEn.phtml');
        $viewModel->addChild($helpPagesModel, 'helpPage');
        return $viewModel;
    }

    public function resultsAction()
    {
        /**
         * todo: save info in session and redirect
         *
             use Zend\Session\Container as SessionContainer;
             $this->session = new SessionContainer('post_supply');
             $this->session->ex = true;
             var_dump($this->session->ex);
         */
        $userManagement = new UserManagement($this->getServiceLocator());
        $answers = json_decode($_POST['answers']);
        // The answers are added to the user profile, returning a JSON that describes the new
        // punctuation.
        $results = json_encode($userManagement->addResults($answers));
        // The user's points of the week are now updated.
        $userManagement->updateWeekPoints();
        // With all the data now available, the view is shown.
        $viewModel = new ViewModel();
        $viewModel->setVariable('results', $results);
        return $viewModel;
    }

    /**
     * It updates the information for an existing user (e.g.: new full name, new email or new password).
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function updateinfoAction(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fullname = $_POST['fullname'];
        $response = $this->getResponse();
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $userManagement = new UserManagement($this->getServiceLocator());
        $currentUser = $userManagement->getCurrentUser();

        // This issues must also be validated on the client side.
        if (!filter_var($username, FILTER_VALIDATE_EMAIL) || !isset($fullname) || $fullname == '') {
            $response->setContent('Invalid email or no user name provided.');
            return $response;
        }

        $currentUserName = $currentUser->getUserId();
        // New email address provided
        if($username !== $currentUserName){
            // If the email already exists...
            $existingUser = $objectManager->createQuery(
                'SELECT u FROM Ccoach\Entity\LoginTable u WHERE u.userName =  \'' . $username . '\'')->getResult();
            if(count($existingUser)>0){
                $response->setContent('The provided email is already in use.');
                return $response;
            }
        }

        $existingUser = $objectManager->createQuery(
            'SELECT u FROM Ccoach\Entity\LoginTable u WHERE u.userName =  \'' . $currentUserName . '\'')->getResult();
        // There must always be a user with this login. Otherwise it wouldn't be logged in.
        // The information of the login table is updated
        $existingUser[0]->setPassword(md5($password));
        $existingUser[0]->setUserName($username);
        $objectManager->persist($existingUser[0]);
        // The information of the users table is updated
        $currentUser->setFullName($fullname);
        $currentUser->setUserId($username);
        $objectManager->persist($currentUser);
        $objectManager->flush();
        $response->setContent('Information updated successfully.');
        return $response;

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

        $user = new \Ccoach\Entity\User();
        $user->setFullName('Paco Porras');
        $objectManager->persist($user); // $user is now "managed"

        $user2 = new \Ccoach\Entity\User();
        $user2->setFullName('Michaël Gallego');
        $objectManager->persist($user2);

        $objectManager->flush(); // commit changes to db

        // Retrieve an object
        $user1 = $objectManager->find('Ccoach\Entity\User', 1);

        var_dump($user1->getFullName()); // Marco Pivetta

        $user2 = $objectManager
            ->getRepository('Ccoach\Entity\User')
            ->findOneBy(array('fullName' => 'Michaël Gallego'));

        var_dump($user2->getFullName()); // Michaël Gallego

        // update an object
        $user = $objectManager->find('Ccoach\Entity\User', 1);

        $user->setFullName('Guilherme Blanco');

        $objectManager->flush();

        // Remove an object
        $user = $objectManager->find('Ccoach\Entity\User', 1);

        $objectManager->remove($user);

        $objectManager->flush();


        die(var_dump($user->getId()));
        */
    }
}
