<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use App\DataTransferObject\Credentials;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $auth): Response
    {
        $form = $this->createForm(LoginType::class, new Credentials($auth->getLastUsername()));
        $error = $auth->getLastAuthenticationError();

        if (null !== $error) {
            $form->addError(new FormError($error->getMessage()
            ));
        }

        return $this->render('security/login.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        
    }
}
