<?php

namespace AppBundle\Controller\Reportes;

use AppBundle\Form\Type\ReporteAreaLegalType;
use AppBundle\Form\Type\SimpleFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/reportes")
 */
class ReportesALController extends Controller
{
    /**
     * @Route("/area-legal", name="_reportes_al")
     */
    public function indexAction(Request $request)
    {
        $results = '';
        $form = $this->createForm(ReporteAreaLegalType::class);
        $formInfo = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_arealegal_cliente_info')]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $results = $this->getDoctrine()->getRepository('AppBundle:Tramite')->DynamicReport($data['columna'], $data['coincidencia'], $data['criterio'], $data['completado']);
            $completado = $data['completado'] ? 'Completado: Si' : 'Completado: No';
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
            $utils->saveLog($this->getUser(), 'Crear reporte en ÁREA LEGAL: [Variables de búsqueda: {' . $data['columna'] . ', ' . $coincide . ', ' . $criterio . ', ' . $completado . '}, Resultados: {' . sizeof($results) . '}]');
        }

        return $this->render('reportes/reportes_al.html.twig',
            array(
                'menu' => 'reportes',
                'submenu' => 'reportes_al',
                'form' => $form->createView(),
                'formInfo' => $formInfo->createView(),
                'results' => $results
            ));
    }
}
