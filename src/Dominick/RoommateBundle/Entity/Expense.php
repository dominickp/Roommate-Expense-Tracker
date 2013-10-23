<?php
namespace Dominick\RoommateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="expense")
 * @ORM\HasLifecycleCallbacks
 */
class Expense
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="integer", unique=false)
     */
    protected $userId;
    /**
     * @ORM\Column(type="integer", unique=false)
     */
    protected $apartmentId;
    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     */
    protected $token;
    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    protected $type;
    /**
     * @ORM\Column(type="decimal", length=16, nullable=false)
     */
    protected $cost;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;

    public function __construct()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdated()
    {
        $this->timestamp = new \DateTime("now");
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Expense
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set apartmentId
     *
     * @param integer $apartmentId
     * @return Expense
     */
    public function setApartmentId($apartmentId)
    {
        $this->apartmentId = $apartmentId;
    
        return $this;
    }

    /**
     * Get apartmentId
     *
     * @return integer 
     */
    public function getApartmentId()
    {
        return $this->apartmentId;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Expense
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Expense
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Expense
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set cost
     *
     * @param float $cost
     * @return Expense
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    
        return $this;
    }

    /**
     * Get cost
     *
     * @return float 
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return Expense
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}