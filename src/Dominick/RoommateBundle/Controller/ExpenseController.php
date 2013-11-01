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
                'choices' => array(
                    'entertainment' => 'Entertainment / Fun',
                    'moving' => 'Moving',
                    'utility' => 'Utilities',
                    'household' => 'Household',
                    'groceries' => 'Groceries',
                    'furniture' => 'Furniture / Decoration',
                    'other' => 'Other',
                ),
                'preferred_choices' => array('other'),
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
            //var_dump($form);
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
        $securityContext = $this->container->get('security.context');
        $currentUser = $this->getUser();
        $currentApartmentId = $currentUser->getApartment()->getId();

        // Tally some totals which I'll use later
        $totals = array(
            'cost' => 0,
            'expenses' => 0,
            'roommates' => 0,
            'roommateCost' => 0
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
        }

        // If there are no expenses, send to the same view without the variable
        if (!$aptExpense) {
            return $this->render('DominickRoommateBundle:Expense:browseexpense.html.twig', array(
             //   'results' => '',
            ));
        }

        // If we have expenses, send all the data to the view
        return $this->render('DominickRoommateBundle:Expense:browseexpense.html.twig', array(
            'expenses' => $aptExpense,
            'totals' => $totals,
            'users' => $users,
        ));
    }
    public function editExpenseAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $expense = $this->getDoctrine()->getRepository('DominickRoommateBundle:Expense')->findOneBy(array('id'=>$id));
        $user = $this->getUser();

        $form = $this->createFormBuilder($expense)
            ->add('description', 'text')
            ->add('type', 'choice', array(
                'choices' => array(
                    'entertainment' => 'Entertainment / Fun',
                    'moving' => 'Moving',
                    'utility' => 'Utilities',
                    'household' => 'Household',
                    'groceries' => 'Groceries',
                    'furniture' => 'Furniture / Decoration',
                    'other' => 'Other',
                ),
                'preferred_choices' => array('other'),
            ))
            ->add('cost', 'money', array(
                'currency' => 'USD',
            ))
            ->add('Update', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $exp = $form->getData();

            $em->persist($exp);
            $em->flush();
            return $this->redirect($this->generateUrl('expense_browse'));
        }

        // Checking to see if you are actually editing an expense from your own apartment. If not, you get the boot!
        if($user->getApartment()->getId() == $expense->getApartmentId()){
            return $this->render('DominickRoommateBundle:Expense:editexpense.html.twig', array(
                'form' => $form->createView(),
                'expense' => $expense,
            ));
        } else {
            return $this->redirect($this->generateUrl('expense_browse'));
        }
    }
    public function deleteExpenseAction(Request $request, $id, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $expense = $this->getDoctrine()->getRepository('DominickRoommateBundle:Expense')->findOneBy(array('token'=>$token, 'id'=>$id));
        $user = $this->getUser();

        // Checking to see if you are actually deleting an expense from your own apartment. If not, you get the boot!
        if($user->getApartment()->getId() == $expense->getApartmentId()){
            $em->remove($expense);
            $em->flush();
            //echo $expense->getDescription();
            return $this->redirect($this->generateUrl('expense_browse'));
        } else {
            return $this->redirect($this->generateUrl('expense_browse'));
        }
    }
}