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

        $default = new DefaultController;
        print_r($default->getTotals($currentUser));
       //var_dump($this->forward('dominick.controller.expense.class:testAction'));


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