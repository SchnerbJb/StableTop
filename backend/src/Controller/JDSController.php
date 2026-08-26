<?php

namespace App\Controller;

use App\Entity\JDS;
use App\Entity\Categorie;
use App\Entity\Mecanique;
use App\Enum\DureeJDS;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MecaniqueRepository;
use App\Repository\CategorieRepository;
use App\Repository\JDSRepository;
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
        $jds->setAgemin($data['ageMin']);
        $jds->setNbJoueurMin($data['nbJoueurMin']);
        $jds->setNbJoueurMax($data['nbJoueurMax']);
        $jds->setSolo($data['solo']);
        $jds->setCooperatif($data['cooperatif']);
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
        $duree = DureeJDS::tryFrom($data['duree']);
        if ($duree === null) {
            return $this->json([
            'error' => 'Durée invalide'
            ], 400);
        }
        $jds->setDuree($duree);
        if (isset($data['image'])) {
            $jds->setImage($data['image']);
        }
        $entityManager->persist($jds);
        $entityManager->flush();

    
    
        return $this->json([
            'message' => 'Jeu ajouté',
            'id' => $jds->getId(),
        ]);
    
    }
    #[Route('/api/jds/{id}', name: 'api_recup_jds', methods: ['GET'],
    requirements: ['id' => '\d+'])]
    public function show(int $id, JDSRepository $repository): JsonResponse
    {
        $jds = $repository->find($id);
        
        if (!$jds) {
            return $this->json([
                'message' => 'Jeu non trouvé'
            ], 404);
        }
            return $this->json($jds, context: ['groups' => ['jds:read']]);

    }
    #[Route('api/jds', name: 'api_jds_recup_all', methods: ['GET'])]
    public function show_all(JDSRepository $repository, Request $request): JsonResponse
    {
    $nom = $request->query->get('nom');
    $editeur = $request->query->get('editeur');
    $ageMin = $request->query->getInt('ageMin') ?: null;
    $nbJoueurMax = $request->query->getInt('nbJoueurMax') ?: null;
    $nbJoueurMin = $request->query->getInt('nbJoueurMin') ?: null;
    $nbJoueur = $request->query->getInt('nbJoueur') ?: null;
    $solo = $request->query->has('solo') ? $request->query->getBoolean('solo') : null;
    $cooperatif = $request->query->has('cooperatif') ? $request->query->getBoolean('cooperatif') : null;
    $categorie = $request->query->getInt('categorie') ?: null;
    $mecanique = $request->query->all('mecanique');
    $duree = null;
    if ($request->query->has('duree')) {
        $duree = DureeJDS::tryFrom($request->query->get('duree'));
        if ($duree === null){
            return $this->json([
            'error' => 'Durée invalide',
            'valeursPossibles' => array_column(DureeJDS::cases(), 'value')
            ], 400);
        }
    }

    

    $jds = $repository->findByFilters(
            $nom,
            $editeur,
            $ageMin,
            $nbJoueurMin,
            $nbJoueurMax,
            $nbJoueur,
            $solo,
            $cooperatif,
            $categorie,
            $mecanique,
            $duree,
    );
    return $this->json($jds, context: ['groups' => ['jds:read']]);
    }


    #[Route('/api/jds/{id}', name: 'api_modif_jds', methods: ['PATCH'],
    requirements: ['id' => '\d+'])]
    public function modify(
    int $id,
    EntityManagerInterface $entityManager,
    Request $request,
    JDSRepository $jdsRepository,
    MecaniqueRepository $mecaniqueRepository,
    CategorieRepository $categorieRepository
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $jds = $jdsRepository->find($id);

        if (!$jds) {
            return $this->json([
                'message' => 'Jeu non trouvé',
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
            if (isset($data['cooperatif'])){
                $jds->setCooperatif($data['cooperatif']);
            }
            if (isset($data['duree'])){
                $duree = DureeJDS::tryFrom($data['duree']);
                if ($duree === null) {
                    return $this->json([
                    'message' => 'Durée invalide'
                    ], 400);
                }
                $jds->setDuree($duree);
            }

            if (array_key_exists('categorie', $data)) {

                $categorie = $categorieRepository
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
        if (isset($data['image'])){
            $jds->setImage($data['image']);
        }                  
        
        $entityManager->flush();
        return $this->json([
            'message' => 'Jeu modifié avec succès'
        ]);
    }
    #[Route('/api/jds/{id}', name: 'api_suppression_jds', methods: ['DELETE'],
    requirements: ['id' => '\d+'])]
    public function delete(
        int $id,
        EntityManagerInterface $entityManager,
        JDSRepository $repository
    ): JsonResponse{
        $jds = $repository->find($id);

        if(!$jds){
            return $this->json([
                'message' => 'Jeu non trouvé'
            ], 404);
        }

        $entityManager->remove($jds);
        $entityManager->flush();

        return $this->json([
            'message' => 'suppression réalisée avec succés',
        ]);
    }

}
