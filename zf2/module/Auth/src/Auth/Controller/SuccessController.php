<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SuccessController extends AbstractActionController
{
    protected $sl;

    public function getServiceLocator(){
        return $this->sl;
    }

    public function __construct($sl)
    {
        $this->sl = $sl;
    }

    public function indexAction()
    {
        if (! $this->getServiceLocator()
                 ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        return new ViewModel();
    }
}
