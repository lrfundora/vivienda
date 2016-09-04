<?php
/**
 * Created by PhpStorm.
 * User: LARRY
 * Date: 3/17/2016
 * Time: 11:53 AM
 */

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Role;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToRoleTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * Transforms an object (role) to a string (id).
     *
     * @param Role|null $entity
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
     * Transforms a string (id) to an object (role).
     *
     * @param string $id
     * @return Role|null
     * @throws TransformationFailedException if object (role) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return;
        }

        $entity = $this->em->getRepository('AppBundle:Role')->find($id);

        if (null === $entity) {
            throw new TransformationFailedException(
                sprintf(
                'El role con id "%s" no existe!', $id
            ));
        }
        return $entity;
    }

}