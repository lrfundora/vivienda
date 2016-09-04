<?php

namespace AppBundle\Controller\CtrlFondo;

use AppBundle\Entity\Arrendamiento;
use AppBundle\Form\Type\ArrendamientoPagoType;
use AppBundle\Form\Type\SimpleFormType;
use AppBundle\Entity\Pago;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/control-de-fondo/arrendamientos")
 */
class ControlFondoPagoController extends Controller
{
    /**
     * @Route("/pagos", name="_cf_pagos")
     */
    public function arrendatariosAction()
    {
        $arrendatarios = $this->getDoctrine()->getRepository('AppBundle:Cliente')->ControlFondoClientes();
        $arrendamientos = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->findAll();

        foreach ($arrendamientos as $item) {
            $nuevosPagos = $this->getDoctrine()->getRepository('AppBundle:Pago')->findNeedNewPay($item->getId());
            if (!$nuevosPagos) {
                $this->setPagos($item);
            }
        }

        $formPago = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_cf_pagos_pagar')]);
        $formInfo = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_cf_pagos_info')]);
        return $this->render('control_fondo/arrendatarios.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_pago',
                'arrendatarios' => $arrendatarios,
                'formInfo' => $formInfo->createView(),
                'formPago' => $formPago->createView()
            ));
    }

    /**
     * @Route("/pagos/info", name="_cf_pagos_info")
     */
    public function infoAction(Request $request)
    {
        $id = $request->request->get('simple_form')['id'];
        if (!$id) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_cf_pagos');
        }
        $arrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->find($id);
        return $this->render('control_fondo/arrendatario_info.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_pago',
                'arrendamiento' => $arrendamiento
            ));
    }

    /**
     * @Route("/pagos/pagar", name="_cf_pagos_pagar")
     */
    public function pagoAction(Request $request)
    {
        $id = $request->request->get('simple_form')['id'];
        if (!$id) {
            $id = $request->request->get('id');
            if (!$id) {
                $this->addFlash('error', 'Datos no válidos.');
                return $this->redirectToRoute('_cf_pagos');
            }
        }
        $arrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->find($id);
        if (!$arrendamiento) {
            $this->addFlash('error', 'El arrendamiento no existe.');
            return $this->redirectToRoute('_cf_pagos');
        }

        $entity = new Arrendamiento();
        foreach ($arrendamiento->getPagos() as $pago) {
            if (!$pago->getPagado()) {
                $entity->addPago($pago);
            }
        }
        $form = $this->createForm(ArrendamientoPagoType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $pagados = '';
            foreach ($entity->getPagos() as $pago) {
                $arrendamiento->addPago($pago);
                if ($pago->getPagado()) {
                    if ($pagados == '') {
                        $pagados = $meses[$pago->getMes()];
                    } else {
                        $pagados .= ', ' . $meses[$pago->getMes()];
                    }

                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Efectuar pago de arrendamiento: Número de registro [' . $arrendamiento->getNumeroRegistro() . '], Meses pagados [' . $pagados . ']');
            $this->addFlash('success', 'Pago de impuesto con éxito.');
            return $this->redirectToRoute('_cf_pagos');
        }

        return $this->render('control_fondo/arrendatario_pago.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_pago',
                'arrendamiento' => $arrendamiento,
                'entity' => $entity,
                'form' => $form->createView()
            ));
    }

    /**
     * @param Arrendamiento $arrendamiento
     * @param $year
     */
    private function setPagos(Arrendamiento $arrendamiento, $year = null)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$year) {
            $year = date_format(new \DateTime('now'), 'Y');
        }
        for ($i = 1; $i < 13; $i++) {
            $tmp = new Pago();
            $tmp
                ->setAno($year)
                ->setMes($i);
            $em->persist($tmp);
            $arrendamiento->addPago($tmp);
        }
        $em->flush();
    }
}
