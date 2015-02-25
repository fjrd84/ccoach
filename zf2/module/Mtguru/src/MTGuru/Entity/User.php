<?php

namespace MTGuru\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

//use MTGuru\Entity\UserSkill;
/** @ORM\Entity */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $fullName;

    /** @ORM\Column(type="string") */
    protected $userId;

    /** @ORM\Column(type="integer") */
    protected $points;

    /** @ORM\Column(type="integer") */
    protected $pointsThisWeek;

    /** @ORM\Column(type="integer") */
    protected $level;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $lastAccess;

    /** @ORM\OneToMany(targetEntity="Job", mappedBy="person", cascade={"remove"}) */
    //protected $jobs;


    /** @ORM\OneToMany(targetEntity="UserSkill", mappedBy="user", cascade={"remove"}) */
    protected $userSkills;

    public function __construct()
    {
        //$this->jobs = new ArrayCollection();
        $this->userSkills = new ArrayCollection();
    }

    /**
     * It updates the lastAccess field of the current user.
     */
    public function updateLastAccess()
    {
        $this->setLastAccess(new \DateTime());
    }

    /**
     * @param mixed $lastAccess
     */
    public function setLastAccess($lastAccess)
    {
        $this->lastAccess = $lastAccess;
    }

    /**
     * @return mixed
     */
    public function getLastAccess()
    {
        return $this->lastAccess;
    }

    public function getSkills()
    {
        return $this->userSkills->toArray();
    }

    public function addSkill(UserSkill $skill)
    {
        if (!$this->userSkills->contains($skill)) {
            $this->userSkills->add($skill);
        }

        return $this;
    }

    public function removeSkill(UserSkill $skill)
    {
        if ($this->userSkills->contains($skill)) {
            $this->userSkills->removeElement($skill);
        }

        return $this;
    }

    /**  Example
     * public function getCompanies()
     * {
     * return array_map(
     * function ($job) {
     * return $job->getCompany();
     * },
     * $this->jobs->toArray()
     * );
     * }*/

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Function that returns an array with the relevant information about the current user.
     */
    public function getUserParams()
    {
        $result = array();
        $result['fullName'] = $this->fullName;
        $result['points'] = $this->points;
        $result['level'] = $this->level;
        return $result;
    }

    /**
     * @param mixed $pointsThisWeek
     */
    public function setPointsThisWeek($pointsThisWeek)
    {
        $this->pointsThisWeek = $pointsThisWeek;
    }

    /**
     * @return mixed
     */
    public function getPointsThisWeek()
    {
        return $this->pointsThisWeek;
    }

    /**
     * @param mixed $userSkills
     */
    public function setUserSkills($userSkills)
    {
        $this->userSkills = $userSkills;
    }

    /**
     * @return mixed
     */
    public function getUserSkills()
    {
        return $this->userSkills;
    }

}