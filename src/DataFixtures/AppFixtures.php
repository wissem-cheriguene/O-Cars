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


    public function load(ObjectManager $em)
    {
        // On va truncate nos tables à la main pour revenir à id=1
        $this->truncate($em->getConnection());

        // On récupère une instance de Faker
        $faker = Faker\Factory::create('fr_FR');
        
        // Nos providers
        $faker->addProvider(new CustomProvider());
        // LoremFlickr provider (image)
        $faker->addProvider(new \Xvladqt\Faker\LoremFlickrProvider($faker));

        $faker->addProvider(new Faker\Provider\ms_MY\Miscellaneous($faker));

        // $proprio = [];
        // $locataire = [];

        // for($i=0; $i< 5;$i++) {
        //     // User Propriétaire
        //     $user = new User();
        //     $user->setEmail('owner'.$i.'@owner.com');
        //     // php bin/console security:encode-password (admin)
        //     $user->setPassword('$2y$13$A5TMRRF8MK.HiyF1RABsLunQ/OI/Y6IVJuTGu/xYYoktoU5pTwmZu');
        //     $user->setStatus(1);
        //     $user->setLastname('Dupont'.$i);
        //     $user->setFirstname('Charles-xavier'.$i);
        //     $user->setBirthdate(new DateTime('1978-08-05'));
        //     $user->setAddress('115 rue de la Tourneuve, 75000 Paris');
        //     $user->setUsername('picsou1234'.$i);
        //     $user->setRole('ROLE_PROPRIO');
        //     $user->setRoles(['ROLE_PROPRIO']);
        //     $user->setImage('https://source.unsplash.com/150x150/?nature,water');
        //     $proprio[] = $user;
        //     $em->persist($user);
        // }

        // for ($i=0; $i< 5;$i++) {
        //     // User Locataire
        //     $user2 = new User();
        //     $user2->setEmail('tenant'.$i.'@tenant.com');
        //     //php bin/console security:encode-password (locataire)
        //     $user2->setPassword('$2y$13$F9oGmWdFbpe9oZVZJjtUSOa2hfPmoDmjlECn2Xh7LShS2lJnz3yWW');
        //     $user2->setStatus(1);
        //     $user2->setLastname('Tartenpion'.$i);
        //     $user2->setFirstname('Elisabeth'.$i);
        //     $user2->setBirthdate(new DateTime('1967-06-23'));
        //     $user2->setAddress('18 avenue des fleurs, 33000 Bordeaux');
        //     $user2->setUsername('vivelesvoitures123'.$i);
        //     $user2->setRole('ROLE_LOCATAIRE');
        //     $user2->setRoles(['ROLE_LOCATAIRE']);
        //     $user2->setImage('https://source.unsplash.com/150x150/?nature,water');
        //     $locataire[] = $user2;
        //     $em->persist($user2);
        // }

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

        // $cars = [];
        // // Création de 50 annonces 
        // for ($i = 0; $i < 50; $i ++) {
            
        //     $model = $faker->carModelName();
        //     $brand = $brands[mt_rand(0, count($brands) - 1)];
            
        //     $car = new Car();
        //     $car->setTitle($faker->date('Y') . ' ' . $model);
        //     $car->setYear($faker->dateTime());
        //     $car->setKilometers(mt_rand(10000, 200000));
        //     $car->setModel($model);
        //     $car->setLicensePlate($faker->jpjNumberPlate);
        //     $car->setEngine($faker->carEngine());
        //     $car->setSeat(mt_rand('1','10'));
        //     $car->setHorsePower(mt_rand(100, 200));
        //     $car->setColor($faker->colorName());
        //     $car->setGearbox($faker->carGearbox());
        //     $car->setDescription($faker->sentence(15));
        //     $car->setPrice(mt_rand(30,400));
        //     $car->setCategory($categories[mt_rand(0, count($categories) - 1)]);
        //     $car->setBrand($brand);
        //     $car->setCity($departments[mt_rand(0, count($departments) - 1)]);
        //     $car->setUser($proprio[array_rand($proprio)]);
        //     $cars[] = $car;
        //     $em->persist($car);
        // }
        // // On ajoute une image pour chaque voiture
        // foreach ($cars as $car) {
        //     for($i=0; $i <3;$i++) {
        //         $image = new Images();
        //         $image->setName($faker->unique()->imageUrl($width = 300, $height = 300, ['cars']));
        //         $image->setCar($car);
        //         $em->persist($image);
        //     }
        // }

        // On ajoute des rental aux voitures
        // foreach($cars as $car) {
        //     $start = $faker->dateTimeBetween('now', '+15 days');
        //     $end = $faker->dateTimeBetween('+30 days', '+90 days');
        //     $diff = $start->diff($end)->format("%a");
        //     $price = $car->getPrice();
        //     $diff = intval($diff) + 1;


        //     $rental = new Rental();
        //     $rental->setStartingDate($start);
        //     $rental->setEndingDate($end);
        //     $rental->setBilling($diff * $price);       
        //     $rental->setCar($car);
        //     $rental->setStatus(mt_rand(1,3));
        //     $rental->setUser($locataire[array_rand($locataire)]);
        //     // dump($rental);
        //     $em->persist($rental);
        // }
        

        // User Admin
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        // php bin/console security:encode-password (adminadmin)
        $admin->setPassword('$2y$13$nm8GN8grfnC6lTwYU/h0au/TX1gPD3aOSoLoBPHQAID202vc0e7Iu');
        $admin->setStatus(1);
        $admin->setLastname('Bloups');
        $admin->setFirstname('Mathieu');
        $admin->setBirthdate(new DateTime('1974-06-15'));
        $admin->setAddress('115 rue de la Rivière, 75000 Paris');
        $admin->setUsername('darkvador1234');
        $admin->setRole('ROLE_ADMIN');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setImage('https://source.unsplash.com/150x150/?nature,water');

        $em->persist($admin);

        //enregistre
        $em->flush();
    }
}
