<?php
/**
 * Created by PhpStorm.
 * User: LARRY
 * Date: 3/17/2016
 * Time: 11:53 AM
 */

namespace AppBundle\Form\DataTransformer;


use AppBundle\Entity\Categoria;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToCategoriaTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * Transforms an object (categoria) to a string (id).
     *
     * @param Categoria|null $entity
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
     * Transforms a string (id) to an object (categoria).
     *
     * @param string $id
     * @return Categoria|null
     * @throws TransformationFailedException if object (categoria) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return;
        }

        $entity = $this->em->getRepository('AppBundle:Categoria')->find($id);

        if (null === $entity) {
            throw new TransformationFailedException(
                sprintf(
                'La categoría con id "%s" no existe!', $id
            ));
        }
        return $entity;
    }

}