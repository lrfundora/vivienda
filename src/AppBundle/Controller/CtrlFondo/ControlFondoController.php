<?php

namespace AppBundle\Controller\CtrlFondo;

use AppBundle\Entity\Arrendamiento;
use AppBundle\Entity\Cliente;
use AppBundle\Entity\Pago;
use AppBundle\Form\Type\SimpleFormType;
use AppBundle\Form\Type\ClienteType;
use AppBundle\Form\Type\ArrendamientoType;
use AppBundle\Form\Type\ArrendamientoBajaType;
use AppBundle\Form\Type\ClienteArrendamientoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/control-de-fondo")
 */
class ControlFondoController extends Controller
{
    /**
     * @Route("/arrendamientos", name="_cf_arrendamiento")
     */
    public function arrenadamientoAction()
    {
        $arrendamientos = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->findAllActive();
        $formArr = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_cf_arrendamiento_cliente-search')]);
        $formInfo = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_cf_arrendamiento_info')]);
        $formEdit = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_cf_arrendamiento_edit')]);
        $formBaja = $this->createForm(ArrendamientoBajaType::class, null, ['action' => $this->generateUrl('_cf_arrendamiento_baja')]);

        return $this->render('control_fondo/arrendamientos.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_arrendamiento',
                'arrendamientos' => $arrendamientos,
                'formArr' => $formArr->createView(),
                'formInfo' => $formInfo->createView(),
                'formEdit' => $formEdit->createView(),
                'formBaja' => $formBaja->createView()
            ));
    }

    /**
     * @Route("/arrendamiento/info", name="_cf_arrendamiento_info")
     */
    public function infoArrenadamientoAction(Request $request)
    {
        $id = $request->request->get('simple_form')['id'];
        if (!$id) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }
        $arrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->find($id);
        if (!$arrendamiento) {
            $this->addFlash('info', 'El arrendamiento del cual solicitó información no existe.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }

        $utils = $this->get('app.utils');
        $utils->saveLog($this->getUser(), 'Consultar información de arrendamiento: [' . $arrendamiento->__toString() . ']');

        return $this->render('control_fondo/arrendamiento_info.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_arrendamiento',
                'arrendamiento' => $arrendamiento
            ));
    }

    /**
     * @Route("/arrendamiento/nuevo", name="_cf_arrendamiento_new")
     */
    public function nuevoArrenadamientoBuscarClienteAction(Request $request)
    {
        $form = $this->createForm(SimpleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $carnet = $form->getData()['id'];
            $cliente = $this->getDoctrine()->getRepository('AppBundle:Cliente')->findOneBy(['carnet' => $carnet]);
            $request = new Request();
            if ($cliente) {
                $request->request->add(['id' => $cliente->getId()]);
                return $this->nuevoArrenadamientoAction($request);
            } else {
                $request->request->add(['carnet' => $carnet]);
                return $this->nuevoClienteArrenadamientoAction($request);
            }
        }

        return $this->render('cliente/buscar_cliente_arrendamiento.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_arrendamiento',
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/arrendamiento/nuevo-arrendamiento", name="_cf_arrendamiento_new1")
     */
    public function nuevoArrenadamientoAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$id) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }
        $cliente = $this->getDoctrine()->getRepository('AppBundle:Cliente')->find($id);
        $arrendamiento = new Arrendamiento();
        $form = $this->createForm(ArrendamientoType::class, $arrendamiento, array('action' => $this->generateUrl('_cf_arrendamiento_new1')));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cliente = $this->getDoctrine()->getRepository('AppBundle:Cliente')->find($id);
            if (!$cliente) {
                $this->addFlash('error', 'El cliente ya no éxito.');
                return $this->redirectToRoute('_cf_arrendamiento');
            }
            $isArrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->findBy(['numeroRegistro' => $arrendamiento->getNumeroRegistro()]);
            if ($isArrendamiento) {
                $this->addFlash('info', 'El arrendamiento con número de registro ' . $arrendamiento->getNumeroRegistro() . ' ya existe.');
                return $this->redirectToRoute('_cf_arrendamiento');
            }
            $cliente->addArrendamiento($arrendamiento);
            $em = $this->getDoctrine()->getManager();
            $em->persist($arrendamiento);
            $em->flush();
            $this->setPagos($arrendamiento);
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Agregar arrendamiento: [' . $cliente->__toString() . '] A: [' . $arrendamiento->__toString() . ']');
            $this->addFlash('success', 'Nuevo arrendamiento agregado con éxito.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }
        return $this->render('control_fondo/arrendamiento_new.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_arrendamiento',
                'cliente' => $cliente,
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/arrendamiento/nuevo-cliente-arrendamiento", name="_cf_arrendamiento_new2")
     */
    public function nuevoClienteArrenadamientoAction(Request $request)
    {
        $cliente = new Cliente();
        $arrendamiento = new Arrendamiento();
        $cliente->setCarnet($request->request->get('carnet'));
        $cliente->addArrendamiento($arrendamiento);
        $form = $this->createForm(ClienteArrendamientoType::class, $cliente, array('action' => $this->generateUrl('_cf_arrendamiento_new2')));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $isArrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->findBy(['numeroRegistro' => $arrendamiento->getNumeroRegistro()]);
            if ($isArrendamiento) {
                $this->addFlash('info', 'El arrendamiento con número de registro ' . $arrendamiento->getNumeroRegistro() . ' ya existe.');
                return $this->redirectToRoute('_cf_arrendamiento');
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($arrendamiento);
            $em->persist($cliente);
            $em->flush();
            $this->setPagos($arrendamiento);
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Agregar cliente y arrendamiento: [' . $cliente->__toString() . '] A: [' . $arrendamiento->__toString() . ']');
            $this->addFlash('success', 'Nuevo cliente y arrendamiento agregado con éxito.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }
        return $this->render('control_fondo/arrendamiento_cliente_new.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_arrendamiento',
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/arrendamiento/edit", name="_cf_arrendamiento_edit")
     */
    public function editArrenadamientoAction(Request $request)
    {
        $id = $request->request->get('simple_form')['id'];
        if (!$id) {
            $id = $request->request->get('id');
            if (!$id) {
                $this->addFlash('error', 'Datos no válidos.');
                return $this->redirectToRoute('_cf_arrendamiento');
            }
        }
        $arrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->find($id);
        if (!$arrendamiento) {
            $this->addFlash('error', 'El arrendamiento no existe.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }
        $form = $this->createForm(ArrendamientoType::class, $arrendamiento);
        $formDelete = $this->createForm(SimpleFormType::class, null, ['action' => $this->generateUrl('_cf_arrendamiento_edit_del')]);
        $oldArr = $arrendamiento->__toString();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utils = $this->get('app.utils');
            if ($oldArr == $arrendamiento->__toString()) {
                $utils->saveLog($this->getUser(), 'Editar arrendamiento: [' . $oldArr . '], no fue modificado.');
                $this->addFlash('info', 'El arrendamiento actualizado no ha sido modificado.');
                return $this->redirectToRoute('_cf_arrendamiento');
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $utils->saveLog($this->getUser(), 'Editar arrendamiento: De: [' . $oldArr . '] A: [' . $arrendamiento->__toString() . ']');
            $this->addFlash('success', 'Arrendamiento actualizado con éxito.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }
        return $this->render('control_fondo/arrendamiento_edit.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_arrendamiento',
                'arrendamiento' => $arrendamiento,
                'form' => $form->createView(),
                'formDelete' => $formDelete->createView()
            ));
    }

    /**
     * @Route("/arrendamiento/edit/delete-cliente", name="_cf_arrendamiento_edit_del")
     */
    public function editArrenadamientoDeleteClienteAction(Request $request)
    {
        $idCliente = $request->request->get('id');
        $idArr = $request->request->get('simple_form')['id'];
        if (!$idArr && !$idCliente) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }
        $cliente = $this->getDoctrine()->getRepository('AppBundle:Cliente')->find($idCliente);
        $arrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->find($idArr);

        $utils = $this->get('app.utils');
        if ($cliente && $arrendamiento) {
            $arrendamiento->removeCliente($cliente);
            $utils->saveLog($this->getUser(), 'Quitar arrendatario de arrendamiento: [' . $cliente->__toString() . '] De: [' . $arrendamiento->__toString() . ']');
            $this->addFlash('success', 'El arrendatario ha sido quitado con éxito.');
        } else {
            $utils->saveLog($this->getUser(), 'Quitar arrendatario de arrendamiento: Operación no completada por inconsistencia de datos.');
            $this->addFlash('error', 'El arrendatario o el arrendamiento seleccionado no existe.');
        }
        return $this->redirectToRoute('_cf_arrendamiento');
    }

    /**
     * @Route("/arrendamiento/arrendatario/nuevo", name="_cf_arrendamiento_cliente-search")
     */
    public function buscarClienteAction(Request $request)
    {
        $idArrendamiento = $request->request->get('idArrendamiento');
        if (!$idArrendamiento) {
            $idArrendamiento = $request->request->get('simple_form')['id'];
            if (!$idArrendamiento) {
                $this->addFlash('danger', 'Datos no válidos.');
                return $this->redirectToRoute('_cf_arrendamiento');
            } else {
                $request = new Request();
            }
        }
        $form = $this->createForm(SimpleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $carnet = $form->getData()['id'];
            $cliente = $this->getDoctrine()->getRepository('AppBundle:Cliente')->findOneBy(['carnet' => $carnet]);
            $request = new Request();
            $request->request->add(['idArrendamiento' => $idArrendamiento]);
            if ($cliente) {
                foreach($cliente->getArrendamientos() as $item){
                    if($idArrendamiento==$item->getId()){
                        $utils = $this->get('app.utils');
                        $utils->saveLog($this->getUser(), 'Agregar nuevo arrendatario: [' . $cliente->__toString() . '], YA EXISTE EN: [' . $item->__toString() . ']');
                        $this->addFlash('warning', 'El arrendamiento ya contiene al arrendatario que intento agregar.');
                       return $this->redirectToRoute('_cf_arrendamiento');
                    }
                }
                $request->request->add(['id' => $cliente->getId()]);
                return $this->agregarClienteAction($request);
            } else {
                $request->request->add(['carnet' => $carnet]);
                return $this->nuevoClienteAction($request);
            }
        }

        return $this->render('cliente/buscar_cliente.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_arrendamiento',
                'idArrendamiento' => $idArrendamiento,
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/arrendamiento/arrendatario/nuevo-cliente", name="_cf_arrendamiento_cliente-new")
     */
    public function nuevoClienteAction(Request $request)
    {

        $idArrendamiento = $request->request->get('idArrendamiento');
        if (!$idArrendamiento) {
            $this->addFlash('danger', 'Datos no válidos.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }
        $entity = new Cliente();
        $entity->setCarnet($request->request->get('carnet'));
        $form = $this->createForm(ClienteType::class, $entity, ['action' => $this->generateUrl('_cf_arrendamiento_cliente-new')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->find($idArrendamiento);
            if (!$arrendamiento) {
                $this->addFlash('warning', 'El arrenadmiento que ha seleccionado ya no existe.');
                return $this->redirectToRoute('_cf_arrendamiento');
            }
            $entity->addArrendamiento($arrendamiento);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Agregar nuevo arrendatario: [' . $entity->__toString() . '] A: [' . $arrendamiento->__toString() . ']');
            $this->addFlash('success', 'Arrendatario agregado con éxito.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }

        return $this->render('control_fondo/arrendatario_new.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_arrendamiento',
                'form' => $form->createView(),
                'idArrendamiento' => $idArrendamiento
            ));
    }

    /**
     * @Route("/arrendamiento/arrendatario/agregar-cliente", name="_cf_arrendamiento_cliente-add")
     */
    public function agregarClienteAction(Request $request)
    {
        $idArrendamiento = $request->request->get('idArrendamiento');
        if (!$idArrendamiento) {
            $this->addFlash('danger', 'Datos no válidos.');
            $this->redirectToRoute('_cf_arrendamiento');
        }
        $entity = $this->getDoctrine()->getRepository('AppBundle:Cliente')->find($request->request->get('id'));
        $form = $this->createForm(ClienteType::class, $entity, ['action' => $this->generateUrl('_cf_arrendamiento_cliente-add')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->find($idArrendamiento);
            if (!$arrendamiento) {
                $this->addFlash('warning', 'El arrenadmiento que ha seleccionado ya no existe.');
                return $this->redirectToRoute('_cf_arrendamiento');
            }
            $existCliente = false;
            foreach ($arrendamiento->getClientes() as $cliente) {
                if ($entity->__toString() == $cliente->__toString()) {
                    $existCliente = true;
                    break;
                }
            }
            if (!$existCliente) {
                $entity->addArrendamiento($arrendamiento);
            } else {
                $utils = $this->get('app.utils');
                $utils->saveLog($this->getUser(), 'Agregar arrendatario: [' . $entity->__toString() . '], ya está agregado.');
                $this->addFlash('info', 'El arrendatario que intento agregar ya existe en el arrendamiento.');
                return $this->redirectToRoute('_cf_arrendamiento');
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Agregar nuevo arrendatario: [' . $entity->__toString() . '] A: [' . $arrendamiento->__toString() . ']');
            $this->addFlash('success', 'Arrendatario agregado con éxito.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }
        return $this->render('control_fondo/arrendatario_add.html.twig',
            array(
                'menu' => 'cf',
                'submenu' => 'cf_arrendamiento',
                'form' => $form->createView(),
                'idArrendamiento' => $idArrendamiento,
                'entity' => $entity

            ));
    }

    /**
     * @Route("/arrendamiento/baja", name="_cf_arrendamiento_baja")
     */
    public function bajaArrendamientoAction(Request $request)
    {
        $form = $this->createForm(ArrendamientoBajaType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $arrendamiento = $this->getDoctrine()->getRepository('AppBundle:Arrendamiento')->find($data['id']);
            if (!$arrendamiento) {
                $this->addFlash('warning', 'El arrendamiento solicitado no existe.');
                return $this->redirectToRoute('_cf_arrendamiento');
            }
            $arrendamiento
                ->setNumeroRegistro(0)
                ->setIsBaja(true)
                ->setFechaBaja(new \DateTime('now'))
                ->setComentario($data['comentario']);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Dar baja a arrendamiento: [' . $arrendamiento->__toString() . ']');
            $this->addFlash('success', 'Se ha dado baja al arrendamiento con éxito.');
            return $this->redirectToRoute('_cf_arrendamiento');
        }
        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_cf_arrendamiento');
    }

    /**
     * @param Arrendamiento $arrendamiento
     * @param $year
     */
    private function setPagos(Arrendamiento $arrendamiento, $year = null)
    {
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');
        $month = date_format($date, 'm');
        if (!$year) {
            $year = date_format($date, 'Y');
        }
        for ($i = $month; $i < 13; $i++) {
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
