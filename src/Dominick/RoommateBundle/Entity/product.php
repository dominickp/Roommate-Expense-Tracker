<?php
// src/Acme/StoreBundle/Entity/Product.php
namespace Acme\StoreBundle\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Product
* @ORM\Table(name="product")
*/
class Product
{
/**
* @ORM\Id
* @ORM\Column(type="integer")
* @ORM\GeneratedValue(strategy="AUTO")
*/
protected $id;

/**
* @ORM\Column(type="string", length=100)
*/
protected $name;

/**
* @ORM\Column(type="decimal", scale=2)
*/
protected $price;

/**
* @ORM\Column(type="text")
*/
protected $description;
}