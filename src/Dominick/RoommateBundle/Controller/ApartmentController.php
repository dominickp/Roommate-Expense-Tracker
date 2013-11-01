<?php

namespace Dominick\RoommateBundle\Controller;

// Stuff for DB insert
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dominick\RoommateBundle\Entity\User;
use Dominick\RoommateBundle\Entity\Apartment;
use Dominick\RoommateBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Response;

// Login/Security
use Symfony\Component\Security\Core\SecurityContext;

// Forms
use Symfony\Component\HttpFoundation\Request;

class ApartmentController extends Controller
{
    public function indexAction()
    {
        return $this->render('DominickRoommateBundle:Default:index.html.twig', array());
    }

    public function newApartmentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $apt = new Apartment();

        // This is how you auto fill form data
        // $task->setTask('Write a blog post');

        $form = $this->createFormBuilder($apt)
            ->add('nickname', 'text')
            ->add('address1', 'text')
            ->add('address2', 'text', array('required' => false))
            ->add('city', 'text')
            ->add('state', 'text')
            ->add('zip', 'text')
            ->add('pin', 'text')

            ->add('Save', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $apt = $form->getData();

            $user = $this->getUser();
            $user->setApartment($apt);

            $em->persist($apt);
            $em->flush();
            // Attempt to get the ID
            $aptId = $apt->getId();
            /* NOW USING NEW FUNCTION setApartmentIdAction FOR THIS
                // Load up the user ID so I can inject the apartment ID into the user's row
                $currentUser = $this->get('security.context')->getToken()->getUser();
                $currentUserId = $currentUser->getId();
                // Use the entity manager to get my User entity and find the user of the ID I have
                $user = $em->getRepository('DominickRoommateBundle:User')->find($currentUserId);
                if (!$user) {
                // I'll have to figure out how to properly throw an error later. But if no user can be found with that ID, then this should be an error.
                }
                //$user = new User();
                $user->setApartmentId($aptId);
                $em->persist($user);
                $em->flush();
            */
            //$this->setApartmentIdAction($aptId);

            // Send to the apartment overview page, now that the apartment has been created an tied to them.
            return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome'));
        }
        return $this->render('DominickRoommateBundle:Apartment:newapartment.html.twig', array(
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

    public function setApartmentIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $apartment = $this->getDoctrine()->getRepository('DominickRoommateBundle:Apartment')->find($id);
        $user = $this->getUser();
        $user->setApartment($apartment);

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome'));
    }
}