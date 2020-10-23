<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * class RegistrationController
 * @package App\Controller
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function _invoke(Request $request, 
                            EntityManagerInterface $manager,
                            UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $form->get('plainPassword')->getData()));
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('login');
        }
        
        return $this->render('registration/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
