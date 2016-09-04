<?php

namespace AppBundle\Repository;

/**
 * ArrendamientoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArrendamientoRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllActive()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a FROM AppBundle:Arrendamiento a WHERE a.isBaja=FALSE ORDER BY a.numeroRegistro ASC')
            ->getResult();
    }

    public function findAllInactive()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a FROM AppBundle:Arrendamiento a WHERE a.isBaja=TRUE')
            ->getResult();
    }

    public function reporteXArrendamientos()
    {
        $a = $this->getEntityManager()
            ->createQuery('SELECT COUNT(a) FROM AppBundle:Arrendamiento a WHERE a.isBaja=FALSE')
            ->getSingleScalarResult();
        $i = $this->getEntityManager()
            ->createQuery('SELECT COUNT(a) FROM AppBundle:Arrendamiento a WHERE a.isBaja=TRUE')
            ->getSingleScalarResult();
        return [$a, $i];
    }

    public function reporteXPlanAnual()
    {
        $today = date_format(new \DateTime('now'), 'Y');
        return $this->getEntityManager()
            ->createQuery('SELECT a FROM AppBundle:Arrendamiento a JOIN a.pagos p WHERE p.ano=:today')
            ->setParameter('today', $today)
            ->getResult();
    }

    public function DynamicReport($column, $search, $string, $complete)
    {
        $dql = 'SELECT a FROM AppBundle:Arrendamiento a JOIN a.clientes c';

        if ($column == 'carnet' || $column == 'nombre' || $column == 'apellidos' || $column == 'direccion') {
            $table = 'c';
        } else {
            $table = 'a';
        }

        if ($column == 'fechaContrato') {
            $string = date_format(date_create_from_format('d/m/Y', $string), 'Y-m-d');
        }

        if ($string != '') {
            if ($search == '1') {
                $dql .= " WITH " . $table . "." . $column . " LIKE '%" . $string . "%'";
            } else if ($search == '2') {
                $dql .= " WITH " . $table . "." . $column . " LIKE '" . $string . "%'";
            } else if ($search == '3') {
                $dql .= " WHERE " . $table . "." . $column . "='" . $string . "'";
            } else {
                $dql .= " WITH " . $table . "." . $column . " LIKE '%" . $string . "'";
            }
        }

        if ($complete && $search == '3' && $string != '') {
            $dql .= ' AND a.isBaja=TRUE';
        } else if ($complete && $string == '') {
            $dql .= ' WHERE a.isBaja=TRUE';
        }

        return $this->getEntityManager()
            ->createQuery($dql)
            ->getResult();
    }
}
