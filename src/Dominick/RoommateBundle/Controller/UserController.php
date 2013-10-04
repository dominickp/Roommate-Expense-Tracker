<?php

namespace Dominick\RoommateBundle\Controller;

// Stuff for DB insert
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dominick\RoommateBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

// Forms
use Symfony\Component\HttpFoundation\Request;



class UserController extends Controller
{

    public function indexAction()
    {
        return $this->render('DominickRoommateBundle:Default:index.html.twig', array());
    }
    public function registerAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $task = new User();

        $form = $this->createFormBuilder($task)
            ->add('name', 'text')
            ->add('email', 'text')
            ->add('password', 'repeated', array(
                'first_name'  => 'password',
                'second_name' => 'confirm',
                'type'        => 'password',
            ))
            ->add('register', 'submit')
            ->getForm();

        /*  If initially loading the page, handleRequest() recognizes that the form was not submitted and does nothing.
            When the user submits the form, handleRequest() recognizes this and immediately writes the submitted data
            back into the task and dueDate properties of the $task object.  */
        $form->handleRequest($request);

        /*  If the submission was valid, process data and redirect.
            isValid() is like isSubmitted() but with a validation check on top of that.  */
        if ($form->isValid()) {
            $data = $form->getData();   // Put the form data into an object
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);        // Load data object into doctrine
            $em->flush();               // Execute query
            return $this->redirect($this->generateUrl('task_success'));
        }
        return $this->render('DominickRoommateBundle:Default:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
