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

    protected static $departments = [
      '01' =>	'Ain',
      '02' =>	'Aisne',
      '03' =>	'Allier',
      '04' =>	'Alpes-de-Haute-Provence',
      '05' =>	'Hautes-Alpes',
      '06' =>	'Alpes-Maritimes',
      '07' =>	'Ardèche',
      '08' =>	'Ardennes',
      '09' =>	'Ariège',
      '10' =>	'Aube',
      '11' =>	'Aude',
      '12' =>	'Aveyron',
      '13' =>	'Bouches-du-Rhône',
      '14' =>	'Calvados',
      '15' =>	'Cantal',
      '16' =>	'Charente',
      '17' =>	'Charente-Maritime',
      '18' =>	'Cher',
      '19' =>	'Corrèze',
      '2A' =>	'Corse-du-Sud',
      '2B' =>	'Haute-Corse',
      '21' =>	'Côte-d\'Or',
      '22' =>	'Côtes d\'Armor',
      '23' =>	'Creuse',
      '24' =>	'Dordogne',
      '25' =>	'Doubs',
      '26' =>	'Drôme',
      '27' =>	'Eure',
      '28' =>	'Eure-et-Loir',
      '29' =>	'Finistère',
      '30' =>	'Gard',
      '31' =>	'Haute-Garonne',
      '32' =>	'Gers',
      '33' =>	'Gironde',
      '34' =>	'Hérault',
      '35' =>	'Ille-et-Vilaine',
      '36' =>	'Indre',
      '37' =>	'Indre-et-Loire',
      '38' =>	'Isère',
      '39' =>	'Jura',
      '40' =>	'Landes',
      '41' =>	'Loir-et-Cher',
      '42' =>	'Loire',
      '43' =>	'Haute-Loire',
      '44' =>	'Loire-Atlantique',
      '45' =>	'Loiret',
      '46' =>	'Lot',
      '47' =>	'Lot-et-Garonne',
      '48' =>	'Lozère',
      '49' =>	'Maine-et-Loire',
      '50' =>	'Manche',
      '51' =>	'Marne',
      '52' =>	'Haute-Marne',
      '53' =>	'Mayenne',
      '54' =>	'Meurthe-et-Moselle',
      '55' =>	'Meuse',
      '56' =>	'Morbihan',
      '57' =>	'Moselle',
      '58' =>	'Nièvre',
      '59' =>	'Nord',
      '60' =>	'Oise',
      '61' =>	'Orne',
      '62' =>	'Pas-de-Calais',
      '63' =>	'Puy-de-Dôme',
      '64' =>	'Pyrénées-Atlantiques',
      '65' =>	'Hautes-Pyrénées',
      '66' =>	'Pyrénées-Orientales',
      '67' =>	'Bas-Rhin',
      '68' =>	'Haut-Rhin',
      '69' =>	'Rhône',
      '70' =>	'Haute-Saône',
      '71' =>	'Saône-et-Loire',
      '72' =>	'Sarthe',
      '73' =>	'Savoie',
      '74' =>	'Haute-Savoie',
      '75' =>	'Paris',
      '76' =>	'Seine-Maritime',
      '77' =>	'Seine-et-Marne',
      '78' =>	'Yvelines',
      '79' =>	'Deux-Sèvres',
      '80' =>	'Somme',
      '81' =>	'Tarn',
      '82' =>	'Tarn-et-Garonne',
      '83' =>	'Var',
      '84' =>	'Vaucluse',
      '85' =>	'Vandée',
      '86' =>	'Vienne',
      '87' =>	'Haute-Vienne',
      '88' =>	'Vosges',
      '89' =>	'Yonne',
      '90' =>	'Territoire de Belfort',
      '91' =>	'Essonne',
      '92' =>	'Hauts-de-Seine',
      '93' =>	'Seine-St-Denis',
      '94' =>	'Val-de-Marne',
      '95' =>	'Val-D\'Oise',
      '971' =>	'Guadeloupe',
      '972' =>	'Martinique',
      '973' =>	'Guyane',
      '974' =>	'La Réunion',
      '976' =>	'Mayotte',
    ];



    public static function carCategories(){
        return static::$categories;
    }

    public static function carBrandNames(){
        return static::$carBrandNames;
    }

    public static function departments(){
      return static::$departments;
  }
}