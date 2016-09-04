<?php

namespace AppBundle\Controller\RHumano;

use AppBundle\Entity\Foto;
use AppBundle\Entity\Trabajador;
use AppBundle\Form\Type\FotoType;
use AppBundle\Form\Type\TrabajadorType;
use AppBundle\Form\Type\TrabajadorBajaType;
use AppBundle\Form\Type\SimpleFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/rh")
 */
class PlantillaController extends Controller
{
    /**
     * @Route("/plantilla-laboral", name="_rh_plantilla")
     */
    public function indexAction()
    {
        $trabajadores = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresActivos();
        $formInfo = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_rh_plantilla_trab_info')));
        $formEdit = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_rh_plantilla_trab_edit')));
        $formBaja = $this->createForm(TrabajadorBajaType::class, null, array(
            'action' => $this->generateUrl('_rh_plantilla_trab_baja')
        ));
        return $this->render('recursos_humano/trabajadores.html.twig',
            array(
                'menu' => 'rh',
                'submenu' => 'rh_plantilla',
                'formInfo' => $formInfo->createView(),
                'formEdit' => $formEdit->createView(),
                'formBaja' => $formBaja->createView(),
                'trabajadores' => $trabajadores,

            ));
    }

    /**
     * @Route("/plantilla-laboral/trabajador/info", name="_rh_plantilla_trab_info")
     */
    public function infoTrabAction(Request $request)
    {
        $id = $request->request->get('simple_form')['id'];
        if (!$id) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        $trabajador = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($id);
        if (!$trabajador) {
            $this->addFlash('error', 'El trabajador del cual ha solicitado información no existe.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        $utils = $this->get('app.utils');
        $utils->saveLog($this->getUser(), 'Consultar información del trabajador: [' . $trabajador->getNombreCompleto().']');
        return $this->render('recursos_humano/trabajador_info.html.twig',
            array(
                'menu' => 'rh',
                'submenu' => 'rh_plantilla',
                'trabajador' => $trabajador
            ));
    }

    /**
     * @Route("/plantilla-laboral/trabajador/nuevo", name="_rh_plantilla_trab_add")
     */
    public function addTrabAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Trabajador();
        $form = $this->createForm(TrabajadorType::class, $entity);
        $integraciones = $this->getDoctrine()->getRepository('AppBundle:Integracion')->findAll();
        $integSelected = '';
        if ($request->get('integraciones')) {
            $integSelected = $request->get('integraciones');
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $isExist = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->findOneBy(['carnet' => $entity->getCarnet()]);
            if ($isExist) {
                $this->addFlash('warning', 'El carnet de identidad del trabajador ya existe. Revíselo!');
            } else {
                $arrInteg = preg_split('/,/', $integSelected);
                foreach ($arrInteg as $item) {
                    $integ = $this->getDoctrine()->getRepository('AppBundle:Integracion')->find($item);
                    if ($integ) {
                        $entity->addIntegracion($integ);
                    }
                }
                $em->persist($entity);
                $em->flush();
                $utils = $this->get('app.utils');
                $utils->saveLog($this->getUser(), 'Agregar trabajador: [' . $entity->getNombreCompleto().']');
                $this->addFlash('success', 'Trabajador agregado con éxito.');
                $request = new Request();
                $request->request->add(['id' => $entity->getId()]);
                return $this->fotoTrabAction($request);
            }
        }
        return $this->render('recursos_humano/trabajador_new.html.twig',
            array(
                'menu' => 'rh',
                'submenu' => 'rh_plantilla',
                'form' => $form->createView(),
                'integraciones' => $integraciones,
                'integSelected' => $integSelected
            ));
    }

    /**
     * @Route("/plantilla-laboral/trabajador/edit", name="_rh_plantilla_trab_edit")
     */
    public function editTrabAction(Request $request)
    {
        $id = $request->request->get('simple_form')['id'];
        if (!$id) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        $trabajador = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($id);
        $integraciones = $this->getDoctrine()->getRepository('AppBundle:Integracion')->findAll();
        if (!$trabajador) {
            $this->addFlash('error', 'El trabajador que intentó editar no existe.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        $form = $this->createForm(TrabajadorType::class, $trabajador, array('action' => $this->generateUrl('_rh_plantilla_trab_edit_info')));
        return $this->render('recursos_humano/trabajador_edit.html.twig',
            array(
                'menu' => 'rh',
                'submenu' => 'rh_plantilla',
                'entity' => $trabajador,
                'form' => $form->createView(),
                'integraciones' => $integraciones
            ));
    }

    /**
     * @Route("/plantilla-laboral/trabajador/edit/info", name="_rh_plantilla_trab_edit_info")
     */
    public function editInfoTrabAction(Request $request)
    {
        $id = $request->request->get('_id');
        if (!$id) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        $request->request->add(['simple_form' => ['id' => $id]]);
        $entity = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($id);
        if (!$entity) {
            $this->addFlash('error', 'El trabajador que intentó editar no existe.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        $oldTrab = $entity->__toString();
        $form = $this->createForm(TrabajadorType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utils = $this->get('app.utils');
            $newInteg = preg_split('/,/', $request->get('integraciones'));
            $oldInteg = [];
            foreach ($entity->getIntegraciones() as $item) {
                array_push($oldInteg, $item->getId());
            }
            $addInteg = array_diff($newInteg, $oldInteg);
            $delInteg = array_diff($oldInteg, $newInteg);
            if ($entity->__toString() == $oldTrab && $addInteg == $delInteg) {
                $utils->saveLog($this->getUser(), 'Editar trabajador: [' . $oldTrab . '], no ha sido modificado.');
                $this->addFlash('info', 'La información del trabajador no ha sido modificada.');
                return $this->redirectToRoute('_rh_plantilla');
            }
            foreach ($delInteg as $item) {
                $integ = $this->getDoctrine()->getRepository('AppBundle:Integracion')->find($item);
                $entity->removeIntegracion($integ);
            }
            foreach ($addInteg as $item) {
                $integ = $this->getDoctrine()->getRepository('AppBundle:Integracion')->find($item);
                if ($integ) {
                    $entity->addIntegracion($integ);
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $utils->saveLog($this->getUser(), 'Editar trabajador: De: [' . $oldTrab . '] A: [' . $entity->__toString().']');
            $this->addFlash('success', 'Información del trabajador actualizada con éxito.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_rh_plantilla');

    }

    /**
     * @Route("/plantilla-laboral/trabajador/subir-foto", name="_rh_plantilla_trab_photo")
     */
    public function fotoTrabAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$id) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        $trabajador = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($id);
        if (!$trabajador) {
            $this->addFlash('error', 'El trabajador que intentó agregarle una foto no existe.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        $foto = new Foto();
        if ($trabajador->getFoto()) {
            $foto = $trabajador->getFoto();
        }
        $form = $this->createForm(FotoType::class, $foto, array('action' => $this->generateUrl('_rh_plantilla_trab_photo')));
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$trabajador->getFoto()) {
                $em->persist($foto);
                $trabajador->setFoto($foto);
            }
            $em->flush();
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Subir foto del trabajador: [Operación completada con éxito.]');
            $this->addFlash('success', 'Foto del trabajador actualizada con éxito.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        return $this->render('recursos_humano/trabajador_photo.html.twig',
            array(
                'menu' => 'rh',
                'submenu' => 'rh_plantilla',
                'entity' => $trabajador,
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/plantilla-laboral/trabajador/baja", name="_rh_plantilla_trab_baja")
     */
    public function bajaTrabAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TrabajadorBajaType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $trabajador = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($data['id']);
            if (!$trabajador) {
                $this->addFlash('error', 'El trabajador que ha intentado eliminar no existe.');
                return $this->redirectToRoute('_rh_plantilla');
            }
            if ($trabajador->getUsuario()) {
                $usuario = $this->getDoctrine()->getRepository('AppBundle:Usuario')->find($trabajador->getUsuario()->getId());
                $trabajador->getUsuario()->setTrabajador(null);
                $trabajador->setUsuario(null);
//                $usuario->setTrabajador(null);
                if ($usuario) {
                    $em->remove($usuario);
                }
            }
            $trabajador
                ->setIsBaja(true)
                ->setFechaBaja(new \DateTime('now'))
                ->setMotivoBaja($data['motivoBaja']);
            $em->flush();
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Dar baja a trabajador: [' . $trabajador->getNombreCompleto().']');
            $this->addFlash('success', 'Se ha dado baja al trabajador con éxito.');
            return $this->redirectToRoute('_rh_plantilla');
        }
        $this->addFlash('error', 'El envío del formulario no fue válido.');
        return $this->redirectToRoute('_rh_plantilla');
    }
}
