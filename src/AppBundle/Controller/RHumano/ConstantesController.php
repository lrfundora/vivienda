<?php

namespace AppBundle\Controller\RHumano;

use AppBundle\Entity\Cargo;
use AppBundle\Entity\Categoria;
use AppBundle\Entity\Contrato;
use AppBundle\Entity\Entidad;
use AppBundle\Entity\EscalaSalarial;
use AppBundle\Entity\Escolaridad;
use AppBundle\Entity\Integracion;
use AppBundle\Form\Type\CargoType;
use AppBundle\Form\Type\CategoriaType;
use AppBundle\Form\Type\ContratoType;
use AppBundle\Form\Type\EntidadType;
use AppBundle\Form\Type\EscalaSalarialType;
use AppBundle\Form\Type\EscolaridadType;
use AppBundle\Form\Type\IntegracionType;
use AppBundle\Form\Type\SimpleFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/rh/constantes")
 */
class ConstantesController extends Controller
{
    /**
     * @Route("/", name="_rh_constantes")
     */
    public function indexAction()
    {
        $request = Request::createFromGlobals();
        $tab = $request->query->get('tab');
        if (!$tab) {
            $tab = 1;
        }

        $cargos = $this->getDoctrine()->getRepository('AppBundle:Cargo')->findAll();
        $categorias = $this->getDoctrine()->getRepository('AppBundle:Categoria')->findAll();
        $contratos = $this->getDoctrine()->getRepository('AppBundle:Contrato')->findAll();
        $escalas = $this->getDoctrine()->getRepository('AppBundle:EscalaSalarial')->findAll();
        $escolaridad = $this->getDoctrine()->getRepository('AppBundle:Escolaridad')->findAll();
        $integraciones = $this->getDoctrine()->getRepository('AppBundle:Integracion')->findAll();
        $entidades = $this->getDoctrine()->getRepository('AppBundle:Entidad')->findBy(['externa' => false]);

        $formCargo = $this->createForm(CargoType::class, new Cargo());
        $formCategoria = $this->createForm(CategoriaType::class, new Categoria());
        $formContrato = $this->createForm(ContratoType::class, new Contrato());
        $formEscalaSalarial = $this->createForm(EscalaSalarialType::class, new EscalaSalarial());
        $formEscolaridad = $this->createForm(EscolaridadType::class, new Escolaridad());
        $formIntegracion = $this->createForm(IntegracionType::class, new Integracion());
        $formEntidad = $this->createForm(EntidadType::class, new Entidad());

        $formDelete = $this->createForm(SimpleFormType::class);

        return $this->render(
            'recursos_humano/constantes.html.twig',
            array(
                'menu' => 'rh',
                'submenu' => 'rh_constantes',
                'tab' => $tab,
                'cargos' => $cargos,
                'categorias' => $categorias,
                'contratos' => $contratos,
                'escalas' => $escalas,
                'escolaridad' => $escolaridad,
                'integraciones' => $integraciones,
                'entidades' => $entidades,
                'formCargo' => $formCargo->createView(),
                'formCategoria' => $formCategoria->createView(),
                'formContrato' => $formContrato->createView(),
                'formEscalaSalarial' => $formEscalaSalarial->createView(),
                'formEscolaridad' => $formEscolaridad->createView(),
                'formIntegracion' => $formIntegracion->createView(),
                'formEntidad' => $formEntidad->createView(),
                'formDelete' => $formDelete->createView(),
            ));
    }

