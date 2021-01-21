<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Car;
use App\Entity\City;
use App\Entity\Brand;
use App\Entity\Category;
use Doctrine\DBAL\Connection;
use App\DataFixtures\CustomProvider;
use App\Entity\Images;
use Nelmio\Alice\Loader\NativeLoader;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Provider\ms_MY\Miscellaneous;
use Generator;

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
        
        $faker->addProvider(new Faker\Provider\ms_MY\Miscellaneous($faker));
        // Recupère la liste des catégories du Provider
        $categoriesList = $faker->carCategories();
        $categories = [];
        foreach($categoriesList as $category) {
            $newCategory = new Category();
            $newCategory->setName($category);
            $categories[] = $newCategory;
            $em->persist($newCategory);
        }

        // Recupère la liste des marques du Provider
        $carBrandNamesList = $faker->carBrandNames();
        $brands = [];
        // dd($categoriesList);
        foreach($carBrandNamesList as $brandName) {
            $newBrandName = new Brand();
            $newBrandName->setName($brandName);
            $brands[] = $newBrandName; 
            $em->persist($newBrandName);
        }

        // Recupère la liste des départments du Provider
        $departmentsList = $faker->departments();
        $departments = [];
        foreach($departmentsList as $postalCode => $department) {
            $newCity = new City();
            $newCity->setName($department);
            $newCity->setPostalCode($postalCode);
            $departments[] = $newCity;
            $em->persist($newCity);
        }

        $cars = [];
        // Création de 20 annonces 
        for ($i = 0; $i < 20; $i ++) {
            

            $car = new Car();
            $car->setTitle($faker->date('Y') . ' ' . $faker->carModelName());
            $car->setYear($faker->dateTime());
            $car->setKilometers(mt_rand(10000, 200000));
            $car->setModel($faker->carModelName());
            $car->setLicensePlate($faker->jpjNumberPlate);
            $car->setEngine($faker->carEngine());
            $car->setSeat(mt_rand('1','10'));
            $car->setHorsePower(mt_rand(100, 200));
            $car->setColor($faker->colorName());
            $car->setGearbox($faker->carGearbox());
            $car->setDescription($faker->sentence(15));
            $car->setPrice(mt_rand(30,400));
            $car->setCategory($categories[mt_rand(0, count($categories) - 1)]);
            $car->setBrand($brands[mt_rand(0, count($brands) - 1)]);
            $car->setCity($departments[mt_rand(0, count($departments) - 1)]);
            $cars[] = $car;
            $em->persist($car);
        }
        // On ajoute une image pour chaque voiture
        foreach ($cars as $car) {
            $image = new Images();
            $image->setName("https://source.unsplash.com/300x300/?cars," . $faker->unique()->carBrandName() . "/");
            $image->setCar($car);
            $em->persist($image);
        }
        //enregistre
        $em->flush();
    }
}
