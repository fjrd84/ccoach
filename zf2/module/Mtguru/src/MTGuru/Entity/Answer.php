<?php

namespace MTGuru\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Answer {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="answers")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=FALSE)
     */
    protected $userId;

    /**
     * @ORM\ManyToOne(targetEntity="QuestionType", inversedBy="answers")
     * @ORM\JoinColumn(name="questionTypeId", referencedColumnName="id", nullable=FALSE)
     */
    protected $questionTypeId;

    /** @ORM\Column(type="integer") */
    protected $skill;

    /** @ORM\Column(type="integer") */
    protected $right;



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
     * @param mixed $questionId
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;
    }

    /**
     * @param mixed $questionTypeId
     */
    public function setQuestionTypeId($questionTypeId)
    {
        $this->questionTypeId = $questionTypeId;
    }

    /**
     * @return mixed
     */
    public function getQuestionTypeId()
    {
        return $this->questionTypeId;
    }


    /**
     * @param mixed $right
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

    /**
     * @return mixed
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param mixed $skill
     */
    public function setSkill($skill)
    {
        $this->skill = $skill;
    }

    /**
     * @return mixed
     */
    public function getSkill()
    {
        return $this->skill;
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

} 