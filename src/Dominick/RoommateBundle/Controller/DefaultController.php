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
        if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            $user = $this->getUser();
            if(empty($user->apartmentId)){
                // Send to create apartment if not assigned
                return $this->redirect($this->generateUrl('apartment_register'));

            } else {
                // Send to index if they already have an apartment
                return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome'));
            }
        } else {
            return $this->render('DominickRoommateBundle:Default:index.html.twig', array());
        }

    }
    public function apartmenthomeAction()
    {
        return $this->render('DominickRoommateBundle:Default:apartmenthome.html.twig', array());
    }


    public function loggedinAction()
    {
        return $this->render('DominickRoommateBundle:Default:loggedin.html.twig', array());
    }
}
