<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use Generator;
use App\Entity\Car;
use App\Entity\City;
use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Images;
use App\Entity\Rental;
use App\Entity\Category;
use Doctrine\DBAL\Connection;
use App\DataFixtures\CustomProvider;
use Nelmio\Alice\Loader\NativeLoader;
use Doctrine\Persistence\ObjectManager;
use Faker\Provider\ms_MY\Miscellaneous;
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

        // On ajoute des rental aux voitures
        foreach($cars as $car) {
            $start = new DateTime();
            $end = $faker->dateTimeInInterval('+30 days');
            $diff = $start->diff($end)->format("%a");
            $price = $car->getPrice();
            $diff = intval($diff) + 1;


            $rental = new Rental();
            $rental->setStartingDate($start);
            $rental->setEndingDate($end);
            $rental->setBilling($diff * $price);       
            $rental->setCar($car);
            dump($rental);
            $em->persist($rental);
        }
        
        // User Owner
        $user = new User();
        $user->setEmail('owner@owner.com');
        // php bin/console security:encode-password (admin)
        $user->setPassword('$2y$13$A5TMRRF8MK.HiyF1RABsLunQ/OI/Y6IVJuTGu/xYYoktoU5pTwmZu');
        $user->setStatus(1);
        $user->setLastname('Dupont');
        $user->setFirstname('Charles-xavier');
        $user->setBirthdate(new DateTime('1978-08-05'));
        $user->setAddress('115 rue de la Tourneuve, 75000 Paris');
        $user->setUsername('picsou1234');
        $user->setRole('ROLE_PROPRIO');
        $user->setRoles(['ROLE_LOCATAIRE', 'ROLE_PROPRIO']);
        $user->setImage('https://source.unsplash.com/150x150/?nature,water');
        
                
        // User Locataire
        $user2 = new User();
        $user2->setEmail('tenant@tenant.com');
        //php bin/console security:encode-password (locataire)
        $user2->setPassword('$2y$13$F9oGmWdFbpe9oZVZJjtUSOa2hfPmoDmjlECn2Xh7LShS2lJnz3yWW');
        $user2->setStatus(1);
        $user2->setLastname('Tartenpion');
        $user2->setFirstname('Elisabeth');
        $user2->setBirthdate(new DateTime('1967-06-23'));
        $user2->setAddress('18 avenue des fleurs, 33000 Bordeaux');
        $user2->setUsername('vivelesvoitures123');
        $user2->setRole('ROLE_LOCATAIRE');
        $user2->setRoles(['ROLE_LOCATAIRE']);
        $user2->setImage('https://source.unsplash.com/150x150/?nature,water');
        
        $em->persist($user);
        $em->persist($user2);

        //enregistre
        $em->flush();
    }
}
