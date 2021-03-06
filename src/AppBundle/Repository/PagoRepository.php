<?php

namespace AppBundle\Repository;

/**
 * PagoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PagoRepository extends \Doctrine\ORM\EntityRepository
{
    public function findMesesSinPagar($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Pago p JOIN p.arrendamiento a WHERE a.id=:id AND a.isBaja=FALSE AND p.pagado=FALSE ORDER BY p.ano AND p.mes')
            ->setParameter('id', $id)
            ->getResult();
    }

    public function findNeedNewPay($id)
    {
        $year = date_format(new \DateTime('now'), 'Y');
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Pago p JOIN p.arrendamiento a WHERE p.ano=:now AND a.id=:id AND a.isBaja=FALSE')
            ->setParameters(['id' => $id, 'now' => $year])
            ->getResult();
    }
}
