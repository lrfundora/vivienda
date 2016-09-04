<?php

namespace AppBundle\Controller\Reportes;

use AppBundle\Form\Type\SimpleFormType;
use AppBundle\Form\Type\ReporteControlFondoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/reportes")
 */
class ReportesCFController extends Controller
{
    /**
     * @Route("/control-de-fondo", name="_reportes_cf")
     */
    public function indexAction(Request $request)
    {
        $results = '';
        $form = $this->createForm(ReporteControlFondoType::class);
        $formInfo = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_cf_arrendamiento_info')]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $results = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->DynamicReport($data['columna'], $data['coincidencia'], $data['criterio'], $data['baja']);
            $baja = $data['baja'] ? 'Baja: Si' : 'Baja: No';
            if ($data['coincidencia'] == 1) {
                $coincide = 'Contiene';
            } else if ($data['coincidencia'] == 2) {
                $coincide = 'Comienza con';
            } else if ($data['coincidencia'] == 3) {
                $coincide = 'Exactamente';
            } else {
                $coincide = 'Termina con';
            }
            $criterio = $data['criterio'] == '' ? 'vacío' : $data['criterio'];
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Crear reporte en CONTROL DE FONDO: [Variables de búsqueda: {' . $data['columna'] . ', ' . $coincide . ', ' . $criterio . ', ' . $baja . '}, Resultados: {' . sizeof($results) . '}]');

        }

        return $this->render('reportes/reportes_cf.html.twig',
            array(
                'menu' => 'reportes',
                'submenu' => 'reportes_cf',
                'form' => $form->createView(),
                'formInfo' => $formInfo->createView(),
                'results' => $results
            ));
    }
}
