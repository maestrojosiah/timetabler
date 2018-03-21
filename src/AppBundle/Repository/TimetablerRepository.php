<?php

namespace AppBundle\Repository;

/**
 * TimetablerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TimetablerRepository extends \Doctrine\ORM\EntityRepository
{
    public function isAlreadyRecorded($day, $class, $time)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.day = :day')
            ->andWhere('a.class = :class')
            ->andWhere('a.time = :time')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('day', $day)
            ->setParameter('class', $class)
            ->setParameter('time', $time)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function isAlreadyRecordedFormat($day, $class, $tableFormatColumn)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.day = :day')
            ->andWhere('a.class = :class')
            ->andWhere('a.tableFormatColumn = :tableFormatColumn')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('day', $day)
            ->setParameter('class', $class)
            ->setParameter('tableFormatColumn', $tableFormatColumn)
            ->getQuery()
            ->getOneOrNullResult();
    }
     public function isClashing($day, $time, $teacher)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->andWhere('a.day = :day')
            ->andWhere('a.time = :time')
            ->andWhere('a.teacher = :teacher')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('day', $day)
            ->setParameter('time', $time)
            ->setParameter('teacher', $teacher)
            ->getQuery()
            ->getOneOrNullResult();
    }

     public function isClashingFormat($day, $tableFormatColumn, $teacher)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->andWhere('a.day = :day')
            ->andWhere('a.tableFormatColumn = :tableFormatColumn')
            ->andWhere('a.teacher = :teacher')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('day', $day)
            ->setParameter('tableFormatColumn', $tableFormatColumn)
            ->setParameter('teacher', $teacher)
            ->getQuery()
            ->getOneOrNullResult();
    }

     public function subjectOccurancesToday($subject, $class, $day)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->andWhere('a.subject = :subject')
            ->andWhere('a.class = :class')
            ->andWhere('a.day = :day')
            ->orderBy('a.id', 'DESC')
            ->setParameter('subject', $subject)
            ->setParameter('class', $class)
            ->setParameter('day', $day)
            ->getQuery()
            ->getResult();
    }


}
