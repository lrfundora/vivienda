<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Usuario;
use AppBundle\Entity\Log;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class Utils
{
    private $encode;
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->encode = new BCryptPasswordEncoder(15);
        $this->em = $manager;
    }

    public function md5Encoder()
    {
        return md5(uniqid(rand(), true));
    }

    public function encodePassword($entity)
    {
        $entity->setSalt($this->md5Encoder());
        $password = $this->encode->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
    }

    public function isPasswordValid($encodePass, $newPlainText, $salt)
    {
        return $this->encode->isPasswordValid($encodePass, $newPlainText, $salt);
    }

    public function saveLog(Usuario $usuario, $action)
    {
        $roles = '';
        $trabajador = '';
        foreach ($usuario->getRoles() as $role) {
            if ($roles == '') {
                $roles = $role->getNombre();
            } else {
                $roles .= ', ' . $role->getNombre();
            }
            if ($role->getRole() == 'ROLE_ADMIN') {
                $trabajador = 'Administrador del sistema';
            }
        }
        if ($trabajador == '') {
            $trabajador = $usuario->getTrabajador()->getNombreCompleto();
        }
        $log = new Log();
        $log
            ->setUsuario($usuario->getUsername())
            ->setTrabajador($trabajador)
            ->setRole($roles)
            ->setAccion($action);
        $this->em->persist($log);
        $this->em->flush();
    }
}