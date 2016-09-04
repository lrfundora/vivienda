<?php

namespace AppBundle\Controller\Recepcion;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/recepcion")
 */
class GuiaController extends Controller
{
    /**
     * @Route("/guia-telefonica", name="_recepcion_guia")
     */
    public function indexAction()
    {
        return $this->render('recepcion/guia_telefonica.html.twig',
            array(
                'menu' => 'recepcion',
                'submenu' => 'recepcion_guia',
                'entidades' => $this->getDoctrine()->getRepository('AppBundle:Entidad')->findAll()
            ));
    }
}
