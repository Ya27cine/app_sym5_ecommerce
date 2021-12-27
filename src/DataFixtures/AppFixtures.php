<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {

        $faker  = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));



        for ($c=0; $c < 3; $c++) { 
            $category = new Category;

            $category->setName( $faker->department )
            ->setSlug( strtolower( $this->slugger->slug( $category->getName() )) );
        
            $manager->persist($category);

            for ($i=0; $i < rand(17,29); $i++) { 
                $product = new Product();
                $product->setName($faker->productName)
                ->setPrice( $faker->price(3000, 20000) )
                ->setSlug( strtolower( $this->slugger->slug( $product->getName() )))

                ->setCategory( $category );

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
