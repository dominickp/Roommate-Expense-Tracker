<?php

namespace Dominick\Roommate\RoommateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
   /*
    public function indexAction($name)
    {
        return $this->render('DominickRoommateBundle:Default:index.html.twig', array('name' => $name));
    }
   */
    public function indexAction()
    {
        return $this->render('DominickRoommateBundle:Default:index.html.twig', array());
    }
    public function loggedinAction()
    {
        return $this->render('DominickRoommateBundle:Default:loggedin.html.twig', array());
    }
}
