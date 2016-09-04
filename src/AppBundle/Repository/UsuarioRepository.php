<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Usuario;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UsuarioRepository extends EntityRepository implements UserLoaderInterface
{
    public function findAll($role = null)
    {
        $em = $this->getEntityManager();

        if ($role) {
            $dql = 'SELECT u FROM AppBundle:Usuario u JOIN u.roles r WHERE r.role=:role';
            $result = $em->createQuery($dql)
                ->setParameter('role', $role)
                ->getResult();
        } else {
            $dql = 'SELECT u FROM AppBundle:Usuario u';
            $result = $em->createQuery($dql)
                ->getResult();
        }

        return $result;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.user = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            $message = sprintf(
                'Unable to find an active admin AppBundle:Usuario object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message);
        }

        return $user;
    }

//    public function refreshUser(UserInterface $user)
//    {
//
//        $class = get_class($user);
//        if (!$this->supportsClass($class)) {
//            throw new UnsupportedUserException(
//                sprintf(
//                    'Instances of "%s" are not supported.',
//                    $class
//                )
//            );
//        }
//
//        return $this->find($user->getId());
//
//    }
//
//    public function supportsClass($class)
//    {
//
//        return $this->getEntityName() === $class
//        || is_subclass_of($class, $this->getEntityName());
//
//    }

    public function isExistUser(Usuario $entity)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u FROM AppBundle:Usuario u WHERE u.id!=:id AND u.user=:username')
            ->setParameters(['id' => $entity->getId(), 'username' => $entity->getUser()])
            ->getOneOrNullResult();
    }

    public function reporteXEstadoUsuario()
    {
        $a = $this->getEntityManager()
            ->createQuery('SELECT COUNT(u) FROM AppBundle:Usuario u WHERE u.isActive=TRUE')
            ->getSingleScalarResult();
        $i = $this->getEntityManager()
            ->createQuery('SELECT COUNT(u) FROM AppBundle:Usuario u WHERE u.isActive=FALSE')
            ->getSingleScalarResult();

        return [$a, $i];
    }
}
