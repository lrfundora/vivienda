<?php

namespace AppBundle\Controller\AreaLegal;

use AppBundle\Entity\Cliente;
use AppBundle\Entity\Tramite;
use AppBundle\Form\Type\ClienteType;
use AppBundle\Form\Type\ClienteTramiteType;
use AppBundle\Form\Type\SimpleFormType;
use AppBundle\Form\Type\TramiteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/area-legal")
 */
class AreaLegalController extends Controller
{
    /**
     * @Route("/clientes", name="_arealegal_cliente")
     */
    public function clientesAction()
    {

        $authChecker = $this->get('security.authorization_checker');
        if ($authChecker->isGranted('ROLE_ABOGADO')) {
            $clientes = $this->getDoctrine()->getRepository('AppBundle:Cliente')->AreaLegalClientes($this->getUser()->getTrabajador()->getId());
        } else {
            $clientes = $this->getDoctrine()->getRepository('AppBundle:Cliente')->AreaLegalClientes();
        }

        $formInfo = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_arealegal_cliente_info')]);
        $formNewTramite = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_arealegal_tramite_new2')]);

        return $this->render('cliente/clientes.html.twig',
            array(
                'menu' => 'area',
                'submenu' => 'area_cliente',
                'clientes' => $clientes,
                'formInfo' => $formInfo->createView(),
                'formNewTramite' => $formNewTramite->createView()
            ));
    }

    /**
     * @Route("/cliente/info-tramites", name="_arealegal_cliente_info")
     */
    public function infoClienteAction(Request $request)
    {
        $idCliente = $request->request->get('simple_form')['id'];
        if (!$idCliente) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_arealegal_cliente');
        }

        $cliente = $this->getDoctrine()->getRepository('AppBundle:Cliente')->find($idCliente);
        if (!$cliente) {
            $this->addFlash('warning', 'El cliente no existe.');
            return $this->redirectToRoute('_arealegal_cliente');
        }

