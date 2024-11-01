<?php

namespace App\Repository;

use App\Entity\AbscenceSheet;
use App\Entity\SchoolYear;
use App\Entity\Quater;
use App\Entity\Sequence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AbscenceSheet>
 *
 * @method AbscenceSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbscenceSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbscenceSheet[]    findAll()
 * @method AbscenceSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbscenceSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbscenceSheet::class);
    }
    public function findAbsByYear(SchoolYear $year )
    {
       $qb = $this->createQueryBuilder('a')
       ->leftJoin('a.sequence', 's')
       ->leftJoin('s.quater', 'q')
       ->leftJoin('q.schoolYear', 'sc')
       ->andWhere('sc.id=:sc')
       ->setParameter('sc', $year->getId());
      
       
       return $qb ->orderBy('a.id', 'DESC')->getQuery()->getResult();
    }

//    /**
//     * @return AbscenceSheet[] Returns an array of AbscenceSheet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

     /**
     * @return AbscenceSheet[] Returns an array of AbscenceSheet objects
     */
    public function findByQuater(Quater $qt): ?array
    {
       return $this->createQueryBuilder('a')
            ->leftJoin('a.sequence', 's')
            ->leftJoin('s.quater', 'q')
            ->where('q.id=:q_id')
            ->orderBy('a.updatedAt')
            ->setParameter('q_id', $qt->getId())
            ->getQuery()
            ->getResult();
        ;
    }
}
