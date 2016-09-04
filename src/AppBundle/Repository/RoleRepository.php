<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{

    public function findAll()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM AppBundle:Role r WHERE r.role!=:role ORDER BY r.nombre ASC')
            ->setParameter('role', 'ROLE_ADMIN')
            ->getResult();
    }

    public function findRole($role)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM AppBundle:Role r WHERE r.role=:name ORDER BY r.nombre ASC')
            ->setParameter('name', $role)
            ->getResult();
    }
}
