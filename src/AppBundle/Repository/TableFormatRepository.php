<?php

namespace AppBundle\Repository;

/**
 * TableFormatRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TableFormatRepository extends \Doctrine\ORM\EntityRepository
{
    public function countTableFormats($user)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
