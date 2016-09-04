<?php

namespace AppBundle\Controller\Security;

use AppBundle\Entity\Log;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login_route")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        if($error){
            $log = new Log();
            $log
                ->setUsuario($lastUsername)
                ->setTrabajador('-')
                ->setRole('-')
                ->setAccion('Iniciar sesión: [Operación fallida]');
            $em=$this->getDoctrine()->getManager();
            $em->persist($log);
            $em->flush();
        }
        return $this->render(
            'security/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        // Este controlador no será ejecutado,
        // pero es usado por el sistema se seguridad
    }

}
