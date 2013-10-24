<?php

namespace Dominick\RoommateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="expense")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Expense
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="expenses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="integer", unique=false)
     */
    protected $apartmentId;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     * @var string
     */
    protected $token;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(type="decimal", length=16, nullable=false)
     * @var double
     */
    protected $cost;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $updated;

    public function __construct()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * @param float $cost
     * @return Expense $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
        
        return $this;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return $this
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime("now");
        
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \Dominick\RoommateBundle\Entity\User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        
        return $this;
    }

    /**
     * @return \Dominick\RoommateBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


}