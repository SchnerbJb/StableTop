<?php

namespace App\Controller;

use App\Entity\JDS;
use App\Entity\Categorie;
use App\Entity\Mecanique;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MecaniqueRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class JDSController extends AbstractController
{
    #[Route('/api/jds', name: 'api_creation_jds', methods: ['POST'])]
    public function create(
            Request $request,
            EntityManagerInterface $entityManager,
            MecaniqueRepository $mecaniqueRepository,
            CategorieRepository $categorieRepository
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $jds = new JDS();

        $jds->setNom($data['nom']);
        $jds->setEditeur($data['editeur']);
        $jds->setageMin($data['ageMin']);
        $jds->setNbJoueurMin($data['nbJoueurMin']);
        $jds->setNbJoueurMax($data['nbJoueurMax']);
        $jds->setSolo($data['solo']);
        $jds->setCoopératif($data['coop']);
        foreach ($data['mecaniques'] as $mecaniqueId) {
            $mecanique = $mecaniqueRepository->find($mecaniqueId);

        if (!$mecanique) {
            return $this->json([
                'error' => 'Mécanique introuvable : ' . $mecaniqueId
                ], 404);
             }

        $jds->addMecanique($mecanique);
        }        
        $categorie = $categorieRepository->find($data['categorie']);
        if (!$categorie) {
            return $this->json([
        'error' => 'Catégorie introuvable'
        ], 404);
        }
        $jds->setCategorie($categorie);
        $jds->setDurée($data['durée']);

        $entityManager->persist($jds);
        $entityManager->flush();

    
    
        return $this->json([
            'message' => 'Jeu ajouté',
            'id' => $jds->getId(),
        ]);
    
    }
    #[Route('/api/jds/{id}', name: 'api_recup_jds', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $jds = $entityManager->getRepository(JDS::class)->find($id);

        $mecaniques=[];
            foreach($jds->getMecanique() as $mecanique){
                $mecaniques[] = $mecanique->getNom();
            }

        if (!$jds) {
            return $this->json([
                'message' => 'Jeu non trouvé'
            ], 404);
        }

        return $this->json([
            'id' => $jds->getId(),
            'nom' => $jds->getNom(),
            'editeur' => $jds->getEditeur(),
            'ageMin' => $jds->getAgeMin(),
            'nbJoueursMin' => $jds->getNbJoueurMin(),
            'nbJoueursMax' => $jds->getNbJoueurMax(),
            'solo' => $jds->isSolo(),
            'coop' => $jds->isCoopératif(),
            'mecanique' => $mecaniques,
            'categorie' => $jds->getCategorie()->getNom(),
            'durée' => $jds->getDurée(),
        ]);
    }
    #[Route('api/jds', name: 'api_jds_recup_all', methods: ['GET'])]
    public function show_all(EntityManagerInterface $entityManager): JsonResponse
    {
    $jds = $entityManager
           ->getRepository(JDS::class)
           ->findAll();

    $liste_jds=[];

    foreach($jds as $jeu) {

        $mecaniques=[];

        foreach($jeu->getMecanique() as $mecanique){
                $mecaniques[] = $mecanique->getNom();
            }

        $liste_jds[] =[
            'id' => $jeu->getId(),
            'nom' => $jeu->getNom(),
            'editeur' => $jeu->getEditeur(),
            'ageMin' => $jeu->getAgeMin(),
            'nbJoueursMin' => $jeu->getNbJoueurMin(),
            'nbJoueursMax' => $jeu->getNbJoueurMax(),
            'solo' => $jeu->isSolo(),
            'coop' => $jeu->isCoopératif(),
            'mecanique' => $mecaniques,
            'categorie' => $jeu->getCategorie()->getNom(),
            'durée' => $jeu->getDurée(),
        ];
    }

    return $this->json($liste_jds);
    }

    #[Route('/api/jds/{id}', name: 'api_modif_jds', methods: ['PATCH'])]
    public function modify(
    int $id,
    EntityManagerInterface $entityManager,
    Request $request,
    MecaniqueRepository $mecaniqueRepository,
    CategorieRepository $categorieRepository
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $jds = $entityManager->getRepository(JDS::class)->find($id);

        if (!$jds) {
            return $this->json([
                'message' => 'jeu non trouvé',
            ], 404);
        }
            if (isset($data['nom'])){
                $jds->setNom($data['nom']);
            }
            if (isset($data['editeur'])){
                $jds->setEditeur($data['editeur']);
            }
            if (isset($data['ageMin'])){
                $jds->setAgeMin($data['ageMin']);
            }
            if (isset($data['nbJoueurMin'])){
                $jds->setNbJoueurMin($data['nbJoueurMin']);
            }
            if (isset($data['nbJoueurMax'])){
                $jds->setNbJoueurMax($data['nbJoueurMax']);
            }
            if (isset($data['solo'])){
                $jds->setSolo($data['solo']);
            }
            if (isset($data['coop'])){
                $jds->setCoopératif($data['coop']);
            }
            if (isset($data['durée'])){
                $jds->setDurée($data['durée']);
            }
            if (array_key_exists('categorie', $data)) {

                $categorie = $entityManager
                ->getRepository(Categorie::class)
                ->find($data['categorie']);

                if(!$categorie){
                    return $this->json([
                        'message' => 'categorie inexistante'
                    ], 404);
                }

                $jds->setCategorie($categorie);
            }
            if (array_key_exists('mecaniques', $data)) {

                $jds->getMecanique()->clear(); 

                foreach ($data['mecaniques'] as $mecaniqueId) {
                $mecanique = $mecaniqueRepository->find($mecaniqueId);

                if (!$mecanique) {
                return $this->json([
                    'error' => 'Mécanique introuvable : ' . $mecaniqueId
                    ], 404);
                }
                $jds->addMecanique($mecanique);
            }
        }
            $entityManager->flush();
        return $this->json([
            'message' => 'Jeu modifié avec succès'
        ]);
    }

}
