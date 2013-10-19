<?php
namespace Dominick\RoommateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="expense")
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
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    protected $expenseToken;
    /**
     * @ORM\Column(type="numeric", length=16, nullable=true)
     */
    protected $cost;
}