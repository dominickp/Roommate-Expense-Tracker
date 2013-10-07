<?php

namespace Dominick\RoommateBundle\Controller;

// Stuff for DB insert
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dominick\RoommateBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

use Dominick\RoommateBundle\Form\Type\RegistrationType;
use Dominick\RoommateBundle\Form\Model\Registration;

// Forms
use Symfony\Component\HttpFoundation\Request;





class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('DominickRoommateBundle:Default:index.html.twig', array());
    }
/*    public function registerAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $task = new User();

        $form = $this->createFormBuilder($task)
            ->add('username', 'text')
            ->add('email', 'text')
            ->add('password', 'repeated', array(
                'first_name'  => 'password',
                'second_name' => 'confirm',
                'type'        => 'password',
            ))
            ->add('register', 'submit')
            ->getForm();

        //  If initially loading the page, handleRequest() recognizes that the form was not submitted and does nothing.
        //    When the user submits the form, handleRequest() recognizes this and immediately writes the submitted data
        //    back into the task and dueDate properties of the $task object.
        $form->handleRequest($request);

        //  If the submission was valid, process data and redirect.
        //    isValid() is like isSubmitted() but with a validation check on top of that.
        if ($form->isValid()) {
            $data = $form->getData();   // Put the form data into an object
            $data->is_active = 1;

            // Create salt, then apply it to the plaintext password
            $data->salt = hash("sha256", time().rand());
            $data->password = hash("sha256", $data->password.$data->salt);

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);        // Load data object into doctrine
            $em->flush();               // Execute query
            return $this->redirect($this->generateUrl('task_success'));
        }
        return $this->render('DominickRoommateBundle:Default:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
*/


    public function registerAction()
    {
        $registration = new Registration();
        $form = $this->createForm(new RegistrationType(), $registration, array(
            'action' => $this->generateUrl('account_create'),
        ));

        return $this->render(
            'DominickRoommateBundle:User:register.html.twig',
            array('form' => $form->createView())
        );
    }
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $form = $this->createForm(new RegistrationType(), new Registration());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $registration = $form->getData();

            // cant figure out how to get passwords to hash... currently passwords are being added with plain text. trying to get the password to encrypt itself using the 5 lines below but its not working. i cant modify anything about the password. $registration->password doesnt work when i call it. I tried adding a plainPassword as well in hopes i could alter it from the form and write that back to the database after hashing but that didn't work either.
            $factory = $this->get('security.encoder_factory');
            $user = new User();
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword('ryanpass', $user->getSalt());
            $user->setPassword($password);


            $em->persist($registration->getUser());
            $em->flush();

            return $this->redirect($this->generateUrl('task_success'));
    }

        return $this->render(
            'DominickRoommateBundle:User:register.html.twig',
            array('form' => $form->createView())
        );
    }
}