        $authChecker = $this->get('security.authorization_checker');
        if ($authChecker->isGranted('ROLE_ABOGADO')) {
            $tramites = $this->getDoctrine()->getRepository('AppBundle:Tramite')->findClienteTramites($idCliente, $this->getUser()->getTrabajador()->getId());
        } else {
            $tramites = $this->getDoctrine()->getRepository('AppBundle:Tramite')->findClienteTramites($idCliente);
        }
        $utils = $this->get('app.utils');
        $utils->saveLog($this->getUser(), 'Consultar información de trámites del cliente: [' . $cliente->__toString().']');
        return $this->render(
            'area_legal/cliente_tramites_info.html.twig',
            array(
                'menu' => 'area',
                'submenu' => 'area_cliente',
                'cliente' => $cliente,
                'tramites' => $tramites
            )
        );
    }

    /**
     * @Route("/tramites", name="_arealegal_tramite")
     */
    public function tramitesAction()
    {
        $authChecker = $this->get('security.authorization_checker');
        if ($authChecker->isGranted('ROLE_ABOGADO')) {
            $tramites = $this->getDoctrine()->getRepository('AppBundle:Tramite')->findTramites($this->getUser()->getTrabajador()->getId());
        } else {
            $tramites = $this->getDoctrine()->getRepository('AppBundle:Tramite')->findTramites();
        }
        $formCom = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_arealegal_tramite_com')));
        $formEdit = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_arealegal_tramite_edit')));
        $formEnd = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_arealegal_tramite_end')));

        return $this->render('area_legal/tramites.html.twig',
            array(
                'menu' => 'area',
                'submenu' => 'area_tramite',
                'tramites' => $tramites,
                'formCom' => $formCom->createView(),
                'formEdit' => $formEdit->createView(),
                'formEnd' => $formEnd->createView()
            ));
    }

    /**
     * @Route("/tramites-finalizados", name="_arealegal_tramite_baja")
     */
    public function tramitesBajaAction()
    {
        $authChecker = $this->get('security.authorization_checker');
        if ($authChecker->isGranted('ROLE_ABOGADO')) {
            $tramites = $this->getDoctrine()->getRepository('AppBundle:Tramite')->findTramitesBaja($this->getUser()->getTrabajador()->getId());
        } else {
            $tramites = $this->getDoctrine()->getRepository('AppBundle:Tramite')->findTramitesBaja();
        }
        $formCom = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_arealegal_tramite_com')));
        $formEdit = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_arealegal_tramite_edit')));
        $formEnd = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_arealegal_tramite_end')));

        return $this->render('area_legal/tramites_baja.html.twig',
            array(
                'menu' => 'area',
                'submenu' => 'area_tramite_baja',
                'tramites' => $tramites,
                'formCom' => $formCom->createView(),
                'formEdit' => $formEdit->createView(),
                'formEnd' => $formEnd->createView()
            ));
    }

    /**
     * @Route("/tramite/nuevo", name="_arealegal_tramite_search")
     */
    public function nuevoTramiteBuscarClienteAction(Request $request)
    {
        $form = $this->createForm(SimpleFormType::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $carnet = $form->getData()['id'];
            $cliente = $this->getDoctrine()->getRepository('AppBundle:Cliente')->findOneBy(['carnet' => $carnet]);
            $request = new Request();
            if ($cliente) {
                $request->request->add(['id' => $cliente->getId()]);
                return $this->nuevoTramiteAction($request);
            } else {
                $request->request->add(['carnet' => $carnet]);
                return $this->nuevoClienteTramiteAction($request);
            }
        }

        return $this->render('cliente/buscar_cliente_tramite.html.twig',
            array(
                'menu' => 'area',
                'submenu' => 'area_tramite',
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/tramite/nuevo-cliente-tramite", name="_arealegal_tramite_new1")
     */
    public function nuevoClienteTramiteAction(Request $request)
    {
        $cliente = new Cliente();
        $cliente->setCarnet($request->request->get('carnet'));
        $tramite = new Tramite();
        $cliente->addTramite($tramite);

        $abogados = '';
        $authChecker = $this->get('security.authorization_checker');
        if (!$authChecker->isGranted('ROLE_ABOGADO')) {
            $abogados = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresUsuarioCargo('Especialista B en derechos sobre la vivienda');
        }

        $form = $this->createForm(ClienteTramiteType::class, $cliente, array('action' => $this->generateUrl('_arealegal_tramite_new1')));

        $form->handleRequest($request);
        if ($form->isValid()) {

            $idTrab = $request->request->get('idAbogado');
            if ($idTrab) {
                $trabajador = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($idTrab);
                if (!$trabajador) {
                    $this->addFlash('warning', 'El abogado que seleccionó no existe.');
                    return $this->nuevoTramiteAction($request);
                }
                $tramite->setTrabajador($trabajador);
            } else {
                $tramite->setTrabajador($this->getUser()->getTrabajador());
            }
            $tramite->setComentario('');
            $tramite->setCliente($cliente);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();

            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Agregar cliente y trámite: [' . $cliente->__toString() . '] A: [' . $tramite->__toString().']');
            $this->addFlash('success', 'Nuevo cliente con su trámite agregado con éxito.');
            return $this->redirectToRoute('_arealegal_tramite');
        }

        return $this->render('area_legal/tramite_cliente_new.html.twig',
            array(
                'menu' => 'area',
                'submenu' => 'area_tramite',
                'form' => $form->createView(),
                'abogados' => $abogados
            ));
    }

    /**
     * @Route("/tramite/nuevo-tramite", name="_arealegal_tramite_new2")
     */
    public function nuevoTramiteAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$id) {
            $id = $request->request->get('simple_form')['id'];
            if (!$id) {
                $this->addFlash('error', 'Datos no válidos.');
                return $this->redirectToRoute('_arealegal_tramite');
            }
        }

        $cliente = $this->getDoctrine()->getRepository('AppBundle:Cliente')->find($id);

        $abogados = '';
        $authChecker = $this->get('security.authorization_checker');
        if (!$authChecker->isGranted('ROLE_ABOGADO')) {
            $abogados = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresUsuarioCargo('Especialista B en derechos sobre la vivienda');
        }

        $tramite = new Tramite();
        $tramite->setCliente($cliente);
        $form = $this->createForm(TramiteType::class, $tramite, array('action' => $this->generateUrl('_arealegal_tramite_new2')));

        $form->handleRequest($request);
        if ($form->isValid()) {

            $idTrab = $request->request->get('idAbogado');
            if ($idTrab) {
                $trabajador = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($idTrab);
                if (!$trabajador) {
                    $this->addFlash('warning', 'El abogado que seleccionó no existe.');
                    return $this->nuevoTramiteAction($request);
                }
                $tramite->setTrabajador($trabajador);
            } else {
                $tramite->setTrabajador($this->getUser()->getTrabajador());
            }
            $tramite->setComentario('');
            $em = $this->getDoctrine()->getManager();
            $em->persist($tramite);
            $em->flush();
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Agregar trámite: [' . $cliente->__toString() . '] A: [' . $tramite->__toString().']');
            $this->addFlash('success', 'Nuevo trámite agregado con éxito.');
            return $this->redirectToRoute('_arealegal_tramite');
        }

        return $this->render('area_legal/tramite_new.html.twig',
            array(
                'menu' => 'area',
                'submenu' => 'area_tramite',
                'form' => $form->createView(),
                'cliente' => $cliente,
                'abogados' => $abogados
            ));
    }

    /**
     * @Route("/tramite/comentar", name="_arealegal_tramite_com")
     */
    public function comentarTramiteAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$id) {
            $id = $request->request->get('simple_form')['id'];
            if (!$id) {
                $this->addFlash('error', 'Datos no válidos.');
//                return $this->redirectToRoute('_arealegal_tramite');
            }
        }

        $tramite = $this->getDoctrine()->getRepository('AppBundle:Tramite')->find($id);

        $oldEntity = strtolower($tramite);

        $form = $this->createForm(TramiteType::class, $tramite);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $utils = $this->get('app.utils');
            if (strtolower($tramite) == $oldEntity) {
                $utils->saveLog($this->getUser(), 'Comentar trámite: [' . $tramite->__toString().'], no ha sido modificado.');
                $this->addFlash('info', 'El comentario del trámite no ha sido modificado.');
                return $this->redirectToRoute('_arealegal_tramite');
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $utils->saveLog($this->getUser(), 'Comentar trámite: [' . $tramite->__toString().']');
            $this->addFlash('success', 'Trámite comentado con éxito.');
            return $this->redirectToRoute('_arealegal_tramite');
        }

        return $this->render('area_legal/tramite_com.html.twig',
            array(
                'menu' => 'area',
                'submenu' => 'area_tramite',
                'form' => $form->createView(),
                'tramite' => $tramite
            ));
    }

    /**
     * @Route("/tramite/edit", name="_arealegal_tramite_edit")
     */
    public function editarTramiteAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$id) {
            $id = $request->request->get('simple_form')['id'];
            if (!$id) {
                $this->addFlash('error', 'Datos no válidos.');
                return $this->redirectToRoute('_arealegal_tramite');
            }
        }

        $abogados = '';
        $authChecker = $this->get('security.authorization_checker');
        if (!$authChecker->isGranted('ROLE_ABOGADO')) {
            $abogados = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresUsuarioCargo('Abogado');
        }

        $tramite = $this->getDoctrine()->getRepository('AppBundle:Tramite')->find($id);

        $oldAbogado = $tramite->getTrabajador()->getId();
        $oldEntity = $tramite->__toString();

        $form = $this->createForm(TramiteType::class, $tramite);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $utils = $this->get('app.utils');
            if (strtolower($tramite) == strtolower($oldEntity)) {
                $utils->saveLog($this->getUser(), 'Editar trámite: [' . $oldEntity . '], no ha sido modificado.');
                $this->addFlash('info', 'El trámite no ha sido modificado.');
                return $this->redirectToRoute('_arealegal_tramite');
            }

            $idTrab = $request->request->get('idAbogado');
            if ($idTrab && $idTrab != $oldAbogado) {
                $trabajador = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($idTrab);
                if (!$trabajador) {
                    $this->addFlash('warning', 'El abogado que seleccionó no existe, por tanto no se actualizó este dato.');
                } else {
                    $tramite->setTrabajador($trabajador);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $utils->saveLog($this->getUser(), 'Editar trámite: De: [' . $oldEntity . '] A: [' . $tramite . ']');
            $this->addFlash('success', 'Trámite actualizado con éxito.');
            return $this->redirectToRoute('_arealegal_tramite');
        }

        return $this->render('area_legal/tramite_edit.html.twig',
            array(
                'menu' => 'area',
                'submenu' => 'area_tramite',
                'form' => $form->createView(),
                'tramite' => $tramite,
                'abogados' => $abogados
            ));
    }

    /**
     * @Route("/tramite/completar", name="_arealegal_tramite_end")
     */
    public function completarTramiteAction(Request $request)
    {
        $form = $this->createForm(SimpleFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form->getData()['id'];
            $tramite = $this->getDoctrine()->getRepository('AppBundle:Tramite')->find($id);
            $tramite
                ->setFechaVencimiento(new \DateTime('now'))
                ->setIsCompletado(true);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Completar trámite: [' . $tramite->__toString().']');
            $this->addFlash('success', 'Trámite finalizado con éxito.');
            return $this->redirectToRoute('_arealegal_tramite');
        }

        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_arealegal_tramite');
    }
}
