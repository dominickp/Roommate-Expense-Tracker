<?php

namespace Dominick\RoommateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $following;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $role;

    /**
     * @ORM\Get id
     *
     * @ORM\@return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Set name
     *
     * @ORM\@param string $name
     * @ORM\@return Users
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @ORM\Get name
     *
     * @ORM\@return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @ORM\Set email
     *
     * @ORM\@param string $email
     * @ORM\@return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @ORM\Get email
     *
     * @ORM\@return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @ORM\Set password
     *
     * @ORM\@param string $password
     * @ORM\@return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @ORM\Get password
     *
     * @ORM\@return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @ORM\Set avatar
     *
     * @ORM\@param string $avatar
     * @ORM\@return Users
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @ORM\Get avatar
     *
     * @ORM\@return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @ORM\Set following
     *
     * @ORM\@param string $following
     * @ORM\@return Users
     */
    public function setFollowing($following)
    {
        $this->following = $following;

        return $this;
    }

    /**
     * @ORM\Get following
     *
     * @ORM\@return string
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @ORM\Set role
     *
     * @ORM\@param string $role
     * @ORM\@return Users
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @ORM\Get role
     *
     * @ORM\@return string
     */
    public function getRole()
    {
        return $this->role;
    }
}
