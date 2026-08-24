<?php

namespace App\Controller;

use App\Entity\JDS;
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
}
