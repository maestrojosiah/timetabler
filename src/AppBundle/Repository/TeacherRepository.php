<?php

namespace AppBundle\Repository;

/**
 * TeacherRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TeacherRepository extends \Doctrine\ORM\EntityRepository
{
    public function countTeachers($user)
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->where('s.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getFirstTeacher($user, $class)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.user = :user')
            ->andWhere('s.classs = :class')
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(1)
            ->setParameter('user', $user)
            ->setParameter('class', $class)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getLastTeacher($user, $class)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.user = :user')
            ->andWhere('s.classs = :class')
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('user', $user)
            ->setParameter('class', $class)
            ->getQuery()
            ->getOneOrNullResult();
    }

	public function previous($teacher, $class)
	{
	    return $this->getEntityManager()
	        ->createQuery(
	            'SELECT s
	            FROM AppBundle:Teacher s
	            WHERE s.id < :id
	            AND s.classs = :class
	            ORDER BY s.id DESC
	            '
	        )
	        ->setParameter(':id', $teacher)
	        ->setParameter(':class', $class)
            ->setMaxResults(1)
	        ->getResult();
	}

	public function next($teacher, $class)
	{
	    return $this->getEntityManager()
	        ->createQuery(
	            'SELECT s
	            FROM AppBundle:Teacher s
	            WHERE s.id > :id
	            AND s.classs = :class
	            ORDER BY s.id ASC
	            '
	        )
	        ->setParameter(':id', $teacher)
	        ->setParameter(':class', $class)
            ->setMaxResults(1)
	        ->getResult();
	}
}