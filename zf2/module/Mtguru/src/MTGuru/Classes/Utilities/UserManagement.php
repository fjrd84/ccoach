<?php

namespace MTGuru\Classes\Utilities;

class UserManagement
{

    private $serviceLocator;
    private $currentUser;
    private $objectManager;

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
     * It returns the skills for the current user
     */
    public function getUpdatedSkills()
    {
        $currentQuestionTypes = $this->getQuestionTypes();
        $currentSkills = $this->currentUser->getSkills();
        $numQuestionTypes = count($currentQuestionTypes);
        $numSkills = count($currentSkills);
        if ($numQuestionTypes > $numSkills) {
            // new skills must be added for the current level
            for($questionIdentIndex = 0; $questionIdentIndex < $numQuestionTypes; $questionIdentIndex++){
                $newSkill = true;
                for($skillIndex = 0; $skillIndex < $numSkills; $skillIndex++){
                    if($currentSkills[$skillIndex]->getQuestionTypeId()==$currentQuestionTypes[$questionIdentIndex]->getId()){
                        $newSkill = false;
                    }
                }
                if($newSkill){
                    $userSkill = new \MTGuru\Entity\UserSkill();
                    $userSkill->setUser($this->currentUser);
                    // The starting skill for any question type will be 0
                    $userSkill->setCurrentSkill(0);
                    $userSkill->setQuestionType($currentQuestionTypes[$questionIdentIndex]);
                    $this->objectManager->persist($userSkill);
                    $this->currentUser->addSkill($userSkill);
                    $this->objectManager->flush();
                }
            }
        }
        return $this->currentUser->getSkills();
    }
} 