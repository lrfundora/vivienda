<?php

namespace AppBundle\Controller\Security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LogController extends Controller
{
    /**
     * @Route("/seguridad/trazas", name="_seguridad_log")
     */
    public function indexAction()
    {
        $logs = $this->getDoctrine()->getRepository('AppBundle:Log')->findAll();
        return $this->render(
            'security/logs.html.twig',
            array(
                'menu' => 'seguridad',
                'submenu' => 'seguridad_log',
                'logs' => $logs
            )
        );
    }
}
