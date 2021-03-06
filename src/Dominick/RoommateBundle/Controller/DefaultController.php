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
    public function getTotals()
    {

        $currentUser = $this->getUser();

        // Get apartment info
        $apartment = $currentUser->getApartment();
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

        $currentApartmentId = $currentUser->getApartment()->getId();

        // Tally some totals which I'll use later
        $totals = array(
            'cost' => 0,
            'expenses' => 0,
            'roommates' => 0,
            'roommateCost' => 0,
            'myPaymentTotal' => 0,
            'myExpenseTotal' => 0,
            'myReceivedTotal' => 0,
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
            if($exp->getUser()->getId() == $currentUser->getId()){
                $totals['myExpenseTotal'] += $exp->getCost();
            }
        }

        foreach ($aptPayments as $pay) {
            // Get the total the current user has paid out
            if($pay->getUser()->getId() == $currentUser->getId()){
                $totals['myPaymentTotal'] += $pay->getAmount();
            }
            // Get the total the current user has received
            if($pay->getRecipient()->getId() == $currentUser->getId()){
                $totals['myReceivedTotal'] += $pay->getAmount();
            }
        }

        // Build out Roommate Data
        $totals['roommate_data'] = array(); // initializing array
        foreach($users as $roommate){
            // initializing arrays
            $totals['roommate_data'][$roommate->getId()] = array(
                'totalPayments' => 0,
                'totalExpenses' => 0,
                'numberOfExpenses' =>0,
                'totalPaid' =>0,
                'totalReceived' =>0,
                'balance' =>0,
            );

            $totals['roommate_data'][$roommate->getId()]['fullName'] = $roommate->getFullname();
            // Go through payments
            foreach ($aptPayments as $rpay) {
                // Get the total the current user has paid out
                if($rpay->getUser()->getId() == $roommate->getId()){
                    $totals['roommate_data'][$roommate->getId()]['totalPayments'] += $rpay->getAmount();
                }
                // Get the total the current user has received
                if($rpay->getRecipient()->getId() == $roommate->getId()){
                    $totals['roommate_data'][$roommate->getId()]['totalReceived'] += $rpay->getAmount();
                }
            }
            // Go through expenses
            foreach ($aptExpense as $rexp) {
                if($rexp->getUser()->getId() == $roommate->getId()){
                    $totals['roommate_data'][$roommate->getId()]['totalExpenses'] += $rexp->getCost();
                    $totals['roommate_data'][$roommate->getId()]['numberOfExpenses']++;
                }
            }
            // Set balance values
            $totals['roommate_data'][$roommate->getId()]['totalPaid'] = $totals['roommate_data'][$roommate->getId()]['totalPayments']+$totals['roommate_data'][$roommate->getId()]['totalExpenses'];
            // Balance = totalPaid - totalReceived - roommateCost
            $totals['roommate_data'][$roommate->getId()]['balance'] = $totals['roommate_data'][$roommate->getId()]['totalPaid']-$totals['roommate_data'][$roommate->getId()]['totalReceived']-$totals['roommateCost'];
            if($totals['roommate_data'][$roommate->getId()]['balance'] < 0){
                $totals['roommate_data'][$roommate->getId()]['balanceNegative'] = true;
            } else {
                $totals['roommate_data'][$roommate->getId()]['balanceNegative'] = false;
            }


        }
        // Remove self from the roommate_data array
        unset($totals['roommate_data'][$currentUser->getId()]);

        // Calculate a few more things using some basic math
        $totals['myTotalPaid'] = $totals['myPaymentTotal']+$totals['myExpenseTotal'];
        $totals['myBalance'] = $totals['myTotalPaid']-$totals['myReceivedTotal']-$totals['roommateCost'];
        if($totals['myBalance'] < 0){
            $totals['balanceNegative'] = true;
        } else {
            $totals['balanceNegative'] = false;
        }
        return $totals;
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

        $totals = $this->getTotals();

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
