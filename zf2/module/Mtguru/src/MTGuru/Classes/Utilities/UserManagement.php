<?php

namespace MTGuru\Classes\Utilities;

class UserManagement
{

    private $serviceLocator;
    private $currentUser;
    private $objectManager;
    private $expertRate = 0.8;
    private $mediumRate = 0.4;

    public function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->objectManager = $serviceLocator
            ->get('Doctrine\ORM\EntityManager');
    }

    /**
     * It returns the currently logged in user
     * @return \MTGuru\Entity\User|null
     */
    public function getCurrentUser()
    {
        $authService = $this->serviceLocator
            ->get('AuthService');
        if (!$authService->hasIdentity()) {
            return null;
        }
        // The userid identifies the user info in the database
        $userId = $authService->getIdentity();

        $currentUser = $this->objectManager->getRepository('MTGuru\Entity\User')->findOneBy(array('userId' => $userId));
        // If the current user is not present in the database yet, it will be now created.
        if ($currentUser == null) {
            // TODO: Log this user creation. Normally, it shouldn't be performed here.
            $currentUser = new \MTGuru\Entity\User();
            $currentUser->setUserId($userId);
            $currentUser->setFullName($userId);
            $currentUser->setLevel(0);
            $currentUser->setPoints(0);
            $this->objectManager->persist($currentUser);
            $this->objectManager->flush();
        }
        $this->currentUser = $currentUser;
        return $currentUser;
    }

    /**
     * It returns an array with the available question types for the current user.
     */
    public function getQuestionTypes()
    {
        if ($this->currentUser == null) {
            $this->getCurrentUser();
        }
        $queryBuilder = $this->objectManager->createQueryBuilder();
        // Question types for the level of the current user and below are searched for
        $query = $queryBuilder->select(array('qt'))
            ->from('MTGuru\Entity\QuestionType', 'qt')
            ->where($queryBuilder->expr()->lte('qt.level', $this->currentUser->getLevel()))
            ->getQuery();
        $questionTypes = $query->getResult();
        return $questionTypes;
    }

    /**
     * It returns the skills for the current user, updating them when necessary.
     */
    public function getUpdatedSkills()
    {
        $currentQuestionTypes = $this->getQuestionTypes();
        $currentSkills = $this->currentUser->getSkills();
        $numQuestionTypes = count($currentQuestionTypes);
        $numSkills = count($currentSkills);
        if ($numQuestionTypes > $numSkills) {
            // new skills must be added for the current level
            for ($questionIdentIndex = 0; $questionIdentIndex < $numQuestionTypes; $questionIdentIndex++) {
                $newSkill = true;
                for ($skillIndex = 0; $skillIndex < $numSkills; $skillIndex++) {
                    if ($currentSkills[$skillIndex]->getQuestionType() == $currentQuestionTypes[$questionIdentIndex]) {
                        $newSkill = false;
                    }
                }
                if ($newSkill) {
                    $userSkill = new \MTGuru\Entity\UserSkill();
                    $userSkill->setUser($this->currentUser);
                    // The starting skill for any question type will be 0
                    $userSkill->setCurrentSkill(0);
                    $userSkill->setNumberOfAnswers(0);
                    $userSkill->setNumberRight(0);
                    $userSkill->setQuestionType($currentQuestionTypes[$questionIdentIndex]);
                    $this->objectManager->persist($userSkill);
                    $this->currentUser->addSkill($userSkill);
                    $this->objectManager->flush();
                }
            }
        }
        return $this->currentUser->getSkills();
    }

    /**
     * It adds the results of a game to the database and calculates the new skills,
     * points and level of the user.
     * @param $questionType
     * @param $numAnswers
     * @param $answersRight
     */
    public function addResults($questionType, $numAnswers, $answersRight)
    {
        if ($this->currentUser == null) {
            $this->getCurrentUser();
        }

        $updatedSkills = $this->getUpdatedSkills();
        foreach ($updatedSkills as $currentSkill) {
            if ($currentSkill->getQuestionType()->getQuestionIdent() == $questionType) {
                $currentPoints = $this->currentUser->getPoints() + $answersRight * 10;
                $this->currentUser->setPoints($currentPoints);
                $this->currentUser->setLevel($this->levelForPoints($currentPoints));
                $currentAnswers = $currentSkill->getNumberOfAnswers() + $numAnswers;
                $currentAnswersRight = $currentSkill->getNumberRight() + $answersRight;
                $currentSkill->setNumberOfAnswers($currentAnswers);
                $currentSkill->setNumberRight($currentAnswersRight);
                if (($currentAnswersRight / $currentAnswers) > $this->expertRate) {
                    $currentSkill->setCurrentSkill(2);
                } elseif (($currentAnswersRight / $currentAnswers) > $this->mediumRate) {
                    $currentSkill->setCurrentSkill(1);
                } else {
                    $currentSkill->setCurrentSkill(0);
                }
                $this->objectManager->flush();
                return;
            }
        }
    }

    /**
     * This function returns the level that belongs to a certain number of points.
     * @param $points
     * @return int
     */
    public function levelForPoints($points){
        if($points>10000){
            return 10;
        }elseif($points>100000){
            return 9;
        }elseif($points>50000){
            return 8;
        }elseif($points>30000){
            return 7;
        }elseif($points>20000){
            return 6;
        }elseif($points>15000){
            return 5;
        }elseif($points>10000){
            return 4;
        }elseif($points>5000){
            return 3;
        }elseif($points>2000){
            return 2;
        }elseif($points>1000){
            return 1;
        }else{
            return 0;
        }
    }
} 