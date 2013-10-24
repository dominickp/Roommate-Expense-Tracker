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

    public function newExpenseAction()
    {
        $em = $this->getDoctrine()->getManager();
        $exp = new Expense();

        // This is how you auto fill form data
        // $task->setTask('Write a blog post');

        // Load up the user & apartment IDs so I can use it for the new expense
        $securityContext = $this->get('security.context');
        $currentUser = $securityContext->getUser();
        $currentUserId = $currentUser->getId();
        //$currentApartmentId = $currentUser->getApartmentId();

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

            // Set user/apartment IDs before submitting the new expense
            $exp->setUser($currentUser);
            //$exp->setApartmentId($currentApartmentId);

            // Set time because Doctrine sucks
            //$exp->setTimestamp($exp->setUpdated);

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
        //$currentApartmentId = $currentUser->getApartmentId();

        $aptexpense = $this->getDoctrine()
            ->getRepository('DominickRoommateBundle:Expense')
            //->findAll();
            ->findBy(
                array('user' => $currentUser), // $where
                array('created' => 'ASC'), // $orderBy
                999, // $limit
                0 // $offset
            );

        // Tally some totals
        $totals = array(
            'cost' => 0,
            'expenses' => 0
        );
        foreach ($aptexpense as $exp) {
            $totals['cost'] += $exp->getCost();
            $totals['expenses']++;
        }

        if (!$aptexpense) {
            return $this->render('DominickRoommateBundle:Expense:browseexpense.html.twig', array(
                'results' => '',
            ));
        }

        // Get $users so I can pass it to the view
//        $users = $this->getDoctrine()
//            ->getRepository('DominickRoommateBundle:User')
//            ->findAll();

        //return var_dump($aptexpense);
        return $this->render('DominickRoommateBundle:Expense:browseexpense.html.twig', array(
            'expenses' => $aptexpense,
            'totals' => $totals,
            //'users' => $users,
        ));
    }
}