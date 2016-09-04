<?php

namespace AppBundle\Controller\Recepcion;

use AppBundle\Form\Type\SimpleFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/recepcion")
 */
class EstadoTramiteController extends Controller
{
    /**
     * @Route("/estado-de-tramite", name="_recepcion_tramite")
     */
    public function indexAction()
    {
        $form = $this->createForm(SimpleFormType::class);
        return $this->render('recepcion/estado_tramite_buscador.html.twig',
            array(
                'menu' => 'recepcion',
                'submenu' => 'recepcion_tramite',
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/estado-de-tramite/resultado", name="_recepcion_tramite_searching")
     */
    public function searchingAction(Request $request)
    {
        $form = $this->createForm(SimpleFormType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $carnet = $form->getData()['id'];
            $cliente = $this->getDoctrine()->getRepository('AppBundle:Cliente')->findOneBy(['carnet' => $carnet]);
            if ($cliente) {
                $msgLog = $cliente->getCarnet() . ' - ' . $cliente->getNombreCompleto().']';
            } else {
                $msgLog = $carnet . '], sin resultados';
            }
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Consultar información de trámite: [' . $msgLog);
            $form = $this->createForm(SimpleFormType::class);
            return $this->render('recepcion/estado_tramite_resultado.html.twig',
                array(
                    'menu' => 'recepcion',
                    'submenu' => 'recepcion_tramite',
                    'form' => $form->createView(),
                    'cliente' => $cliente
                ));
        }

        $this->addFlash('error', 'Datos no válidos.');
        return new RedirectResponse($this->generateUrl('_recepcion_tramite'));
    }
}
