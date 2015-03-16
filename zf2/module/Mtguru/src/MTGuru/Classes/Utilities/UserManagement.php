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
            $currentUser->setPointsThisWeek(0);
            $this->objectManager->persist($currentUser);
            $this->objectManager->flush();
        }
        $this->currentUser = $currentUser;
        return $currentUser;
    }

    /**
     * It returns an array with information about the best performing users (overall).
     * At least 5 users are needed. If less than 5 users are found, empty objects will be created to fill in the blanks.
     */
    public function getTopUsers()
    {
        $allUsers = $this->objectManager->getRepository('MTGuru\Entity\User')->findBy(array(), array('points' => 'desc'));
        $numUsers = count($allUsers);
        for ($i = 0; $i < 5 - $numUsers; $i += 1) {
            array_push($allUsers, new \MTGuru\Entity\User());
        }
        return $allUsers;
    }

    /**
     * It returns an array with information about the best performing users (this week).
     * At least 5 users are needed. If less than 5 users are found, empty objects will be created to fill in the blanks.
     * @return mixed
     */
    public function getTopUsersThisWeek()
    {
        //$allUsers = $this->objectManager->getRepository('MTGuru\Entity\User')->findBy(array(), array('pointsThisWeek' => 'desc'));
        $aWeekAgo = new \DateTime();
        $aWeekAgo->sub(new \DateInterval("P7D"));
        // Find the active users within the last seven days
        $allUsers = $this->objectManager->createQuery(
            'SELECT u FROM MTGuru\Entity\User u WHERE u.lastAccess > ' . $aWeekAgo->format('Y-m-d') .
            ' ORDER BY u.pointsThisWeek DESC')->getResult();
        $numUsers = count($allUsers);
        for ($i = 0; $i < 5 - $numUsers; $i += 1) {
            array_push($allUsers, new \MTGuru\Entity\User());
        }
        return $allUsers;
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
            ->orderBy('qt.level', 'ASC')
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
                    // The starting skill for any question type will be -1. It will let the theory appear.
                    $userSkill->setCurrentSkill(-1);
                    $userSkill->setNumberOfAnswers(0);
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
     * @param $results
     * @return int The information of the current session.
     */
    public function addResults($results)
    {
        $answersPerType = array();
        $numAnswers = count($results);
        $totalAttempts = 0;
        $totalTime = 0;
        // If the results seem to be hacked, they're not used
        if ($this->resultsHacked($results)) {
            return -1;
        }
        // The last question should be of type 'extraInformation'.
        if ($results[$numAnswers - 1]->type != 'extraInformation') {
            return -1;
        } else {
            $points = $results[$numAnswers - 1]->points;
        }
        // The current user and its skills are retrieved.
        if ($this->currentUser == null) {
            $this->getCurrentUser();
        }
        // No points will be added to the guest user.
        if ($this->currentUser->getUserId() === 'guest') {
            return;
        }
        $updatedSkills = $this->getUpdatedSkills();
        $initialUserPoints = $this->currentUser->getPoints();
        $initialUserLevel = $this->currentUser->getLevel();

        // The results of the session will be analyzed (summed up by type)
        for ($i = 0; $i < $numAnswers - 1; $i += 1) {
            if ($results[$i]->solutionShown) {
                continue; // When the solution has been shown, the answer is not accounted.
            }
            $currentType = $results[$i]->type;
            if (!isset($answersPerType[$currentType])) {
                $answersPerType[$currentType] = array();
                $answersPerType[$currentType]['attempts'] = 0;
                $answersPerType[$currentType]['timeLeft'] = 0;
                $answersPerType[$currentType]['numberOfAnswers'] = 0;
            }
            $totalAttempts += $results[$i]->attemptsCount;
            $totalTime += $results[$i]->timeLeft / 50;
            $answersPerType[$currentType]['attempts'] += $results[$i]->attemptsCount;
            $answersPerType[$currentType]['timeLeft'] += $results[$i]->timeLeft / 50;
            $answersPerType[$currentType]['numberOfAnswers'] += 1;
        }

        // The total session points are calculated.
        $sessionPoints = $points * (($totalTime) / ($totalAttempts + 1));
        $finalUserPoints = $sessionPoints + $initialUserPoints;

        // The user skills are now updated
        foreach ($updatedSkills as $currentSkill) {
            $questionType = $currentSkill->getQuestionType()->getQuestionIdent();
            if (!isset($answersPerType[$questionType])) {
                continue; // There have not been right answers for this question type
            }
            $totalAnswers = $currentSkill->getNumberOfAnswers() + $answersPerType[$questionType]['numberOfAnswers'];
            $currentSkill->setCurrentSkill($this->skillForRightAnswers($totalAnswers));
            $currentSkill->setNumberOfAnswers($totalAnswers); // The number of right answers for the current skill is updated.
        }

        // An entry for the current session is created
        $currentSession = new \MTGuru\Entity\GameSession();
        $currentSession->setUserId($this->currentUser);
        $currentSession->setPoints($sessionPoints);
        $currentSession->setAccessTime(new \DateTime());
        $this->objectManager->persist($currentSession);

        // Finally, the user profile is updated
        $this->currentUser->setPoints($finalUserPoints);
        // The user may have advanced to a new level
        $this->currentUser->setLevel($this->levelForPoints($finalUserPoints));
        // The last access of the user is updated.
        $this->currentUser->updateLastAccess();
        $this->objectManager->flush();

        // The information of the session is returned
        $sessionInfo = ['initialPoints' => $initialUserPoints,
            'finalPoints' => $finalUserPoints,
            'initialLevel' => $initialUserLevel,
            'finalLevel' => $this->currentUser->getLevel(),
            'gamePoints' => $points,
            'totalTime' => $totalTime,
            'totalRepetitions' => $totalAttempts,
            'sessionPoints' => $sessionPoints];
        return $sessionInfo;
    }

    /**
     * It checks if the results seem to be hacked.
     * @param $results
     * @return bool
     */
    public function resultsHacked($results)
    {
        // Todo: add security check with a hash (for not repeating results!)
        // Check if the time and number of points are within the limits
        return false;
    }

    public function updateWeekPoints()
    {
        $aWeekAgo = new \DateTime();
        $aWeekAgo->sub(new \DateInterval("P7D"));
        // Find the sessions of the last seven days
        $gameSessions = $this->objectManager->createQuery(
            'SELECT u FROM MTGuru\Entity\GameSession u WHERE u.userId =  ' .
            $this->currentUser->getId() . ' AND u.accessTime > ' . $aWeekAgo->format('Y-m-d'))->getResult();
        $numSessions = count($gameSessions);
        $sumPoints = 0;
        // Sum the session points for the last seven days.
        for ($i = 0; $i < $numSessions; $i += 1) {
            $sumPoints += $gameSessions[$i]->getPoints();
        }
        // Update the user.
        $this->currentUser->setPointsThisWeek($sumPoints);
        $this->objectManager->flush();
    }

    /**
     * Returns a number between 0 and 2 determining the skill for a number of right answers
     */
    public function skillForRightAnswers($numAnswers)
    {
        if ($numAnswers == 0) { // No right answers yet. The theory will be shown at the beginning.
            return -1;
        }
        if ($numAnswers < 20) {
            return 0;
        }
        if ($numAnswers < 50) {
            return 1;
        }
        return 2;
    }

    /**
     * This function returns the level that belongs to a certain number of points.
     * @param $points
     * @return int
     */
    public function levelForPoints($points)
    {
        if ($points > 200000) {
            return 10;
        } elseif ($points > 100000) {
            return 9;
        } elseif ($points > 50000) {
            return 8;
        } elseif ($points > 30000) {
            return 7;
        } elseif ($points > 20000) {
            return 6;
        } elseif ($points > 15000) {
            return 5;
        } elseif ($points > 10000) {
            return 4;
        } elseif ($points > 5000) {
            return 3;
        } elseif ($points > 2000) {
            return 2;
        } elseif ($points > 1000) {
            return 1;
        } else {
            return 0;
        }
    }
} 