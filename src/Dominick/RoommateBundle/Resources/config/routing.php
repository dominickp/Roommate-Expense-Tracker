<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

/*$collection->add('dominick_roommate_homepage', new Route('/hello/{name}', array(
    '_controller' => 'DominickRoommateBundle:Default:index',
)));*/

$collection->add('dominick_roommate_homepage', new Route('/', array(
    '_controller' => 'DominickRoommateBundle:Default:index',
)));
$collection->add('dominick_roommate_admin', new Route('/admin', array(
    '_controller' => 'DominickRoommateBundle:Default:loggedin',
)));

# REGISTRATION AND AUTHENTICATION
// Register Page
$collection->add('dominick_roommate_register', new Route('/register', array(
    '_controller' => 'DominickRoommateBundle:User:register',
)));

return $collection;
