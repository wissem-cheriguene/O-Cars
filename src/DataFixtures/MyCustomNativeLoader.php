<?php

//src/DataFixtures/MyCustomNativeLoader.php

namespace App\DataFixtures;

use App\DataFixtures\Faker\CustomProvider;
use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;
use Faker\Factory as FakerGeneratorFactory;
use Faker\Generator as FakerGenerator;

//ajout du provider custom
use App\DataFixtures\Faker\MovieAndGenreProvider;

class MyCustomNativeLoader extends NativeLoader
{
    protected function createFakerGenerator(): FakerGenerator
    {
        $generator = FakerGeneratorFactory::create(parent::LOCALE);
        $generator->addProvider(new AliceProvider());

        //ajout du nouveau provider en passant le generator dans le constructeur de notre classe (heritée du parent base)
        $generator->addProvider(new CustomProvider($generator));
        $generator->seed($this->getSeed());

        return $generator;
    }
}