<?php
/**
 * Created by PhpStorm.
 * User: LARRY
 * Date: 3/17/2016
 * Time: 11:53 AM
 */

namespace AppBundle\Form\DataTransformer;


use AppBundle\Entity\Contrato;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToContratoTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * Transforms an object (contrato) to a string (id).
     *
     * @param Contrato|null $entity
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
     * Transforms a string (id) to an object (contrato).
     *
     * @param string $id
     * @return Contrato|null
     * @throws TransformationFailedException if object (contrato) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return;
        }

        $entity = $this->em->getRepository('AppBundle:Contrato')->find($id);

        if (null === $entity) {
            throw new TransformationFailedException(
                sprintf(
                'El contrato con id "%s" no existe!', $id
            ));
        }
        return $entity;
    }

}