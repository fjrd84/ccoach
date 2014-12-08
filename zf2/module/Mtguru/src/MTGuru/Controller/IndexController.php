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

    public function trainingAction()
    {
        $knowledge = Knowledge::getInstance();
        $knowledge->readFiles();
        $_SESSION['questionTypes'] = $knowledge->getQuestionTypes($this->translator);;
        return new ViewModel();
    }
}
