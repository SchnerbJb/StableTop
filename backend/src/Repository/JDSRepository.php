<?php

namespace App\Repository;

use App\Entity\JDS;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Enum\DureeJDS;


/**
 * @extends ServiceEntityRepository<JDS>
 */
class JDSRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JDS::class);
    }

    public function findByFilters(
        ?string $nom = null,
        ?string $editeur = null,
        ?int $ageMin = null,
        ?int $nbJoueurMin = null,
        ?int $nbJoueurMax = null,
        ?int $nbJoueur = null,
        ?bool $solo = null,
        ?bool $cooperatif = null,
        ?int $categorie = null,
        ?array $mecaniques = [],
        ?DureeJDS $duree = null
    ): array {
        $qb = $this->createQueryBuilder('jeu');

        if ($nom !== null) {
            $qb->andWhere('jeu.nom LIKE :nom')
            ->setParameter('nom', '%' . $nom . '%');
        }
        if ($editeur !== null) {
            $qb->andWhere('jeu.editeur LIKE :editeur')
            ->setParameter('editeur', '%' . $editeur . '%');
        }
        if ($ageMin !== null) {
            $qb->andWhere('jeu.ageMin <= :ageMin')
            ->setParameter('ageMin', $ageMin);
        }
        if ($nbJoueurMax !== null) {
            $qb->andWhere('jeu.nbJoueurMax <= :nbJoueurMax')
            ->setParameter('nbJoueurMax', $nbJoueurMax);
        }
        if ($nbJoueurMin !== null) {
            $qb->andWhere('jeu.nbJoueurMin >= :nbJoueurMin')
            ->setParameter('nbJoueurMin', $nbJoueurMin);
        }
        if ($nbJoueur !== null) {
            $qb->andWhere('jeu.nbJoueurMin <= :nbJoueur')
            ->andWhere('jeu.nbJoueurMax >= :nbJoueur')
            ->setParameter('nbJoueur', $nbJoueur);
        }
        if ($solo !== null) {
            $qb->andWhere('jeu.solo = :solo')
            ->setParameter('solo', $solo);
        }
        if ($cooperatif !== null) {
            $qb->andWhere('jeu.cooperatif = :cooperatif')
            ->setParameter('cooperatif', $cooperatif);
        }
        if ($categorie !== null) {
            $qb->join('jeu.categorie', 'categorie')
            ->andWhere('categorie.id = :categorie')
            ->setParameter('categorie', $categorie);
        }
        if (!empty($mecanique)) {
            $qb->join('jeu.mecanique', 'mecanique')
            ->andWhere('mecanique.id IN (:mecanique)')
            ->setParameter('mecanique', $mecanique);
        }
        if ($duree !== null) {
            $qb->andWhere('jeu.duree = :duree')
            ->setParameter('duree', $duree);
        }
        return $qb->getQuery()->getResult();                                                           
    }

//    /**
//     * @return JDS[] Returns an array of JDS objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?JDS
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
