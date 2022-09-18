<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\Automobile;
use App\Entity\Emploi;
use App\Entity\Immobilier;
use App\Entity\Modele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function add(Annonce $annonce): void
    {
        $this->getEntityManager()->persist($annonce);
        $this->getEntityManager()->flush();
    }

    public function remove(Annonce $annonce): void
    {
        $this->getEntityManager()->remove($annonce);
        $this->getEntityManager()->flush();
    }
}
