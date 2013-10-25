<?php

namespace Dominick\RoommateBundle\Controller;

// Stuff for DB insert
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

        $form = $this->createFormBuilder($pay)
            ->add('memo', 'text')
            //->add('recipient_id', 'text')
            ->add('method', 'choice', array(
                'choices' => array('cash' => 'Cash', 'check' => 'Check', 'bank_transfer' => 'Bank Transfer'),
                'preferred_choices' => array('check'),
            ))
            ->add('amount', 'money', array(
                'currency' => 'USD',
            ))
            ->add('Save', 'submit')
            ->getForm();

        $form->handleRequest($request);

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
        }
        return $this->render('DominickRoommateBundle:Payment:newpayment.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
?>