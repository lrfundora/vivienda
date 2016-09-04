<?php

namespace AppBundle\Controller\RHumano;

use AppBundle\Form\Type\SimpleFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/rh")
 */
class PlantillaBajasController extends Controller
{
    /**
     * @Route("/bajas-laborales", name="_rh_bajas")
     */
    public function bajasAction()
    {
        $trabajadores = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresBajas();
        $form = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_rh_bajas_info')));

        if (!$trabajadores) {
            $this->addFlash('info', 'No hay trabajadores que han causado baja.');
        }

        return $this->render('recursos_humano/bajas_laborales.html.twig',
            array(
                'menu' => 'rh',
                'submenu' => 'rh_bajas',
                'form' => $form->createView(),
                'trabajadores' => $trabajadores
            ));
    }

    /**
     * @Route("/bajas-laborales/info", name="_rh_bajas_info")
     */
    public function bajasInfoAction(Request $request)
    {
        $id = $request->request->get('simple_form')['id'];
        if (!$id) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_rh_bajas');
        }

        $trabajador = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($id);

        if (!$trabajador) {
            $this->addFlash('info', 'El trabajador del cual solicitó información no existe.');
            return $this->redirectToRoute('_rh_bajas');
        }

        $utils = $this->get('app.utils');
        $utils->saveLog($this->getUser(), 'Consultar información de baja del trabajador: ['.$trabajador->getNombreCompleto().']');

        return $this->render('recursos_humano/bajas_laborales_info.html.twig',
            array(
                'menu' => 'rh',
                'submenu' => 'rh_bajas',
                'trabajador' => $trabajador
            ));
    }
}
