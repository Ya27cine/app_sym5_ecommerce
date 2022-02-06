<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function index(Request $request, AuthenticationUtils $utils): Response
    {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'errors' => $utils->getLastAuthenticationError()
        ]);
    }
}
