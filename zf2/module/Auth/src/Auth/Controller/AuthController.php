<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;

use Auth\Model\User;

class AuthController extends AbstractActionController
{
    protected $form;
    protected $storage;
    protected $authservice;

    protected $sl;

    public function getServiceLocator()
    {
        return $this->sl;
    }

    public function __construct($sl)
    {
        $this->sl = $sl;
    }

    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()
                ->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
                ->get('Auth\Model\MyAuthStorage');
        }

        return $this->storage;
    }

    public function getForm()
    {
        if (!$this->form) {
            $user = new User();
            $builder = new AnnotationBuilder();
            $this->form = $builder->createForm($user);
        }

        return $this->form;
    }

    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('success');
        }

        $form = $this->getForm();

        return array(
            'form' => $form,
            'messages' => $this->flashmessenger()->getMessages()
        );
    }

    public function authenticateAction()
    {
        $form = $this->getForm();
        $redirect = 'login';

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                //check authentication...
                $this->getAuthService()->getAdapter()
                    ->setIdentity($request->getPost('username'))
                    ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();
                foreach ($result->getMessages() as $message) {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addMessage($message);
                }

                if ($result->isValid()) {
                    $redirect = 'index';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1) {
                        $this->getSessionStorage()
                            ->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                    $this->getAuthService()->getStorage()->write($request->getPost('username'));
                }
            }
        }

        return $this->redirect()->toRoute($redirect);
    }

    public function logoutAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            $this->getSessionStorage()->forgetMe();
            $this->getAuthService()->clearIdentity();
            $this->flashmessenger()->addMessage("You've been logged out");
        }

        return $this->redirect()->toRoute('login');
    }

    /**
     * Action for creating a new user
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function newuserAction()
    {
        $error = false;
        $messages = array();
        $username = $_POST['username'];
        $password = $_POST['password'];

        $userFullName = explode("@", $username, 2)[0];

        if (!filter_var($username, FILTER_VALIDATE_EMAIL) || !isset($userFullName) || $userFullName == '') {
            array_push($messages, "You must enter a valid e-mail address.");
            $error = true;
        }
        if ($password == '' || $password == null) {
            array_push($messages, "Your password cannot be empty.");
            $error = true;
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $existingUser = $objectManager->createQuery(
            'SELECT u FROM Ccoach\Entity\LoginTable u WHERE u.userName =  \'' . $username . '\'')->getResult();

        if (count($existingUser) > 0) {
            array_push($messages, "This user already exists.");
            $error = true;
        }

        $response = $this->getResponse();

        if (!$error) {
            $newUser = new \Ccoach\Entity\LoginTable();
            $newUser->setUserName($username);
            $newUser->setPassword(md5($password));
            $objectManager->persist($newUser);
            $objectManager->flush();
            $currentUser = new \Ccoach\Entity\User();
            $currentUser->setUserId($username);
            $currentUser->setFullName($userFullName);
            $currentUser->setLevel(0);
            $currentUser->setPoints(0);
            $currentUser->setPointsThisWeek(0);
            $objectManager->persist($currentUser);
            $objectManager->flush();
            $response->setContent('success');
        } else {
            $response->setContent(json_encode($messages));
        }

        return $response;
    }

    public function jdonadoAction()
    {
    }

    /**
     * It sends an email to a user who forgot his/her password with a link to reset it.
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function forgotpassAction()
    {
        $error = false;
        $messages = array();
        $username = $_POST['username'];

        $userFullName = explode("@", $username, 2)[0];

        if (!filter_var($username, FILTER_VALIDATE_EMAIL) || !isset($userFullName) || $userFullName == '') {
            array_push($messages, "You must enter a valid e-mail address.");
            $error = true;
        }
        $response = $this->getResponse();
        if (!$error) {
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $existingUser = $objectManager->createQuery(
                'SELECT u FROM Ccoach\Entity\LoginTable u WHERE u.userName =  \'' . $username . '\'')->getResult();
            if (count($existingUser) == 0) {
                $response->setContent('success');
                return $response;
            }
            $md5hash = $existingUser[0]->getPassword();
            $linkToNewPass = "http://www.cassettecoach.com/home/requestnewpass?user=".$username."&hash=".$md5hash;
            // send email with pass
            $msg = "Message automatically generated from CassetteCoach.com.\n Your email in Cassette Coach is " . $username .
                ". \nIf you want to reset your password, visit the following URL:" .
                $linkToNewPass . "\n. If you didn't request this message, just ignore it. \n\n Thank you for using Cassette Coach!";
            // use wordwrap() if lines are longer than 70 characters
            $msg = wordwrap($msg, 70);
            // send email
            mail($username,"Cassette Coach - Password",$msg);
        } else {
            $response->setContent(json_encode($messages));
        }
        return $response;
    }

    public function requestnewpassAction(){
        // todo
    }
}
