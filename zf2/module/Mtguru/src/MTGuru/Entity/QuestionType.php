<?php

namespace MTGuru\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class QuestionType {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $questionIdent;

    /** @ORM\Column(type="integer") */
    protected $level;

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
     * @param mixed $questionIdent
     */
    public function setQuestionIdent($questionIdent)
    {
        $this->questionIdent = $questionIdent;
    }

    /**
     * @return mixed
     */
    public function getQuestionIdent()
    {
        return $this->questionIdent;
    }

}