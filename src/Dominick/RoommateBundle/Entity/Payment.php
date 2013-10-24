<?php

namespace Dominick\RoommateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 */
class Payment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="payments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    protected $user;

    /**
     * @var integer
     */
    private $apartmentId;

    /**
     * @var string
     */
    private $method;

    /**
     * @var float
     */
    private $amount;

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
     * @return Payment
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
     * @return Payment
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
     * Set method
     *
     * @param string $method
     * @return Payment
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return Payment
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

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
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
}