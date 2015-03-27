<?php

namespace Ccoach\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class GameSession {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=FALSE)
     */
    protected $userId;

    /** @ORM\Column (type="integer") */
    protected $points;

    /** @ORM\Column (type="datetime") */
    protected $accessTime;

    /**
     * @param mixed $accessTime
     */
    public function setAccessTime($accessTime)
    {
        $this->accessTime = $accessTime;
    }

    /**
     * @return mixed
     */
    public function getAccessTime()
    {
        return $this->accessTime;
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

} 