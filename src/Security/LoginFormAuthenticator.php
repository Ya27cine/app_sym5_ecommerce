<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class LoginFormAuthenticator extends AbstractGuardAuthenticator
{

    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get("_route") === 'security_login' &&
         $request->isMethod('POST'); 
    }

    public function getCredentials(Request $request)
    {
        //dd($request);
        return $request->request->get('login');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            return $userProvider->loadUserByUsername( $credentials['email']);
        } catch (UsernameNotFoundException $e) {
            throw new UsernameNotFoundException("Cette adresse email n'est pas connue.");
        }

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // vrefi le mot pass
        // $credentials['password'] => $user->getPassword()
         return 
            $this->encoder->isPasswordValid($user, $credentials['password']) == true ? 
             true : throw new AuthenticationException("Les informations de connecion ne correspondent pas");
         
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        //dd($exception);
        return $request->attributes->set(Security::AUTHENTICATION_ERROR, $exception);

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        //dd("success");

        return new RedirectResponse('/');
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/login');
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
