<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_inicio")
     */
    public function indexAction()
    {
        //Usuarios
        $estadoUsuario = $this->getDoctrine()->getRepository('AppBundle:Usuario')->reporteXEstadoUsuario();

        //Clientes
        $clientes = $this->getDoctrine()->getRepository('AppBundle:Cliente')->reporteXClienteOperaciones();

        //Area legal
        $tramiteTermino = $this->getDoctrine()->getRepository('AppBundle:Tramite')->reporteXTramiteTermino();
        $tramiteIniMes = $this->getDoctrine()->getRepository('AppBundle:Tramite')->reporteXTramiteIniMes();
        $tramiteVenMes = $this->getDoctrine()->getRepository('AppBundle:Tramite')->reporteXTramiteVenMes();
        $tramiteEndMes = $this->getDoctrine()->getRepository('AppBundle:Tramite')->reporteXTramiteEndMes();
        $tramitesMeses = [[0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0]];
        foreach ($tramiteIniMes as $item) {
            if ($item[0] && date_format($item[0]->getFechaEntrada(), 'Y') == date_format(new \DateTime('now'), 'Y')) {
                $tramitesMeses[(date_format($item[0]->getFechaEntrada(), 'm') * 1) - 1][0] = $tramitesMeses[(date_format($item[0]->getFechaEntrada(), 'm') * 1) - 1][0] + $item[1];
            }
        }
        foreach ($tramiteVenMes as $item) {
            if ($item[0] && date_format($item[0]->getFechaVencimiento(), 'Y') == date_format(new \DateTime('now'), 'Y')) {
                $tramitesMeses[(date_format($item[0]->getFechaVencimiento(), 'm') * 1) - 1][1] = $tramitesMeses[(date_format($item[0]->getFechaVencimiento(), 'm') * 1) - 1][1] + $item[1];
            }
        }
        foreach ($tramiteEndMes as $item) {
            if ($item[0] && date_format($item[0]->getFechaVencimiento(), 'Y') == date_format(new \DateTime('now'), 'Y')) {
                $tramitesMeses[(date_format($item[0]->getFechaVencimiento(), 'm') * 1) - 1][2] = $tramitesMeses[(date_format($item[0]->getFechaVencimiento(), 'm') * 1) - 1][2] + $item[1];
            }
        }

        //Control de fondo
        $cf = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->reporteXArrendamientos();
        $all = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->reporteXPlanAnual();
        $cfPlan = 0;
        $cfRec = 0;
        $cfRMeses = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($all as $item) {
            $tmp = number_format(($item->getValor() / 180), '2', '.', ',');
            $cfPlan += $tmp * 12;
            foreach ($item->getPagos() as $pago) {
                if ($pago->getPagado()) {
                    $cfRec += $tmp;
                    $cfRMeses[$pago->getMes()-1] += $tmp;
                }
            }
        }


        //Recursos Humanos
        $rh = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->reporteXGenero();
        $rhb = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->reporteXGeneroBaja();
        $ta = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->reporteXTrabajador();
        $tb = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->reporteXTrabajadorBaja();
        $rhab = [[0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0], [0, 0]];
        foreach ($ta as $item) {
            if ($item[0] && date_format($item[0]->getFechaAlta(), 'Y') == date_format(new \DateTime('now'), 'Y')) {
                $rhab[(date_format($item[0]->getFechaAlta(), 'm') * 1) - 1][0] = $rhab[(date_format($item[0]->getFechaAlta(), 'm') * 1) - 1][0] + $item[1];
            }
        }
        foreach ($tb as $item) {
            if ($item[0] && date_format($item[0]->getFechaBaja(), 'Y') == date_format(new \DateTime('now'), 'Y')) {
                $rhab[(date_format($item[0]->getFechaBaja(), 'm') * 1) - 1][1] = $rhab[(date_format($item[0]->getFechaBaja(), 'm') * 1) - 1][1] + $item[1];
            }
        }
        return $this->render('default/index.html.twig',
            array(
                'menu' => 'inicio',
                'submenu' => '',
                'estadoUsuario' => $estadoUsuario,
                'clientes' => $clientes,
                'tramiteTermino' => $tramiteTermino,
                'tramiteMeses' => $tramitesMeses,
                'cf' => $cf,
                'cfPlan' => $cfPlan,
                'cfRec' => $cfRec,
                'cfRMeses' => $cfRMeses,
                'rh' => $rh,
                'rhb' => $rhb,
                'rhab' => $rhab
            ));
    }

    /**
     * @Route("/redirect", name="_redirect")
     */
    public function redirectAction()
    {
        $utils = $this->get('app.utils');
        $utils->saveLog($this->getUser(), 'Iniciar sesión: [Operación exitosa]');

        $authChecker = $this->get('security.authorization_checker');
        if ($authChecker->isGranted('ROLE_RECEPCION')) {
            return $this->redirectToRoute('_recepcion_tramite');
        } else {
            return $this->redirectToRoute('_inicio');
        }
    }
}
