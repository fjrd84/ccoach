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
use Zend\Mvc\I18n\Translator;
use MTGuru\Classes\General\Knowledge;

class IndexController extends AbstractActionController
{

    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function indexAction()
    {
        return new ViewModel();
    }
    public function gameAction()
    {
        return new ViewModel();
    }

    public function doctrineAction(){
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


        die(var_dump($user->getId())); // yes, I'm lazy
    }

    public function trainingAction()
    {
        $knowledge = Knowledge::getInstance();
        $knowledge->readFiles();
        $_SESSION['questionTypes'] = $knowledge->getQuestionTypes($this->translator);;
        return new ViewModel();
    }
}
