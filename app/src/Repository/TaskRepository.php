<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getTaskHashes(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.hash')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function getLastWeek()
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t.week')
            ->orderBy('t.week', 'desc')
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getSingleColumnResult()[0] ?? 0;
    }

}
