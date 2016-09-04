<?php

namespace AppBundle\Controller\Security;

use AppBundle\Entity\Usuario;
use AppBundle\Form\Type\UsuarioType;
use AppBundle\Form\Type\UsuarioInfoType;
use AppBundle\Form\Type\UsuarioPassType;
use AppBundle\Form\Type\SimpleFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/seguridad")
 */
class UsuarioController extends Controller
{
    /**
     * @Route("/usuarios", name="_seguridad_usuario")
     */
    public function indexAction()
    {
        $authChecker = $this->get('security.authorization_checker');
        if ($authChecker->isGranted('ROLE_JABOGADO')) {
            $usuarios = $this->getDoctrine()->getRepository('AppBundle:Usuario')->findAll('ROLE_ABOGADO');
        } elseif ($authChecker->isGranted('ROLE_JRH')) {
            $usuarios = $this->getDoctrine()->getRepository('AppBundle:Usuario')->findAll('ROLE_RH');
        } else {
            $usuarios = $this->getDoctrine()->getRepository('AppBundle:Usuario')->findAll();
        }

        $formInfo = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_seguridad_usuario_info')));
        $formEdit = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_seguridad_usuario_edit')));
        $formDelete = $this->createForm(SimpleFormType::class, null, array('action' => $this->generateUrl('_seguridad_usuario_delete')));


        return $this->render(
            'security/usuarios.html.twig',
            array(
                'menu' => 'seguridad',
                'submenu' => 'seguridad_usuario',
                'usuarios' => $usuarios,
                'formInfo' => $formInfo->createView(),
                'formEdit' => $formEdit->createView(),
                'formDelete' => $formDelete->createView(),
            )
        );
    }

    /**
     * @Route("/usuario/info", name="_seguridad_usuario_info")
     */
    public function infoUsuarioAction(Request $request)
    {
        $id = $request->request->get('simple_form')['id'];
        if (!$id) {
            $this->addFlash('error', 'Datos no válido.');
            return $this->redirectToRoute('_seguridad_usuario');
        }

        $usuario = $this->getDoctrine()->getRepository('AppBundle:Usuario')->find($id);

        if (!$usuario) {
            $this->addFlash('error', 'El usuario del cual ha solicitado información no existe.');
            return $this->redirectToRoute('_seguridad_usuario');
        }
        $utils = $this->get('app.utils');
        $utils->saveLog($this->getUser(), 'Consultar información de usuario: [' . $usuario->__toString() . ']');
        return $this->render('security/usuario_info.html.twig',
            array(
                'menu' => 'seguridad',
                'submenu' => 'seguridad_usuario',
                'usuario' => $usuario
            ));
    }

    /**
     * @Route("/usuario/nuevo", name="_seguridad_usuario_new")
     */
    public function nuevoUsuarioAction(Request $request)
    {
        $entity = new Usuario();
        $form = $this->createForm(UsuarioType::class, $entity);

        $authChecker = $this->get('security.authorization_checker');
        if ($authChecker->isGranted('ROLE_JABOGADO')) {
            $trabajadores = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresSinUsuario('Abogado');
            $roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findRole('ROLE_ABOGADO');
        } elseif ($authChecker->isGranted('ROLE_JRH')) {
            $trabajadores = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresSinUsuario('Recursos Humano');
            $roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findRole('ROLE_RH');
        } else {
            $trabajadores = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresSinUsuario();
            $roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findAll();
        }

        $idTrabajador = '';
        if ($request->get('idTrabajador')) {
            $idTrabajador = $request->get('idTrabajador');
        }
        $idRoles = '';
        if ($request->get('roles')) {
            $idRoles = $request->get('roles');
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $idTrabajador = $request->get('idTrabajador');
            $Trabajador = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($idTrabajador);
            if (!$Trabajador) {
                $this->addFlash('error', 'El trabajador seleccionado ya no existe.');
            } else {
                $isUsuario = $this->getDoctrine()->getRepository('AppBundle:Usuario')->findOneBy(['user' => $entity->getUser()]);
                if ($isUsuario) {
                    $this->addFlash('error', 'El nombre de usuario ya existe, escoja otro diferente.');
                } else {

                    $arrRoles = preg_split('/,/', $idRoles);
                    foreach ($arrRoles as $id) {
                        $role = $this->getDoctrine()->getRepository('AppBundle:Role')->find($id);
                        if ($role) {
                            $entity->addRole($role);
                        }
                    }

                    $encode = $this->get('app.utils');
                    $encode->encodePassword($entity);
//                    $Trabajador->setUsuario($entity);
                    $entity->setTrabajador($Trabajador);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entity);
                    $em->flush();

                    $utils = $this->get('app.utils');
                    $utils->saveLog($this->getUser(), 'Agregar usuario: [' . $entity->getUsername() . '] A: [' . $Trabajador->__toString() . ']');

                    $this->addFlash('success', 'Usuario agregado con éxito.');
                    return $this->redirectToRoute('_seguridad_usuario');
                }
            }
        }

