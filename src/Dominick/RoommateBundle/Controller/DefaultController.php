<?php

namespace Dominick\RoommateBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /*
     public function indexAction($name)
     {
         return $this->render('DominickRoommateBundle:Default:index.html.twig', array('name' => $name));
     }
    */
    public function indexAction()
    {
        // Check if they are logged in
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // The same as saying
            // $user = $this->get('security.context')->getToken()->getUser();
            $user = $this->getUser();
            $apartment = $user->getApartment();
            // Send to create apartment if not assigned
            if (empty($apartment)) {
                return $this->redirect($this->generateUrl('apartment_register'));
                // Send to index if they already have an apartment
            } else {

                return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome', array(
                //    'apartment' => $apartment,
                )));
            }

        } else {
            return $this->render('DominickRoommateBundle:Default:index.html.twig', array());
        }

    }

    public function apartmenthomeAction()
    {
        // Get apartment info
        $user = $this->getUser();
        $apartment = $user->getApartment();

        return $this->render('DominickRoommateBundle:Default:apartmenthome.html.twig', array(
            'apartment' => $apartment,
        ));
        //return $this->render('DominickRoommateBundle:Default:apartmenthome.html.twig', array());
    }


    public function loggedinAction()
    {
        $user = $this->getUser();
        $apartment = $user->getApartment();
        // Send to create apartment if not assigned
        if (empty($apartment)) {
            return $this->redirect($this->generateUrl('apartment_register'));
            // Send to index if they already have an apartment
        } else {
            return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome'));
        }
    }
}
