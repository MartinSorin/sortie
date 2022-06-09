<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Trip;
use App\Form\Model\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 *
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function add(Trip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filter(Filter $filter, $participant, $stateRepository){

        $queryBuilder = $this->createQueryBuilder('t');
        $queryBuilder->join('t.state', 's');
        $queryBuilder->join('t.isRegistered', 'p');
        $queryBuilder->join('t.organiser', 'o');
        $queryBuilder->join('t.place', 'pl');
        $queryBuilder->join('t.siteOrganiser', 'c');
        $queryBuilder->addSelect('s', 'p', 'o', 'pl', 'c');
        if ($filter->getCampus()){
            $queryBuilder->andWhere('t.siteOrganiser = :campus');
            $queryBuilder->setParameter('campus', $filter->getCampus()->getId());
        }

        if ($filter->getSearch()){
            $queryBuilder->andWhere('t.name LIKE :search');
            $queryBuilder->setParameter('search', '%'.$filter->getSearch().'%');

        }

        if ($filter->getStart()){
            $queryBuilder->andWhere('t.dateTimeStart > :start');
            $queryBuilder->setParameter('start', $filter->getStart());
        }

        if ($filter->getEnd()){
            $queryBuilder->andWhere('t.dateTimeStart < :end');
            $queryBuilder->setParameter('end', $filter->getEnd());
        }

        if ($filter->getOrganiser()){
            dump($participant);
            $queryBuilder->andWhere('t.organiser = :organiser');
            $queryBuilder->setParameter('organiser', $participant);
        }

        if ($filter->getRegistered()){
            $queryBuilder->andWhere(':registered MEMBER OF t.isRegistered');
            $queryBuilder->setParameter('registered', $participant);
        }

        if ($filter->getNotRegistered()){
            $queryBuilder->andWhere(':notRegistered NOT MEMBER OF t.isRegistered');
            $queryBuilder->setParameter('notRegistered', $participant);
        }

        if ($filter->getPassed()){
            $state = $stateRepository->findOneBySomeField('Terminee');
            $queryBuilder->andWhere('t.state = :passed');
            $queryBuilder->setParameter('passed', $state->getId());
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }



//    /**
//     * @return Trip[] Returns an array of Trip objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trip
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
