<?php
/**
 * Created by PhpStorm.
 * User: LARRY
 * Date: 3/17/2016
 * Time: 11:53 AM
 */

namespace AppBundle\Form\DataTransformer;


use AppBundle\Entity\Escolaridad;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToEscolaridadTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * Transforms an object (escolaridad) to a string (id).
     *
     * @param Escolaridad|null $entity
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
     * Transforms a string (id) to an object (escolaridad).
     *
     * @param string $id
     * @return Escolaridad|null
     * @throws TransformationFailedException if object (escolaridad) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return;
        }

        $entity = $this->em->getRepository('AppBundle:Escolaridad')->find($id);

        if (null === $entity) {
            throw new TransformationFailedException(
                sprintf(
                'El nivel escolar con id "%s" no existe!', $id
            ));
        }
        return $entity;
    }

}