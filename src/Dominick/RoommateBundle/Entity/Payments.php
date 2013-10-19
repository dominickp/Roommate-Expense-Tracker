<?php
namespace Dominick\RoommateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="payment")
 */
class Payment
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
    protected $method;
    /**
     * @ORM\Column(type="decimal", length=16, nullable=false)
     */
    protected $amount;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;
}