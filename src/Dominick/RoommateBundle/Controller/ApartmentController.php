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
            ->add('state', 'text', array('max_length' => 2))
            ->add('zip', 'text', array('max_length' => 5))
            ->add('pin', 'text', array('max_length' => 8))

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

    public function setApartmentIdAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $apartment = $this->getDoctrine()->getRepository('DominickRoommateBundle:Apartment')->findOneBy(array('id'=>$id));
        $apartmentPin = $apartment->getPin();
        // Null out the Pin in the form
        $apartment->setPin('');
        $form = $this->createFormBuilder($apartment)
            ->add('pin', 'text')

            ->add('Save', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $formy = $request->request->get('form');
            if($formy['pin'] == $apartmentPin){
                $user = $this->getUser();
                $user->setApartment($apartment);
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('dominick_roommate_apartmenthome'));
            } else {
                return $this->render('DominickRoommateBundle:Apartment:verifyapartment.html.twig', array(
                    'form' => $form->createView(),
                    'apartment' => $apartment,
                    'fail' => true,
                ));
            }
        }

        return $this->render('DominickRoommateBundle:Apartment:verifyapartment.html.twig', array(
            'form' => $form->createView(),
            'apartment' => $apartment,
        ));


    }
}