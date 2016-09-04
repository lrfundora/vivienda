<?php
/**
 * Created by PhpStorm.
 * User: LARRY
 * Date: 3/17/2016
 * Time: 11:53 AM
 */

namespace AppBundle\Form\DataTransformer;


use AppBundle\Entity\EscalaSalarial;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToEscalaSalarialTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * Transforms an object (escala) to a string (id).
     *
     * @param EscalaSalarial|null $entity
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
     * Transforms a string (id) to an object (escala).
     *
     * @param string $id
     * @return EscalaSalarial|null
     * @throws TransformationFailedException if object (escala) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return;
        }

        $entity = $this->em->getRepository('AppBundle:EscalaSalarial')->find($id);

        if (null === $entity) {
            throw new TransformationFailedException(
                sprintf(
                'La escala salarial con id "%s" no existe!', $id
            ));
        }
        return $entity;
    }

}