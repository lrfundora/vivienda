<?php

namespace AppBundle\Controller\Security;

use AppBundle\Entity\Entidad;
use AppBundle\Form\Type\EntidadType;
use AppBundle\Form\Type\SimpleFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EntidadController extends Controller
{
    /**
     * @Route("/seguridad/entidades", name="_seguridad_entidad")
     */
    public function entidadesAction()
    {
        $entidades = $this->getDoctrine()->getRepository('AppBundle:Entidad')->findAll();
        $form = $this->createForm(EntidadType::class, new Entidad(), array('action' => $this->generateUrl('_seguridad_entidad_new')));
        $formEdit = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_seguridad_entidad_edit')));
        $formDelete = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_seguridad_entidad_delete')));

        return $this->render(
            'security/entidades.html.twig',
            array(
                'menu' => 'seguridad',
                'submenu' => 'seguridad_entidad',
                'entidades' => $entidades,
                'formEntidad' => $form->createView(),
                'formEdit' => $formEdit->createView(),
                'formDelete' => $formDelete->createView()
            )
        );
    }

    /**
     * @Route("/seguridad/entidad/nueva", name="_seguridad_entidad_new")
     */
    public function nuevaEntidadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Entidad();
        $form = $this->createForm(EntidadType::class, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $isExist = $em->getRepository('AppBundle:Entidad')->findOneBy(['nombre' => $entity->getNombre()]);
            if ($isExist) {
                $this->addFlash('warning', 'La entidad ya existe.');
                return $this->redirectToRoute('_seguridad_entidad');
            }

            $em->persist($entity);
            $em->flush();
            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Agregar entidad: [' . $entity->__toString() . ']');
            $this->addFlash('success', 'Nueva entidad agregada con éxito.');
            return $this->redirectToRoute('_seguridad_entidad');
        }

        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_seguridad_entidad');
    }

    /**
     * @Route("/seguridad/entidad/edit", name="_seguridad_entidad_edit")
     */
    public function editEntidadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        if (!$id) {
            $id = $request->request->get('simple_form')['id'];
            if (!$id) {
                $this->addFlash('error', 'Datos no válidos.');
                return $this->redirectToRoute('_seguridad_entidad');
            }
        }

        $entity = $this->getDoctrine()->getRepository('AppBundle:Entidad')->find($id);
        $form = $this->createForm(EntidadType::class, $entity);
        $oldEntity = $entity->__toString();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utils = $this->get('app.utils');
            if (strtolower($entity) == strtolower($oldEntity)) {
                $utils->saveLog($this->getUser(), 'Editar entidad: [' . $entity->$oldEntity . '], no ha sido modificada.');
                $this->addFlash('info', 'La entidad no ha sido modificada.');
                return $this->redirectToRoute('_seguridad_entidad');
            }
            $entities = $this->getDoctrine()->getRepository('AppBundle:Entidad')->findAll();
            foreach ($entities as $item) {
                if ($entity->getId() != $item->getId() && $entity->getNombre() == $item->getNombre()) {
                    $this->addFlash('warning', 'La entidad ya existe.');
                    return $this->redirectToRoute('_seguridad_entidad');
                }
            }
            $em->flush();
            $utils->saveLog($this->getUser(), 'Editar entidad: De: [' . $entity->$oldEntity . '] A: [' . $entity . ']');
            $this->addFlash('success', 'Entidad actualizada con éxito.');
            return $this->redirectToRoute('_seguridad_entidad');
        }

        return $this->render(
            'security/entidad_edit.html.twig',
            array(
                'menu' => 'seguridad',
                'submenu' => 'seguridad_entidad',
                'entity' => $entity,
                'formEntidad' => $form->createView(),
            ));
    }

    /**
     * @Route("/seguridad/entidad/delete", name="_seguridad_entidad_delete")
     */
    public function delEntidadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SimpleFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $request->request->get('simple_form')['id'];
            $entity = $this->getDoctrine()->getRepository('AppBundle:Entidad')->find($id);
            if (!$entity) {
                $this->addFlash('warning', 'La entidad no existe.');
                return $this->redirectToRoute('_seguridad_entidad');
            }

            if (!$entity->getExterna()) {
                foreach ($entity->getTrabajadores() as $item) {
                    $item->setEntidad(null);
                }
            }

            $em->remove($entity);
            $em->flush();

            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Eliminar entidad: [' . $entity->__toString() . ']');
            $this->addFlash('success', 'Entidad eliminada con éxito.');
            return $this->redirectToRoute('_seguridad_entidad');
        }

        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_seguridad_entidad');
    }
}
