<?php

namespace AppBundle\Repository;

/**
 * ConfigRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConfigRepository extends \Doctrine\ORM\EntityRepository
{
    public function countConfigs($user)
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->where('s.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

}
