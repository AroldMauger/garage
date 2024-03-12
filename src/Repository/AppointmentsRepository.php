<?php

namespace App\Repository;

use App\Entity\Appointments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use \DateTimeInterface;
/**
 * @extends ServiceEntityRepository<Appointments>
 *
 * @method Appointments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointments[]    findAll()
 * @method Appointments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointments::class);
    }

    public function save(Appointments $appointment): bool
    {
        try {
            $em = $this->getEntityManager();
            $em->persist($appointment);
            $em->flush();

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function delete(Appointments $appointment): bool
    {
        try {
            $em = $this->getEntityManager();
            $em->remove($appointment);
            $em->flush();

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
    public function findByDate(DateTimeInterface $date, string $status)
    {
        $dayBefore = date('Y-m-d', strtotime($date->format('Y-m-d') .' -1 day'));
        $dayAfter = date('Y-m-d', strtotime($date->format('Y-m-d') .' +1 day'));

        return $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.status = :status')
            ->setParameter('status', $status)
            ->andWhere('a.date > :before')
            ->setParameter('before', $dayBefore)
            ->andWhere('a.date < :after')
            ->setParameter('after', $dayAfter)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findFinishedPaginated (int $page, int $limit) {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.status = :status')
            ->setParameter('status', "terminé")
            ->orderBy("a.date", "DESC")
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
