<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $products = [
        [
            'brand' => 'Apple',
            'name' => 'Iphone 7',
            'description' => 'Ecran Retina HD 4,7 pouces avec technologie IPS | Puce A10 avec coprocesseur de mouvement M10',
            'memory' => '32',
            'color' => 'Noir',
            'price' => 379.9,
            'stock' => 0
        ],
        [
            'brand' => 'Apple',
            'name' => 'Iphone SE',
            'description' => 'iPhone SE. La puce A13 Bionic – la plus rapide des puces de smartphone. Le mode Portrait et la vidéo 4K. Un superbe écran Retina HD de 4,7 pouces et Touch ID. ',
            'memory' => '64',
            'color' => 'Noir',
            'price' => 489.90,
            'stock' => 10
        ],
        [
            'brand' => 'Apple',
            'name' => 'Iphone XR',
            'description' => 'L’iPhone XR intègre un écran Liquid Retina de 6,1 pouces, le LCD le plus avancé du marché1. Face ID avancé. La plus puissante et la plus intelligente des puces de smartphone. Et un appareil photo d’avant-garde.',
            'memory' => '64',
            'color' => 'Noir',
            'price' => 679.90,
            'stock' => 2
        ],
        [
            'brand' => 'Apple',
            'name' => 'Iphone 11',
            'description' => 'Tout nouveau double appareil photo avec ultra grand-angle. Mode Nuit et qualité d’image vidéo spectaculaire. Résistance à l’eau et à la poussière. Autonomie d’une journée. Six superbes couleurs. Passez à la puissance 11.',
            'memory' => '128',
            'color' => 'Gold',
            'price' => 849.90,
            'stock' => 25
        ],
        [
            'brand' => 'Apple',
            'name' => 'Iphone 11 Pro Max',
            'description' => 'Tout nouveau triple appareil photo. Autonomie d’une journée. La plus rapide de toutes les puces de smartphone. Et un écran Super Retina XDR de 6,5 pouces, l’écran le plus grand et le plus lumineux jamais vu sur iPhone.',
            'memory' => '512',
            'color' => 'Argent',
            'price' => 1559.90,
            'stock' => 8
        ],
        [
            'brand' => 'Samsung',
            'name' => 'Galaxy S10',
            'description' => 'Le nouveau Samsung Galaxy S10 est doté d’un grand écran Infinity 6,1’’ avec capteur photo et lecteur d’empreinte intégré sous l’écran. Il est en plus équipé d’un triple appareil photo avec grand angle et zoom optique.',
            'memory' => '128',
            'color' => 'Noir',
            'price' => 619.90,
            'stock' => 10
        ],
        [
            'brand' => 'Samsung',
            'name' => 'Galaxy S20',
            'description' => 'Découvrez le Samsung Galaxy S20, le design toujours plus innovant, l’appareil photo 64Mpx avec grand angle et zoom optique performant.',
            'memory' => '128',
            'color' => 'Gris',
            'price' => 759.90,
            'stock' => 15
        ],
        [
            'brand' => 'Samsung',
            'name' => 'Galaxy S20+',
            'description' => 'Découvrez le Samsung Galaxy S20+, le design toujours plus innovant, l’appareil photo 64Mpx avec grand angle et zoom optique performan',
            'memory' => '128',
            'color' => 'Bleu',
            'price' => 859.90,
            'stock' => 3
        ],
        [
            'brand' => 'Samsung',
            'name' => 'Galaxy Note10+',
            'description' => 'Découvrez le Samsung Galaxy Note10+, le dernier né de la gamme Note dans un format encore plus grand pour encore plus de plaisirs',
            'memory' => '128',
            'color' => 'noir cosmos',
            'price' => 899.90,
            'stock' => 6
        ],
        [
            'brand' => 'Samsung',
            'name' => 'Galaxy S20 Ultra 5G',
            'description' => 'Le Samsung Galaxy S20 Ultra 5G, le design toujours plus innovant, l’appareil photo 108Mpx avec grand angle et zoom optique performant.',
            'memory' => '128',
            'color' => 'Blanc',
            'price' => 1259.90,
            'stock' => 1
        ]
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->products as $product) {
            $mobile = new Product();
            $mobile->setBrand($product['brand']);
            $mobile->setName($product['name']);
            $mobile->setDescription($product['description']);
            $mobile->setMemory($product['memory']);
            $mobile->setColor($product['color']);
            $mobile->setPrice($product['price']);
            $mobile->setStock($product['stock']);
            $mobile->setCreatedAt(new \DateTimeImmutable());
            $mobile->setUpdatedAt(new \DateTime());
            $manager->persist($mobile);
        }

        $manager->flush();
    }
}
