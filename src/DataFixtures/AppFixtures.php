<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Produits;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Catégories
                $categories = [
                    'Électronique' => ['Smartphones', 'Laptops', 'Tablettes'],
                    'Vêtements' => ['T-shirts', 'Pantalons', 'Chaussures'],
                    'Alimentation' => ['Fruits', 'Légumes', 'Viandes']
                ];
        
        foreach ($categories as $categoryName => $products) {
                    $category = new Categories();
                    $category->setNom($categoryName);
                    $manager->persist($category);
        
                    foreach ($products as $productName) {
                        $product = new Produits();
                        $product->setNom($productName)
                            ->setDescription("Description de $productName")
                            ->setPrix(mt_rand(10, 1000))
                            ->setCategorie($category)
                            ->setdateCreation(new \DateTimeImmutable());
                        $manager->persist($product);
                    }
                }

        $manager->flush();
    }
}
