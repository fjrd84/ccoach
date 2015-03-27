<?php

namespace Ccoach\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class UserSkill {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="skills")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=FALSE)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="QuestionType", inversedBy="skills")
     * @ORM\JoinColumn(name="questionTypeId", referencedColumnName="id", nullable=FALSE)
     */
    protected $questionType;

    /** @ORM\Column(type="integer") */
    protected $currentSkill;

    /** @ORM\Column(type="integer") */
    protected $numberOfAnswers;

    /**
     * @param mixed $currentSkill
     */
    public function setCurrentSkill($currentSkill)
    {
        $this->currentSkill = $currentSkill;
    }

    /**
     * @return mixed
     */
    public function getCurrentSkill()
    {
        return $this->currentSkill;
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
     * @param mixed $questionTypeId
     */
    public function setQuestionType($questionType)
    {
        $this->questionType = $questionType;
    }

    /**
     * @return mixed
     */
    public function getQuestionType()
    {
        return $this->questionType;
    }

    /**
     * @param mixed $userId
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $numberOfAnswers
     */
    public function setNumberOfAnswers($numberOfAnswers)
    {
        $this->numberOfAnswers = $numberOfAnswers;
    }

    /**
     * @return mixed
     */
    public function getNumberOfAnswers()
    {
        return $this->numberOfAnswers;
    }

}