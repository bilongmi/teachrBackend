<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/produits')]
final class ProduitsController extends AbstractController
{
    #[Route(name: 'app_produits_index', methods: ['GET'])]
    public function index(ProduitsRepository $produitsRepository): Response
    {
        $produits = $produitsRepository->findAll();

        //return $this->json($produits);
        return $this->json($produits, 200, [], [
            'groups' => ['produits:read'],
        ]);
    }

    #[Route(name: 'app_produits_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, CategoriesRepository $categoriesRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $produit = new Produits();
        $produit->setNom($data['nom'] ?? '');
        $produit->setDescription($data['description'] ?? null);
        $produit->setPrix($data['prix'] ?? 0);
        $produit->setDateCreation(new \DateTime());

        if (isset($data['categorie_id'])) {
            $categorie = $categoriesRepository->find($data['categorie_id']);
            if ($categorie) {
                $produit->setCategorie($categorie);
            } else {
                return $this->json(['error' => 'Categorie non trouvée'], 400);
            }
        }

        $em->persist($produit);
        $em->flush();

        //return $this->json($produit, 201);
        return $this->json($produit, 201, [], [
            'groups' => ['produits:read'],
        ]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Produits $produit): Response
    {
        //return $this->json($produit);
        return $this->json($produit, 200, [], [
            'groups' => ['produits:read'],
        ]);
    }

    #[Route('/{id}', name: 'app_produits_update', methods: ['PUT'])]
    public function update(Request $request, Produits $produit, EntityManagerInterface $em, CategoriesRepository $categoriesRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $produit->setNom($data['nom'] ?? $produit->getNom());
        $produit->setDescription($data['description'] ?? $produit->getDescription());
        $produit->setPrix($data['prix'] ?? $produit->getPrix());

        if (isset($data['categorie_id'])) {
            $categorie = $categoriesRepository->find($data['categorie_id']);
            if ($categorie) {
                $produit->setCategorie($categorie);
            } else {
                return $this->json(['error' => 'Categorie non trouvée'], 400);
            }
        }

        $em->flush();

        //return $this->json($produit);
        return $this->json($produit, 200, [], [
            'groups' => ['produits:read'],
        ]);
    }

    #[Route('/{id}', name: 'app_produits_delete', methods: ['DELETE'])]
    public function delete(Produits $produit, EntityManagerInterface $em): Response
    {
        $em->remove($produit);
        $em->flush();

        return $this->json(null, 204);
    }
}
