<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TrabajadorRepository extends EntityRepository
{
    public function trabajadoresActivos()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT t FROM AppBundle:Trabajador t WHERE t.isBaja=FALSE ORDER BY t.nombre ASC')
            ->getResult();
    }

    public function trabajadoresBajas()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT t FROM AppBundle:Trabajador t WHERE t.isBaja=TRUE ORDER BY t.nombre ASC')
            ->getResult();
    }

    public function trabajadoresSinUsuario($cargo = null)
    {
        $em = $this->getEntityManager();

        if ($cargo) {
            $dql = 'SELECT t FROM AppBundle:Trabajador t JOIN t.cargo c WHERE t.isBaja=FALSE AND t.usuario IS NULL AND c.nombre=:cargo ORDER BY t.nombre ASC';
            $result = $em->createQuery($dql)
                ->setParameter('cargo', $cargo)
                ->getResult();
        } else {
            $dql = 'SELECT t FROM AppBundle:Trabajador t WHERE t.isBaja=FALSE AND t.usuario IS NULL ORDER BY t.nombre ASC';
            $result = $em->createQuery($dql)
                ->getResult();
        }

        return $result;
    }

    public function trabajadoresUsuarioCargo($cargo = null)
    {
        $em = $this->getEntityManager();

        if ($cargo) {
            $dql = 'SELECT t FROM AppBundle:Trabajador t JOIN t.cargo c WHERE t.isBaja=FALSE AND t.usuario IS NOT NULL AND c.nombre=:cargo ORDER BY t.nombre ASC';
            $result = $em->createQuery($dql)
                ->setParameter('cargo', $cargo)
                ->getResult();
        } else {
            $result = false;
        }

        return $result;
    }

    public function reporteXGenero()
    {
        $h = $this->getEntityManager()
            ->createQuery('SELECT COUNT(t) FROM AppBundle:Trabajador t WHERE t.isBaja=FALSE AND t.sexo=TRUE')
            ->getSingleScalarResult();
        $m = $this->getEntityManager()
            ->createQuery('SELECT COUNT(t) FROM AppBundle:Trabajador t WHERE t.isBaja=FALSE AND t.sexo=FALSE')
            ->getSingleScalarResult();

        return [$h, $m];
    }

    public function reporteXGeneroBaja()
    {
        $h = $this->getEntityManager()
            ->createQuery('SELECT COUNT(t) FROM AppBundle:Trabajador t WHERE t.isBaja=TRUE AND t.sexo=TRUE')
            ->getSingleScalarResult();
        $m = $this->getEntityManager()
            ->createQuery('SELECT COUNT(t) FROM AppBundle:Trabajador t WHERE t.isBaja=TRUE AND t.sexo=FALSE')
            ->getSingleScalarResult();

        return [$h, $m];
    }

    public function reporteXTrabajador()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT t, COUNT(t) FROM AppBundle:Trabajador t WHERE t.isBaja=FALSE GROUP BY t.fechaAlta')
            ->getResult();
    }

    public function reporteXTrabajadorBaja()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT t, COUNT(t) FROM AppBundle:Trabajador t WHERE t.isBaja=TRUE GROUP BY t.fechaAlta')
            ->getResult();
    }

    public function DynamicReport($column, $search, $string, $hombre, $mujer, $baja)
    {
        $dql = 'SELECT w FROM AppBundle:Trabajador w JOIN w.cargo car JOIN w.categoria cat JOIN w.contrato con JOIN w.entidad ent JOIN w.escalaSalarial esc JOIN w.escolaridad grado JOIN w.integraciones i';

        if ($column == 'carnet' || $column == 'nombre' || $column == 'apellidos' || $column == 'direccion' || $column == 'fechaAlta') {
            $table = 'w.' . $column;
        } else if ($column == 'cargo') {
            $table = 'car.nombre';
        } else if ($column == 'categoria') {
            $table = 'cat.nombre';
        } else if ($column == 'contrato') {
            $table = 'con.tipo';
        } else if ($column == 'categoria') {
            $table = 'cat.nombre';
        } else if ($column == 'entidad') {
            $table = 'ent.nombre';
        } else if ($column == 'escalaSalarial') {
            $table = 'esc.escala';
        } else if ($column == 'escolaridad') {
            $table = 'grado.nivel';
        } else {
            $table = 'i.nombre';
        }

        if ($column == 'fechaAlta') {
            $string = date_format(date_create_from_format('d/m/Y', $string), 'Y-m-d');
        }

        if ($string != '') {
            if ($column == 'integracion') {
                $arr = preg_split('/,/', $string);
                $string = $arr[0];
                $dql .= " WHERE " . $table . "='" . $string . "'";
                for ($i = 1; $i < sizeof($arr); $i++) {
                    $dql .= " AND EXISTS (SELECT int" . $i . " FROM AppBundle:Integracion int" . $i . " JOIN int" . $i . ".trabajadores t".$i." WHERE t".$i.".id=w.id AND int" . $i . ".nombre='" . $arr[$i] . "')";
                }
            } else {
                if ($search == '1') {
                    $dql .= " WITH " . $table . " LIKE '%" . $string . "%'";
                } else if ($search == '2') {
                    $dql .= " WITH " . $table . " LIKE '" . $string . "%'";
                } else if ($search == '3') {
                    $dql .= " WHERE " . $table . "='" . $string . "'";
                } else {
                    $dql .= " WITH " . $table . " LIKE '%" . $string . "'";
                }
            }

            if ($hombre && !$mujer) {
                if ($search == '3' || $column == 'integracion') {
                    $dql .= ' AND w.sexo=true';
                } else {
                    $dql .= ' WHERE w.sexo=true';
                }
            }

            if (!$hombre && $mujer) {
                if ($search == '3' || $column == 'integracion') {
                    $dql .= ' AND w.sexo=false';
                } else {
                    $dql .= ' WHERE w.sexo=false';
                }
            }
        }

        if ($baja && ($search == '3' || $column == 'integracion') && $string != '') {
            $dql .= ' AND w.isBaja=TRUE';
        } else if ($baja && $string == '') {
            $dql .= ' WHERE w.isBaja=TRUE';
        }
//        return $dql;

        return $this->getEntityManager()
            ->createQuery($dql)
            ->getResult();
    }
}
