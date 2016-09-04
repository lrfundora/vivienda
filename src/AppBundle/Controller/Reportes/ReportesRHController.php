<?php

namespace AppBundle\Controller\Reportes;

use AppBundle\Form\Type\SimpleFormType;
use AppBundle\Form\Type\ReporteRecursosHumanoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/reportes")
 */
class ReportesRHController extends Controller
{
    /**
     * @Route("/recursos-humanos", name="_reportes_rh")
     */
    public function indexAction(Request $request)
    {
        $results = '';
        $cargos = $this->getDoctrine()->getRepository('AppBundle:Cargo')->findAll();
        $categorias = $this->getDoctrine()->getRepository('AppBundle:Categoria')->findAll();
        $contratos = $this->getDoctrine()->getRepository('AppBundle:Contrato')->findAll();
        $entidades = $this->getDoctrine()->getRepository('AppBundle:Entidad')->findBy(['externa' => false]);
        $escolaridad = $this->getDoctrine()->getRepository('AppBundle:Escolaridad')->findAll();
        $escalas = $this->getDoctrine()->getRepository('AppBundle:EscalaSalarial')->findAll();
        $integraciones = $this->getDoctrine()->getRepository('AppBundle:Integracion')->findAll();

        $form = $this->createForm(ReporteRecursosHumanoType::class);
        $formInfo = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_rh_plantilla_trab_info')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $results = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->DynamicReport($data['columna'], $data['coincidencia'], $data['criterio'], $data['hombre'], $data['mujer'], $data['baja']);
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
            if ($data['criterio'] == '') {
                $criterio = 'vacío';
            } else {
                if ($data['columna'] == 'integracion') {
                    $criterio = '{' . $data['criterio'] . '}';
                } else {
                    $criterio = $data['criterio'];
                }
            }
            if ($data['hombre'] && !$data['mujer']) {
                $sexo = 'Hombre';
            } else if (!$data['hombre'] && $data['mujer']) {
                $sexo = 'Mujer';
            } else {
                $sexo = 'Ambos';
            }
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Crear reporte en RECURSOS HUMANOS: [Variables de búsqueda: {' . $data['columna'] . ', ' . $coincide . ', ' . $criterio . ', Sexo: '.$sexo.', ' . $baja . '}, Resultados: {' . sizeof($results) . '}]');
        }

        return $this->render('reportes/reportes_rh.html.twig',
            array(
                'menu' => 'reportes',
                'submenu' => 'reportes_rh',
                'form' => $form->createView(),
                'formInfo' => $formInfo->createView(),
                'results' => $results,
                'cargos' => $cargos,
                'categorias' => $categorias,
                'contratos' => $contratos,
                'entidades' => $entidades,
                'escolaridad' => $escolaridad,
                'escalas' => $escalas,
                'integraciones' => $integraciones
            ));
    }
}
