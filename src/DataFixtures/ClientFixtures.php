<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    private $client = [
        'name' => 'Telefon',
        'address' => '10, rue de la box',
        'postalCode' => 75000,
        'city' => 'Paris',
    ];

    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setName($client['name']);
        $client->setAddress($client['address']);
        $client->setPostalCode($client['postalCode']);
        $client->setCity($client['city']);

        $manager->persist($client);
        $manager->flush();
    }
}
