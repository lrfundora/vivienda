<?php
/**
 * Created by PhpStorm.
 * User: LARRY
 * Date: 3/17/2016
 * Time: 11:53 AM
 */

namespace AppBundle\Form\DataTransformer;


use AppBundle\Entity\Entidad;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToEntidadTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * Transforms an object (entidad) to a string (id).
     *
     * @param Entidad|null $entity
     * @return string
     */
    public function transform($entity)
    {
        if (null === $entity) {
            return '';
        }
        return $entity->getId();
    }

    /**
     * Transforms a string (id) to an object (entidad).
     *
     * @param string $id
     * @return Entidad|null
     * @throws TransformationFailedException if object (entidad) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return;
        }

        $entity = $this->em->getRepository('AppBundle:Entidad')->find($id);

        if (null === $entity) {
            throw new TransformationFailedException(
                sprintf(
                'la entidad con id "%s" no existe!', $id
            ));
        }
        return $entity;
    }

}