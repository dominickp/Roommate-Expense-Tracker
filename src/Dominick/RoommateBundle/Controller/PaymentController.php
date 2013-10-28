<?php

namespace Dominick\RoommateBundle\Controller;

// Stuff for DB insert
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dominick\RoommateBundle\Entity\User;
use Dominick\RoommateBundle\Entity\Apartment;
use Dominick\RoommateBundle\Entity\Expense;
use Dominick\RoommateBundle\Entity\Payment;
use Dominick\RoommateBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Response;

// Login/Security
use Symfony\Component\Security\Core\SecurityContext;

// Forms
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    public function indexAction()
    {
        return $this->render('DominickRoommateBundle:Default:index.html.twig', array());
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
    public function newPaymentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pay = new Payment();

        $currentUser = $this->getUser();
        $currentApartment = $currentUser->getApartment();

        $currentRoommates = $currentApartment->getUsers();
        // Make an array of roommates (bros) which I can send to the form
        $bros = array();
        foreach($currentRoommates as $key => $bro){
            // Remove yourself from the $currentRoommates variable
            if($currentUser->getId() == $bro->getId()){
                unset($currentRoommates[$key]);
            } else {
                $bros[$key] = 'Set';
            }
        }

        $totals = $this->getTotals();

        $form = $this->createFormBuilder($pay)
            ->add('memo', 'text')
            ->add('method', 'choice', array(
                'choices' => array('cash' => 'Cash', 'check' => 'Check', 'bank_transfer' => 'Bank Transfer'),
                'preferred_choices' => array('check'),
            ))
            ->add('amount', 'money', array(
                'currency' => 'USD',
            ))

            ->add('recipient', 'entity', array(
                'class' => 'DominickRoommateBundle:User',
                'choices' => $currentRoommates,
            /*    'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.username', 'ASC');
                },
            */
            ))
            ->add('Save', 'submit')
            ->getForm();

        $form->handleRequest($request);
        if($this->getRequest()->getMethod() == 'POST') {
            if ($form->isValid()) {
                $pay = $form->getData();
    
                // Set user
                $pay->setUser($currentUser);

                // Set the apartmentId
                $pay->setApartmentId($currentApartment->getId());

                $em->persist($pay);
                $em->flush();

                // Send to the apartment overview page, now that the apartment has been created an tied to them.
                return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome'));
            } else { print_r($form->getErrors()); }
        }

        // Generate array to send to the view
        if(!empty($bros)){
            $return = array(
                'form' => $form->createView(),
                'bros' => $bros,
                'totals' => $totals
            );
        } else {
            $return = array(
                'form' => $form->createView(),
            );
        }

        return $this->render('DominickRoommateBundle:Payment:newpayment.html.twig', $return);
    }
}
?>