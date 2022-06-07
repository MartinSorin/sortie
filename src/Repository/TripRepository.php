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

    /**
     * @return Trip[] Returns an array of Trip objects
     */
    public function sorted(StateRepository $stateRepository, mixed $trips): array
    {
        $cloture=$stateRepository->findOneBySomeField('Cloturee');
        $historisee=$stateRepository->findOneBySomeField('Historisee');
        $terminee=$stateRepository->findOneBySomeField('Terminee');
        $encours=$stateRepository->findOneBySomeField('En cours');
        $annulee=$stateRepository->findOneBySomeField('Annulee');
        $ouverte=$stateRepository->findOneBySomeField('Ouverte');

        foreach ($trips as $value) {

            /**
             * @var $value Trip
             */
            $datefin0 = $value->getDateTimeStart();
            $datefin01 = clone $datefin0;
            $datefin = date_modify($datefin01, '+' . $value->getDuration() . ' minutes');
            $datefin1 = clone $datefin;
            $datefinHisto = date_modify($datefin1, '+1 month');
            $dateNow = new \DateTime('now');

            if ($value->getState() == $ouverte) {
                if ($value->getIsRegistered()->count() == $value->getNbInscriptionsMax()) {
                    $value->setState($cloture);
                    $this->add($value, true);
                }
                if ($dateNow > $value->getDateLimitInscription()) {
                    $value->setState($cloture);
                    $this->add($value, true);

                }
                if ($datefin <= $dateNow) {
                    $value->setState($terminee);
                    $this->add($value, true);
                }
                if ($dateNow > $value->getDateTimeStart() && $datefin > $dateNow) {
                    $value->setState($encours);
                    $this->add($value, true);
                }


            }
            if ($value->getState() == $encours) {
                if ($dateNow >= $datefin) {
                    $value->setState($terminee);
                    $this->add($value, true);
                }
            }
            if (($value->getState() == $cloture && $dateNow > $datefinHisto) || ($value->getState() == $annulee && $dateNow > $datefinHisto) || $datefinHisto <= $dateNow) {
                if ($datefinHisto >= $datefin) {
                    $value->setState($historisee);
                    $this->add($value, true);
                }

            }
            if ($value->getState() == $cloture && $value->getDateLimitInscription() < $dateNow) {
                if ($value->getIsRegistered()->count() < $value->getNbInscriptionsMax()) {
                    $value->setState($ouverte);
                    $this->add($value, true);
                }
            }
        }
        return $trips;
    }

}
