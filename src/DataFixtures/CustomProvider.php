<?php

namespace App\DataFixtures;

class CustomProvider{

  // https://www.carissime.com/automobile_categorie.htm
    protected static $categories = [
        'CITADINE & POLYVALENTE',
        'COMPACTE & FAMILIALE',
        'ROUTIERES',
        'CROSSOVER, SUV, 4X4',
        'BREAK',
        'MONOSPACE',
        'LUDOSPACE',
        'COUPÉ',
        'CABRIOLET',
        'SPORTIVE',
        'SUPERCAR',
        'HYBRIDE',
        'ELECTRIQUE',
        'HYDROGÈNE',
        'PROTOTYPE & CONCEPT CAR',
        'ANCIENNE'
    ];

    /**
     * https://github.com/ppelgrims/FakerCar/blob/master/src/Provider/Car.php
     *  @link https://www.globalcarsbrands.com/all-car-brands-list-and-logos
     */
    protected static $carBrandNames = array(
      'Acura', 'Alfa Romeo', 'Aston Martin', 'Audi', 'Bentley', 'BMW',
      'Bugatti', 'Buick', 'Cadillac', 'Chevrolet', 'Chrysler', 'Citroen',
      'Dodge', 'Ferrari', 'Fiat', 'Ford', 'Geely', 'General Motors', 'GMC',
      'Honda', 'Hyundai', 'Infiniti', 'Jaguar', 'Jeep', 'Kia', 'Koenigsegg',
      'Lamborghini', 'Land Rover', 'Lexus', 'Maserati', 'Mazda', 'McLaren',
      'Mercedes-Benz', 'Mini', 'Mitsubishi', 'Nissan', 'Pagani', 'Peugeot',
      'Porsche', 'Ram', 'Renault', 'Rolls Royce', 'Saab', 'Subaru', 'Suzuki',
      'Tata Motors', 'Tesla', 'Toyota', 'Volkswagen', 'Volvo'
  );

    public static function carCategories(){
        return static::$categories;
    }

    public static function carBrandNames(){
        return static::$carBrandNames;
    }
}