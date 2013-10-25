<?php

namespace Dominick\RoommateBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dominick\RoommateBundle\Entity\Expense;
use Dominick\RoommateBundle\Entity\User;
use Dominick\RoommateBundle\Entity\Apartment;

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
        // Check if they are logged in
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // The same as saying
            // $user = $this->get('security.context')->getToken()->getUser();
            $user = $this->getUser();
            $apartment = $user->getApartment();
            // Send to create apartment if not assigned
            if (empty($apartment)) {
                return $this->redirect($this->generateUrl('apartment_register'));
                // Send to index if they already have an apartment
            } else {

                return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome', array(
                //    'apartment' => $apartment,
                )));
            }

        } else {
            return $this->render('DominickRoommateBundle:Default:index.html.twig', array());
        }

    }
    public function apartmenthomeAction()
    {
        // Get apartment info
        $user = $this->getUser();
        $apartment = $user->getApartment();
        $currentApartmentId = $apartment->getId();

        // Get roommate info from
        $roommates = $this->getDoctrine()
            ->getRepository('DominickRoommateBundle:User')
            //    ->findAll();
            ->findBy(
                array('apartmentId' => $currentApartmentId), // $where
                array('created' => 'ASC'), // $orderBy
                999, // $limit
                0 // $offset
            );

        $currentUser = $this->getUser();
        $currentApartmentId = $currentUser->getApartment()->getId();

        // Tally some totals which I'll use later
        $totals = array(
            'cost' => 0,
            'expenses' => 0,
            'roommates' => 0,
            'roommateCost' => 0,
            'myPaymentTotal' => 0,
            'myExpenseTotal' => 0,
        );

        // Pull all of the expenses tied to this apartment
        $aptExpense = $this->getDoctrine()
            ->getRepository('DominickRoommateBundle:Expense')
            //    ->findAll();
            ->findBy(
                array('apartmentId' => $currentApartmentId), // $where
                array('created' => 'ASC'), // $orderBy
                999, // $limit
                0 // $offset
            );

        // Pull all of the payments tied to this apartment
        $aptPayments = $this->getDoctrine()
            ->getRepository('DominickRoommateBundle:Payment')
            //    ->findAll();
            ->findBy(
                array('apartmentId' => $currentApartmentId), // $where
                array('created' => 'ASC'), // $orderBy
                999, // $limit
                0 // $offset
            );

        // Pull User to use for converting IDs to names in the view
        $users = $this->getDoctrine()
            ->getRepository('DominickRoommateBundle:User')
            //    ->findAll();
            ->findBy(
                array('apartmentId' => $currentApartmentId), // $where
                array('created' => 'ASC'), // $orderBy
                999, // $limit
                0 // $offset
            );

        // To calculate stuff like the cost per roommate, I'll need to tally the number of roommates
        $totals['roommates'] = count($users);

        // Iterate through the expenses to tally totals and set cost per roommate
        foreach ($aptExpense as &$exp) {
            $totals['cost'] += $exp->getCost();
            $totals['expenses']++;
            $exp->perRoommateCost = $exp->getCost()/$totals['roommates'];
            $totals['roommateCost'] += $exp->perRoommateCost;
            // Get the total the current user has registered for paid expenses
            if($exp->getUser()->getId() == $user->getId()){
                $totals['myExpenseTotal'] += $exp->getCost();
            }
        }

        foreach ($aptPayments as $pay) {
            //$exp->perRoommateCost = $exp->getCost()/$totals['roommates'];
            //$totals['roommateCost'] += $exp->perRoommateCost;

            // Get the total the current user has paid out
            if($pay->getUser()->getId() == $user->getId()){
                $totals['myPaymentTotal'] += $pay->getAmount();
            }
        }

        // Calculate a few more things using some basic math
        $totals['myTotalPaid'] = $totals['myPaymentTotal']+$totals['myExpenseTotal'];
        $totals['myBalance'] = $totals['myTotalPaid']-$totals['roommateCost'];
        if($totals['myBalance'] < 0){
            $totals['balanceNegative'] = true;
        } else {
            $totals['balanceNegative'] = false;
        }

        //  debugging
        //  var_dump($totals);

        return $this->render('DominickRoommateBundle:Default:apartmenthome.html.twig', array(
            'apartment' => $apartment,
            'roommates' => $roommates,
            'totals'    => $totals,
        ));
        //return $this->render('DominickRoommateBundle:Default:apartmenthome.html.twig', array());
    }


    public function loggedinAction()
    {
        $user = $this->getUser();
        $apartment = $user->getApartment();
        // Send to create apartment if not assigned
        if (empty($apartment)) {
            return $this->redirect($this->generateUrl('apartment_register'));
            // Send to index if they already have an apartment
        } else {
            return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome'));
        }
    }
}
