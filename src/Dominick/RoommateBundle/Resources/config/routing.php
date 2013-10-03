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

return $collection;