        return $this->render(
            'security/usuario_new.html.twig',
            array(
                'menu' => 'seguridad',
                'submenu' => 'seguridad_usuario',
                'trabajadores' => $trabajadores,
                'trabSelected' => $idTrabajador,
                'roles' => $roles,
                'rolesSelected' => $idRoles,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/usuario/edit", name="_seguridad_usuario_edit")
     */
    public function editUsuarioAction(Request $request)
    {
        $id = $request->request->get('simple_form')['id'];
        if (!$id) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_seguridad_usuario');
        }

        $entity = $this->getDoctrine()->getRepository('AppBundle:Usuario')->find($id);
        $formInfo = $this->createForm(UsuarioInfoType::class, $entity, array('action' => $this->generateUrl('_seguridad_usuario_edit_info', ['id' => $id])));
        $formPass = $this->createForm(UsuarioPassType::class, $entity, array('action' => $this->generateUrl('_seguridad_usuario_edit_pass', ['id' => $id])));

        $authChecker = $this->get('security.authorization_checker');
        if ($authChecker->isGranted('ROLE_JABOGADO')) {
            $trabajadores = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresSinUsuario('Abogado');
            $roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findRole('ROLE_ABOGADO');
        } elseif ($authChecker->isGranted('ROLE_JRH')) {
            $trabajadores = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresSinUsuario('Recursos Humano');
            $roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findRole('ROLE_RH');
        } else {
            $trabajadores = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->trabajadoresSinUsuario();
            $roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findAll();
        }

        return $this->render(
            'security/usuario_edit.html.twig',
            array(
                'menu' => 'seguridad',
                'submenu' => 'seguridad_usuario',
                'trabajadores' => $trabajadores,
                'roles' => $roles,
                'formInfo' => $formInfo->createView(),
                'formPass' => $formPass->createView(),
                'entity' => $entity
            )
        );
    }

    /**
     * @Route("/usuario/edit/{id}/info", name="_seguridad_usuario_edit_info")
     */
    public function editInfoUsuarioAction(Request $request, $id)
    {
        $request->request->add(['simple_form' => ['id' => $id]]);

        $entity = $this->getDoctrine()->getRepository('AppBundle:Usuario')->find($id);
        if (!$entity) {
            $this->addFlash('error', 'El usuario que intento editar no existe.');
            return $this->redirectToRoute('_seguridad_usuario');
        }
        $username = $entity->getUser();
        $userActive = $entity->getIsActive();
        $idTrab = $request->get('idTrabajador');

        $formInfo = $this->createForm(UsuarioInfoType::class, $entity);
        $formInfo->handleRequest($request);
        if ($formInfo->isSubmitted() && $formInfo->isValid()) {
            $utils = $this->get('app.utils');
            $newRoles = preg_split('/,/', $request->get('roles'));
            $oldRoles = [];
            foreach ($entity->getRoles() as $item) {
                array_push($oldRoles, $item->getId());
            }
            $addRoles = array_diff($newRoles, $oldRoles);
            $delRoles = array_diff($oldRoles, $newRoles);

            if ($entity->getUser() == $username && $entity->getTrabajador()->getId() == $idTrab && $addRoles == $delRoles && $entity->getIsActive() == $userActive) {
                $utils->saveLog($this->getUser(), 'Editar datos de usuario: [' . $entity->getUsername() . '], no ha sido modificado.');
                $this->addFlash('info', 'La información del usuario no ha sido modificada.');
                return $this->editUsuarioAction($request);
            }

            $Trabajador = $this->getDoctrine()->getRepository('AppBundle:Trabajador')->find($idTrab);
            if (!$Trabajador) {
                $this->addFlash('error', 'El trabajador que seleccionó no existe.');
                return $this->redirectToRoute('_seguridad_usuario');
            }

            $username = $this->getDoctrine()->getRepository('AppBundle:Usuario')->isExistUser($entity);
            if ($username) {
                $this->addFlash('error', 'El nombre de usuario ya existe, escoja otro diferente.');
                return $this->editUsuarioAction($request);
            }

            foreach ($delRoles as $item) {
                $role = $this->getDoctrine()->getRepository('AppBundle:Role')->find($item);
                $entity->removeRole($role);
            }

            foreach ($addRoles as $item) {
                $role = $this->getDoctrine()->getRepository('AppBundle:Role')->find($item);
                if ($role) {
                    $entity->addRole($role);
                }
            }

            $entity->getTrabajador()->setUsuario(null);
            $entity->setTrabajador($Trabajador);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $utils->saveLog($this->getUser(), 'Editar datos de usuario: [' . $entity->getUsername() . ']');
            $this->addFlash('success', 'Información de usuario actualizada con éxito.');
            return $this->redirectToRoute('_seguridad_usuario');

        }

        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_seguridad_usuario');
    }

    /**
     * @Route("/usuario/edit/{id}/pass", name="_seguridad_usuario_edit_pass")
     */
    public function editPassUsuarioAction(Request $request, $id)
    {
        $request->request->add(['simple_form' => ['id' => $id]]);

        $entity = $this->getDoctrine()->getRepository('AppBundle:Usuario')->find($id);
        $formPass = $this->createForm(UsuarioPassType::class, $entity);

        $formPass->handleRequest($request);
        if ($formPass->isSubmitted() && $formPass->isValid()) {
            $Usuario = $this->getDoctrine()->getRepository('AppBundle:Usuario')->find($id);
            if (!$Usuario) {
                $this->addFlash('error', 'El usuario no existe.');
                return $this->redirectToRoute('_seguridad_usuario');
            }
            $em = $this->getDoctrine()->getManager();
            $encode = $this->get('app.utils');
            $encode->encodePassword($entity);
            $em->flush();

            $encode->saveLog($this->getUser(), 'Cambiar contraseña de usuario: [' . $entity->getUsername() . ']');

            $this->addFlash('success', 'Contraseña cambiada con éxito.');
            return $this->redirectToRoute('_seguridad_usuario');
        }

        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_seguridad_usuario');
    }

    /**
     * @Route("/usuario/cambiar-contrasena", name="_seguridad_usuario_change_pass")
     */
    public function cambiarPassUsuarioAction(Request $request)
    {
        $id = $request->request->get('changePass_id');
        if (!$id) {
            return $this->redirectToRoute('_inicio');
        }

        $encode = $this->get('app.utils');
        $entity = $this->getDoctrine()->getRepository('AppBundle:Usuario')->find($id);
        $form = $this->createForm(UsuarioPassType::class, $entity);
        $oldPass = $entity->getPassword();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$encode->isPasswordValid($oldPass, $request->request->get('oldPass'), $entity->getSalt())) {
                $this->addFlash('warning', 'La contraeña actual es incorrecta.');
            } else {
                $em = $this->getDoctrine()->getManager();
                $encode->encodePassword($entity);
                $em->flush();

                $encode->saveLog($this->getUser(), 'Cambiar contraseña personal: [' . $this->getUser() . ']. Cierre de sesión automático.');
                return $this->redirectToRoute('logout');
            }
        }

        return $this->render(
            'security/usuario_pass.html.twig',
            array(
                'menu' => '',
                'submenu' => '',
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/usuario/delete", name="_seguridad_usuario_delete")
     */
    public function deleteUsuarioAction(Request $request)
    {
        $form = $this->createForm(SimpleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $usuario = $this->getDoctrine()->getRepository('AppBundle:Usuario')->find($form->getData()['id']);
            if (!$usuario) {
                $this->addFlash('error', 'El usuario no existe.');
                return $this->redirectToRoute('_seguridad_usuario');
            }
            $em = $this->getDoctrine()->getManager();
            $usuario->getTrabajador()->setUsuario(null);
            $em->remove($usuario);
            $em->flush();

            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), 'Eliminar usuario: [' . $usuario->getUsername() . ']');
            $this->addFlash('success', 'Usuario eliminado con éxito.');
            return $this->redirectToRoute('_seguridad_usuario');
        }

        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_seguridad_usuario');
    }

}
