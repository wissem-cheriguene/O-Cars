<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\City;
use App\Entity\Brand;
use App\Entity\Category;
use Doctrine\DBAL\Connection;
use App\DataFixtures\CustomProvider;
use Nelmio\Alice\Loader\NativeLoader;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private function truncate(Connection $connection)
    {
        // On passen mode SQL ! On cause avec MySQL
        // Désactivation des contraintes FK
        $users = $connection->query('SET foreign_key_checks = 0');
        // On tronque
        $users = $connection->query('TRUNCATE TABLE car');
        $users = $connection->query('TRUNCATE TABLE brand');
        $users = $connection->query('TRUNCATE TABLE category');
        $users = $connection->query('TRUNCATE TABLE city');
        $users = $connection->query('TRUNCATE TABLE comment');
        $users = $connection->query('TRUNCATE TABLE rental');
        $users = $connection->query('TRUNCATE TABLE user');
        // etc.
    }

    // public function load(ObjectManager $em)
    // {
    //     // On va truncate nos tables à la main pour revenir à id=1
    //     $this->truncate($em->getConnection());

    //     $loader = new MyCustomNativeLoader();
        
    //     //importe le fichier de fixtures et récupère les entités générés
    //     $entities = $loader->loadFile(__DIR__.'/fixtures.yml')->getObjects();
        
    //     //empile la liste d'objet à enregistrer en BDD
    //     foreach ($entities as $entity) {
    //         $em->persist($entity);
    //     };
        
    //     //enregistre
    //     $em->flush();
    // }

    public function load(ObjectManager $em)
    {
        // On va truncate nos tables à la main pour revenir à id=1
        $this->truncate($em->getConnection());

        // On récupère une instance de Faker
        $faker = Faker\Factory::create('fr_FR');
        
        // Notre provider
        $faker->addProvider(new CustomProvider());

        // Recupère la liste des catégories du Provider
        $categoriesList = $faker->carCategories();
        // dd($categoriesList);
        foreach($categoriesList as $category) {
            $newCategory = new Category();
            $newCategory->setName($category);
            $em->persist($newCategory);
        }

        // Recupère la liste des marques du Provider
        $carBrandNamesList = $faker->carBrandNames();
        // dd($categoriesList);
        foreach($carBrandNamesList as $brandName) {
            $newBrandName = new Brand();
            $newBrandName->setName($brandName);
            $em->persist($newBrandName);
        }

        // Recupère la liste des départments du Provider
        $departmentsList = $faker->departments();
        // dd($categoriesList);
        foreach($departmentsList as $postalCode => $department) {
            $newCity = new City();
            $newCity->setName($department);
            $newCity->setPostalCode($postalCode);
            $em->persist($newCity);
        }
        
        //enregistre
        $em->flush();
    }
}
