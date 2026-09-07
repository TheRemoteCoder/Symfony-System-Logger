<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use InvalidArgumentException;
use Doctrine\ORM\ORMException;
use RuntimeException;

/**
 * @package App\Repository
 */
class SystemLogsRepository extends EntityRepository
{
    /**
     * @return mixed
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws NonUniqueResultException
     */
    public function getLast()
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }

    /**
     * @param string $hashsum
     * @return mixed
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws ORMException
     */
    public function findByHashsum(string $hashsum)
    {
        return $this->createQueryBuilder('l')
            ->where('l.hashsum = :hashsum')
            ->setParameter('hashsum', $hashsum)
            ->getQuery()
            ->getResult();
    }
}
