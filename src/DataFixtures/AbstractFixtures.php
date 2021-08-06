<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

abstract class AbstractFixtures extends Fixture
{
    /**
     * @var Faker\Generator
     */
    protected Faker\Generator $faker;

    /**
     * @param ObjectManager $manager
     */
    protected ObjectManager $manager;

    public function load(ObjectManager $manager)
    {
        $this->faker = Faker\Factory::create('fr_FR');
        $this->manager = $manager;
    }
}
