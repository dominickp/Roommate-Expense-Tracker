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
    '_controller' => 'DominickRoommateBundle:Apartment:newApartment',
)));
$collection->add('apartment_lookup', new Route('/residence/lookup', array(
    '_controller' => 'DominickRoommateBundle:Apartment:lookupApartment',
)));
$collection->add('apartment_set', new Route('/residence/set/{id}', array(
    '_controller' => 'DominickRoommateBundle:Apartment:setApartmentId',
)));

# USER ACCOUNT
$collection->add('account_edit', new Route('/account', array(
    '_controller' => 'DominickRoommateBundle:User:editAccount',
)));

# EXPENSES
$collection->add('expense_new', new Route('/expense/new/', array(
    '_controller' => 'DominickRoommateBundle:Expense:newExpense',
)));
$collection->add('expense_browse', new Route('/expense/browse/', array(
    '_controller' => 'DominickRoommateBundle:Expense:browseExpense',
)));

# PAYMENTS
$collection->add('payment_new', new Route('/payment/new/', array(
    '_controller' => 'DominickRoommateBundle:Payment:newPayment',
)));
$collection->add('payment_browse', new Route('/payment/browse/', array(
    '_controller' => 'DominickRoommateBundle:Payment:browsePayment',
)));

/* I don't think I need this anymore
$collection->add('account_create', new Route('/register/create', array(
    '_controller' => 'DominickRoommateBundle:User:create',
)));

*/

return $collection;
