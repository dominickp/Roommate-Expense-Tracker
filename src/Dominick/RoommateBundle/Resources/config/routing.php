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
$collection->add('dominick_roommate_loggedin', new Route('/user', array(
    '_controller' => 'DominickRoommateBundle:Default:loggedin',
)));
$collection->add('dominick_roommate_apartmenthome', new Route('/residence/overview', array(
    '_controller' => 'DominickRoommateBundle:Default:apartmenthome',
)));

# REGISTRATION AND AUTHENTICATION
$collection->add('account_register', new Route('/register', array(
    '_controller' => 'DominickRoommateBundle:User:register',
)));
$collection->add('account_login', new Route('/login', array(
    '_controller' => 'DominickRoommateBundle:User:login',
)));
$collection->add('account_login_check', new Route('/login_check'));
$collection->add('account_logout', new Route('/logout'));

# APARTMENT REGISTRATION
$collection->add('apartment_register', new Route('/residence/new', array(
    '_controller' => 'DominickRoommateBundle:User:apartment',
)));

/* I don't think I need this anymore
$collection->add('account_create', new Route('/register/create', array(
    '_controller' => 'DominickRoommateBundle:User:create',
)));

*/

return $collection;
