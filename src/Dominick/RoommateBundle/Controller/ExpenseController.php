<?php

namespace Dominick\RoommateBundle\Controller;

// Stuff for DB insert
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dominick\RoommateBundle\Entity\User;
use Dominick\RoommateBundle\Entity\Apartment;
use Dominick\RoommateBundle\Entity\Expense;
use Dominick\RoommateBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Response;

// Login/Security
use Symfony\Component\Security\Core\SecurityContext;

// Forms
use Symfony\Component\HttpFoundation\Request;

class ExpenseController extends Controller
{
    public function indexAction()
    {
        return $this->render('DominickRoommateBundle:Default:index.html.twig', array());
    }
    public function newExpenseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $exp = new Expense();

        // This is how you auto fill form data
        // $task->setTask('Write a blog post');

        $form = $this->createFormBuilder($exp)
            ->add('description', 'text')
            ->add('type', 'choice', array(
                'choices' => array('foo' => 'Foo', 'bar' => 'Bar', 'baz' => 'Baz'),
                'preferred_choices' => array('baz'),
            ))
            ->add('cost', 'money', array(
                'currency' => 'USD',
            ))
            ->add('Save', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $exp = $form->getData();
            $em->persist($exp);
            $em->flush();
            // Attempt to get the ID
            $aptId = $exp->getId();

            $this->setApartmentIdAction($aptId);

            // Send to the apartment overview page, now that the apartment has been created an tied to them.
            return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome'));
        }
        return $this->render('DominickRoommateBundle:Expense:newexpense.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function lookupApartmentAction(Request $request)
    {
        $apartment = $this->getDoctrine()
            ->getRepository('DominickRoommateBundle:Apartment')
            ->findAll();

        if (!$apartment) {
            throw $this->createNotFoundException(
                'No results found'
            );
        }
        //return var_dump($apartment);
        return $this->render('DominickRoommateBundle:Apartment:lookupapartment.html.twig', array(
            'results' => $apartment,
        ));
    }
}