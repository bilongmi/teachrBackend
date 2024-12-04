<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/categories')]
final class CategoriesController extends AbstractController
{
    #[Route(name: 'app_categories_index', methods: ['GET'])]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();

        //return $this->json($categories);
        return $this->json($categories, 200, [], [
            'groups' => ['categories:read'],
        ]);
    }

    #[Route(name: 'app_categories_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        {
            $data = json_decode($request->getContent(), true);

            if (empty($data['nom'])) {
                return $this->json(['error' => 'Le champ "nom" est requis.'], 400);
            }

            $category = new Categories();
            $category->setNom($data['nom']);
            //$category->setNom($request->request->get('nom'));
    
            $errors = $validator->validate($category);
            if (count($errors) > 0) {
                return $this->json(['errors' => $errors], 400);
            }
    
            $em->persist($category);
            $em->flush();
    
           // return $this->json($category, 201);
           return $this->json($category, 201, [], [
            'groups' => ['categories:read'],
        ]);
        }
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Categories $category): Response
    {
        //return $this->json($category);
        return $this->json($category, 200, [], [
            'groups' => ['categories:read'],
        ]);
    }

    #[Route('/{id}', name: 'app_categories_update', methods: ['PUT'])]
    public function update(Request $request, Categories $category, EntityManagerInterface $em, ValidatorInterface $validator ): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['nom'])) {
            return $this->json(['error' => 'Le champ "nom" est requis.'], 400);
        } 

        $category->setNom($data['nom']);
        //$category->setNom($request->request->get('nom'));

        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], 400);
        }

        $em->flush();

        //return $this->json($category);
        return $this->json($category, 200, [], [
            'groups' => ['categories:read'],
        ]);
    }

    #[Route('/{id}', name: 'app_categories_delete', methods: ['DELETE'])]
    public function delete(Request $request, Categories $category, EntityManagerInterface $em): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->getString('_token'))) {
        //     $entityManager->remove($category);
        //     $entityManager->flush();
        // }

        // return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);

        // Supprimer tous les produits liés à cette catégorie
        foreach ($category->getProduits() as $produit) {
        $em->remove($produit);
        }
        $em->remove($category);
        $em->flush();

        return $this->json(null, 204);
    }
}