    /**
     * @Route("/{tab}/add", name="_rh_add")
     */
    public function addAction(Request $request, $tab)
    {
        $em = $this->getDoctrine()->getManager();

        switch ($tab) {
            case 1:
                $entity = new Cargo();
                $form = $this->createForm(CargoType::class, $entity);
                $msgSuccess = 'Nuevo cargo agregado con éxito.';
                $msgWarning = 'El cargo ya existe.';
                $msgLog = 'Agregar cargo: ';
                break;
            case 2:
                $entity = new Categoria();
                $form = $this->createForm(CategoriaType::class, $entity);
                $msgSuccess = 'Nueva categoría ocupacional agregada con éxito.';
                $msgWarning = 'La categoría ocupacional ya existe.';
                $msgLog = 'Agregar categoría: ';
                break;
            case 3:
                $entity = new Contrato();
                $form = $this->createForm(ContratoType::class, $entity);
                $msgSuccess = 'Nuevo tipo de contrato agregado con éxito.';
                $msgWarning = 'El tipo de contrato ya existe.';
                $msgLog = 'Agregar tipo de contrato: ';
                break;
            case 4:
                $entity = new EscalaSalarial();
                $form = $this->createForm(EscalaSalarialType::class, $entity);
                $msgSuccess = 'Nueva escala salarial agregada con éxito.';
                $msgWarning = 'La escala salarial ya existe.';
                $msgLog = 'Agregar escala salarial: ';
                break;
            case 5:
                $entity = new Escolaridad();
                $form = $this->createForm(EscolaridadType::class, $entity);
                $msgSuccess = 'Nuevo nivel escolar agregado con éxito.';
                $msgWarning = 'El nivel escolar ya existe.';
                $msgLog = 'Agregar nivel escolar: ';
                break;
            case 6:
                $entity = new Integracion();
                $form = $this->createForm(IntegracionType::class, $entity);
                $msgSuccess = 'Nueva integración revolucionaria agregada con éxito.';
                $msgWarning = 'El tipo de integración ya existe.';
                $msgLog = 'Agregar integración revolucionaria: ';
                break;
            case 7:
                $entity = new Entidad();
                $form = $this->createForm(EntidadType::class, $entity);
                $msgSuccess = 'Nueva entidad agregada con éxito.';
                $msgWarning = 'La entidad ya existe.';
                $msgLog = 'Agregar entidad: ';
                break;
            default:
                $this->addFlash('error', 'Datos no válidos.');
                return $this->redirectToRoute('_rh_constantes');
                break;
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->find($tab, $entity)) {
                $this->addFlash('warning', $msgWarning);
                return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
            }
            $em->persist($entity);
            $em->flush();

            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), $msgLog .'['. $entity->__toString().']');
            $this->addFlash('success', $msgSuccess);
            return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
        }

        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
    }

    /**
     * @Route("/{tab}/{id}/edit", name="_rh_edit")
     */
    public function editAction(Request $request, $tab, $id)
    {
        $em = $this->getDoctrine()->getManager();

        switch ($tab) {
            case 1:
                $entity = $em->getRepository('AppBundle:Cargo')->find($id);
                $form = $this->createForm(CargoType::class, $entity);
                $msgInfo = 'El cargo no ha sido modificado.';
                $msgSuccess = 'El cargo ha sido actualizado con éxito.';
                $msgWarning = 'El cargo no ha podido ser actualizado porque ya existe.';
                $msgLog = 'Editar cargo: ';
                break;
            case 2:
                $entity = $em->getRepository('AppBundle:Categoria')->find($id);
                $form = $this->createForm(CategoriaType::class, $entity);
                $msgInfo = 'La categoría ocupacional no ha sido modificada.';
                $msgSuccess = 'La categoría ocupacional ha sido actualizada con éxito.';
                $msgWarning = 'La categoría no ha podido ser actualizada porque ya existe.';
                $msgLog = 'Editar categoría: ';
                break;
            case 3:
                $entity = $em->getRepository('AppBundle:Contrato')->find($id);
                $form = $this->createForm(ContratoType::class, $entity);
                $msgInfo = 'El tipo de contrato no ha sido modificado.';
                $msgSuccess = 'El tipo de contrato ha sido actualizado con éxito.';
                $msgWarning = 'El tipo de contrato no ha podido ser actualizado porque ya existe.';
                $msgLog = 'Editar tipo de contrato: ';
                break;
            case 4:
                $entity = $em->getRepository('AppBundle:EscalaSalarial')->find($id);
                $form = $this->createForm(EscalaSalarialType::class, $entity);
                $msgInfo = 'La escala salarial no ha sido modificada.';
                $msgSuccess = 'La escala salarial ha sido actualizada con éxito.';
                $msgWarning = 'La escala salarial no ha podido ser actualizada porque ya existe.';
                $msgLog = 'Editar escala salarial: ';
                break;
            case 5:
                $entity = $em->getRepository('AppBundle:Escolaridad')->find($id);
                $form = $this->createForm(EscolaridadType::class, $entity);
                $msgInfo = 'El nivel escolar no ha sido modificado.';
                $msgSuccess = 'El nivel escolar ha sido actualizado con éxito.';
                $msgWarning = 'El nivel escolar no ha podido ser actualizado porque ya existe.';
                $msgLog = 'Editar nivel escolar: ';
                break;
            case 6:
                $entity = $em->getRepository('AppBundle:Integracion')->find($id);
                $form = $this->createForm(IntegracionType::class, $entity);
                $msgInfo = 'La integración revolucionaria no ha sido modificada.';
                $msgSuccess = 'La integración revolucionaria ha sido actualizada con éxito.';
                $msgWarning = 'La integración revolucionaria no ha podido ser actualizada porque ya existe.';
                $msgLog = 'Editar integración revolucionaria: ';
                break;
            case 7:
                $entity = $em->getRepository('AppBundle:Entidad')->find($id);
                $form = $this->createForm(EntidadType::class, $entity);
                $msgInfo = 'La entidad no ha sido modificada.';
                $msgSuccess = 'La entidad ha sido actualizada con éxito.';
                $msgWarning = 'La entidad no ha podido ser actualizada porque ya existe.';
                $msgLog = 'Editar entidad: ';
                break;
            default:
                $this->addFlash('error', 'Datos no válidos.');
                return $this->redirectToRoute('_rh_constantes');
                break;
        }
        if (!$entity) {
            $this->addFlash('error', 'El dato no ha podido ser actualizado porque no existe!');
            return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
        }
        $entityOld = $entity->__toString();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utils = $this->get('app.utils');
            if (strtolower($entity) == strtolower($entityOld)) {
                $utils->saveLog($this->getUser(), $msgLog . '[' . $entityOld . '], no ha sido modificado.');
                $this->addFlash('info', $msgInfo);
                return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
            }
            if ($this->find($tab, $entity)) {
                $this->addFlash('warning', $msgWarning);
                return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
            }
            $em->flush();

            $utils->saveLog($this->getUser(), $msgLog . 'De: [' . $entityOld . '] A: [' . $entity.']');
            $this->addFlash('success', $msgSuccess);
            return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
        }

        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
    }

    /**
     * @Route("/{tab}/delete", name="_rh_delete")
     */
    public function deleteAction(Request $request, $tab)
    {
        if ($tab < 1 || $tab > 7) {
            $this->addFlash('error', 'Datos no válidos.');
            return $this->redirectToRoute('_rh_constantes');
        }

        $form = $this->createForm(SimpleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form->getData()['id'];
            $em = $this->getDoctrine()->getManager();
            switch ($tab) {
                case 1:
                    $msgSuccess = 'El cargo ha sido eliminado con éxito.';
                    $msgWarning = 'El cargo que intentó eliminar no existe.';
                    $entity = $em->getRepository('AppBundle:Cargo')->find($id);
                    $msgLog = 'Eliminar cargo: ';
                    break;
                case 2:
                    $msgSuccess = 'La categoría ocupacional ha sido eliminada con éxito.';
                    $msgWarning = 'La categoría ocupacional que intentó eliminar no existe.';
                    $entity = $em->getRepository('AppBundle:Categoria')->find($id);
                    $msgLog = 'Eliminar categoría: ';
                    break;
                case 3:
                    $msgSuccess = 'El tipo de contrato ha sido eliminado con éxito.';
                    $msgWarning = 'El tipo de contrato que intentó eliminar no existe.';
                    $entity = $em->getRepository('AppBundle:Contrato')->find($id);
                    $msgLog = 'Eliminar tipo de contrato: ';
                    break;
                case 4:
                    $msgSuccess = 'La escala salarial ha sido eliminada con éxito.';
                    $msgWarning = 'La escala salarial que intentó eliminar no existe.';
                    $entity = $em->getRepository('AppBundle:EscalaSalarial')->find($id);
                    $msgLog = 'Eliminar escala salarial: ';
                    break;
                case 5:
                    $msgSuccess = 'El nivel escolar ha sido eliminado con éxito.';
                    $msgWarning = 'El nivel escolar que intentó eliminar no existe.';
                    $entity = $em->getRepository('AppBundle:Escolaridad')->find($id);
                    $msgLog = 'Eliminar nivel escolar: ';
                    break;
                case 6:
                    $msgSuccess = 'La integración revolucionaria ha sido eliminada con éxito.';
                    $msgWarning = 'La integración revolucionaria que intentó eliminar no existe.';
                    $entity = $em->getRepository('AppBundle:Integracion')->find($id);
                    $msgLog = 'Eliminar integración revolucionaria: ';
                    break;
                case 7:
                    $msgSuccess = 'La entidad ha sido eliminada con éxito.';
                    $msgWarning = 'La entidad que intentó eliminar no existe.';
                    $entity = $em->getRepository('AppBundle:Entidad')->find($id);
                    $msgLog = 'Eliminar entidad: ';
                    break;
            }
            if (!$entity) {
                $this->addFlash('warning', $msgWarning);
                return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
            }
            switch ($tab) {
                case 1:
                    foreach ($entity->getTrabajadores() as $trabajador) {
                        $trabajador->setCargo(null);
                        $em->persist($trabajador);
                    }
                    break;
                case 2:
                    foreach ($entity->getTrabajadores() as $trabajador) {
                        $trabajador->setCategoria(null);
                        $em->persist($trabajador);
                    }
                    break;
                case 3:
                    foreach ($entity->getTrabajadores() as $trabajador) {
                        $trabajador->setContrato(null);
                        $em->persist($trabajador);
                    }
                    break;
                case 4:
                    foreach ($entity->getTrabajadores() as $trabajador) {
                        $trabajador->setEscalaSalarial(null);
                        $em->persist($trabajador);
                    }
                    break;
                case 5:
                    foreach ($entity->getTrabajadores() as $trabajador) {
                        $trabajador->setEscolaridad(null);
                        $em->persist($trabajador);
                    }
                    break;
                case 6:
                    foreach ($entity->getTrabajadores() as $trabajador) {
                        $entity->removeTrabajador($trabajador);
                        $em->persist($entity);
                    }
                    break;
                case 7:
                    foreach ($entity->getTrabajadores() as $trabajador) {
                        $trabajador->setEntidad(null);
                        $em->persist($trabajador);
                    }
                    break;
            }
            $em->remove($entity);
            $em->flush();

            $utils = $this->get('app.utils');
            $utils->saveLog($this->getUser(), $msgLog .'['. $entity->__toString().']');
            $this->addFlash('success', $msgSuccess);
            return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
        }

        $this->addFlash('error', 'Datos no válidos.');
        return $this->redirectToRoute('_rh_constantes', array('tab' => $tab));
    }

    /**
     * @param $tab
     * @param $entity
     * @return bool|object
     */
    private function find($tab, $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $isExist = false;
        switch ($tab) {
            case 1:
                $isExist = $em->getRepository('AppBundle:Cargo')->findOneBy(['nombre' => $entity->getNombre()]);
                break;
            case 2:
                $isExist = $em->getRepository('AppBundle:Categoria')->findOneBy(['nombre' => $entity->getNombre()]);
                break;
            case 3:
                $isExist = $em->getRepository('AppBundle:Contrato')->findOneBy(['tipo' => $entity->getTipo()]);
                break;
            case 4:
                $isExist = $em->getRepository('AppBundle:EscalaSalarial')->findOneBy(['escala' => $entity->getEscala(), 'salario' => $entity->getSalario()]);
                break;
            case 5:
                $isExist = $em->getRepository('AppBundle:Escolaridad')->findOneBy(['nivel' => $entity->getNivel()]);
                break;
            case 6:
                $isExist = $em->getRepository('AppBundle:Integracion')->findOneBy(['nombre' => $entity->getNombre()]);
                break;
            case 7:
                $isExist = $em->getRepository('AppBundle:Entidad')->findOneBy(['nombre' => $entity->getNombre()]);
                break;
        }
        return $isExist;
    }
}
