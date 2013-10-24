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

        // Load up the user & apartment IDs so I can use it for the new expense
        $securityContext = $this->get('security.context');
        //$currentUser = $securityContext->getUser();
        $currentUser = $this->getUser();
        $currentApartment = $currentUser->getApartment();

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

        if ($form->isValid()) {
            $exp = $form->getData();

            // Set user
            $exp->setUser($currentUser);

            // Set the apartmentId
            $exp->setApartmentId($currentApartment->getId());

            // Generate a token value so this expense can be referenced later
            $exp->setToken(substr(md5($exp->getDescription() . time()), 0, 8));

            $em->persist($exp);
            $em->flush();

            // Send to the apartment overview page, now that the apartment has been created an tied to them.
            return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome'));
        }
        return $this->render('DominickRoommateBundle:Expense:newexpense.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function browseExpenseAction()
    {
        // Load up the user & apartment IDs so I can use it for limiting the browse results
        //$securityContext = $this->get('security.context');
        $securityContext = $this->container->get('security.context');
        $currentUser = $this->getUser();
        $currentApartmentId = $currentUser->getApartment()->getId();

        $aptExpense = $this->getDoctrine()
            ->getRepository('DominickRoommateBundle:Expense')
        //    ->findAll();
            ->findBy(
                array('apartmentId' => $currentApartmentId), // $where
                array('created' => 'ASC'), // $orderBy
                999, // $limit
                0 // $offset
            );

   //     var_dump($aptExpense);
        $users = $this->getDoctrine()
            ->getRepository('DominickRoommateBundle:User')
            ->findAll();


        // Tally some totals
        $totals = array(
            'cost' => 0,
            'expenses' => 0
        );
        foreach ($aptExpense as $exp) {
            $totals['cost'] += $exp->getCost();
            $totals['expenses']++;
        }

        if (!$aptExpense) {
            return $this->render('DominickRoommateBundle:Expense:browseexpense.html.twig', array(
             //   'results' => '',
            ));
        }

        // Get $users so I can pass it to the view
//        $users = $this->getDoctrine()
//            ->getRepository('DominickRoommateBundle:User')
//            ->findAll();

        //return var_dump($aptExpense);
        return $this->render('DominickRoommateBundle:Expense:browseexpense.html.twig', array(
            'expenses' => $aptExpense,
            'totals' => $totals,
            'users' => $users,
        ));
    }
}