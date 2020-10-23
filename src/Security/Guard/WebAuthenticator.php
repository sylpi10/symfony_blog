<?php

namespace App\Security\Guard;

use App\Form\LoginType;
use App\DataTransferObject\Credentials;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

/**
 * class WebAuthenticator
 * @package App\Security\Guard
 */

 class WebAuthenticator extends AbstractFormLoginAuthenticator
{
    private UrlGeneratorInterface $urlGenerator;
    private FormFactoryInterface $formFactory;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UrlGeneratorInterface $urlGenerator,
                                FormFactoryInterface $formFactory,
                                UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate("login");
    }

    /**
     * @inheritdoc
     */
    // do i get a POSt method and am i on the login page ??
    public function supports(Request $request)
    {
        return $request->isMethod(Request::METHOD_POST) 
            && $request->attributes->get("_route") === "login";
    }

    /**
     * @inheritdoc
     */
    // if form valid -> return credentials
    public function getCredentials(Request $request)
    {
        $credentials = new Credentials();
        $form = $this->formFactory->create(LoginType::class, $credentials)->handleRequest($request);
        if (!$form->isValid()) {
            return;
        }
        return $credentials;
    }
    /**
     * @param Credentials $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface|void|null
     */
    // get user with username (email)
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials->getUsername());
    }

    /**
     * @param Credentials $credentials
     * @param UserIterface $user
     * @return bool
     */
    // true or false if password is correct
    public function checkCredentials($credentials, UserInterface $user)
    {
       if ($valid = $this->userPasswordEncoder->isPasswordValid($user, $credentials->getPassword())) {
           return true;
       }
       throw new AuthenticationException("Password wrong ");
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
       return new RedirectResponse($this->urlGenerator->generate("home"));
    }


}