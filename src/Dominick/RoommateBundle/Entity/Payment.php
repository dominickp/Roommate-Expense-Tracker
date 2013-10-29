<?php

namespace Dominick\RoommateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="payment")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Payment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="paymentsMade")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="paymentsReceived")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
     * @var User
     */
    protected $recipient;

    /**
     * @ORM\Column(type="integer", name="recipient_id", nullable=true)
     * @var integer
     */
    protected $recipientId;

    /**
     * @ORM\Column(type="integer", unique=false)
     */
    private $apartmentId;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @var string
     */
    private $method;

    /**
     * @ORM\Column(type="decimal", scale=2, length=16, nullable=false)
     * @var double
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @var string
     */
    protected $memo;

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
     * @param \Dominick\RoommateBundle\Entity\User $recipient
     * @return $this
     */
    public function setRecipient(User $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return \Dominick\RoommateBundle\Entity\User
     */
    public function getRecipient()
    {
        return $this->recipient;
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

    /**
     * Set memo
     *
     * @param string $memo
     * @return Payment
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;
    
        return $this;
    }

    /**
     * Get memo
     *
     * @return string 
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Payment
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Set recipientId
     *
     * @param integer $recipientId
     * @return Payment
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = $recipientId;
    
        return $this;
    }

    /**
     * Get recipientId
     *
     * @return integer 
     */
    public function getRecipientId()
    {
        return $this->recipientId;
    }
}