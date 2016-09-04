<?php

namespace AppBundle\Controller\CtrlFondo;


use AppBundle\Form\Type\SimpleFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/control-de-fondo")
 */
class ControlFondoBajasController extends Controller
{
    /**
     * @Route("/arrendamientos/bajas", name="_cf_bajas")
     */
    public function bajasAction()
    {
        $arrendamientos = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->findAllInactive();

        $formInfo = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_cf_bajas_info')]);
        return $this->render('control_fondo/arrendamientos_bajas.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_bajas',
                'arrendamientos' => $arrendamientos,
                'formInfo' => $formInfo->createView()
            ));
    }

    /**
     * @Route("/arrendamientos/bajas/info", name="_cf_bajas_info")
     */
    public function infoBajasAction(Request $request)
    {
        $id = $request->request->get('simple_form')['id'];
        if (!$id) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_cf_bajas');
        }
        $arrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->find($id);
        if (!$arrendamiento) {
            $this->addFlash('info', 'El arrendamiento del cual solicitó información no existe.');
            return $this->redirectToRoute('_cf_bajas');
        }

        $utils = $this->get('app.utils');
        $utils->saveLog($this->getUser(), 'Consultar información de arrendamiento baja: [' . $arrendamiento->__toString().']');

        return $this->render('control_fondo/arrendamientos_bajas_info.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_bajas',
                'arrendamiento' => $arrendamiento
            ));
    }
}
