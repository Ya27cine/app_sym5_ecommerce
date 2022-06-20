<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\User;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use DateTime;
use App\Entity\PurchaseItem;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $encoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder )
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $users = [];
        $products = [];

        $faker  = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));


        $admin =  new User();
        $admin->setEmail("test@prostam.fr")
        ->setFullname("Khelifa Yassine")
        ->setPassword( $this->encoder->encodePassword($admin, "password"))
        ->setRoles([
        ]);

        $manager->persist($admin);

        for ($u=0; $u < 5; $u++) { 
           $user = new User();

           $user->setEmail("user$u@gmail.com")
           ->setFullname( $faker->name())
           ->setPassword( $this->encoder->encodePassword($user, "password"));

           $users[] = $user;

           $manager->persist($user);
        }

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
                ->setMainPicture( $faker->imageUrl(400, 400, true))
                ->setShortDescriotion( $faker->paragraph())

                ->setCategory( $category );

                $products[] = $product;

                $manager->persist($product);
            }
        }


        for ($p=0; $p < mt_rand(20, 40); $p++) { 

            $purchase = new Purchase;
            $purchase->setFullName($faker->name)
                    ->setAddress($faker->streetAddress)
                    ->setPostalCode($faker->postcode)
                    ->setCity($faker->city)
                    ->setClient($faker->randomElement($users))
                    ->setPurchasedAt(  $faker->dateTimeBetween("-17 days") )
                    ->setTotal( mt_rand(2000, 30000));

            for ($e=0; $e <  mt_rand(2, 10); $e++) { 

                $purchaseItem = new PurchaseItem;
                $purchaseItem->setProduct($products[$e])
                ->setProductName($products[$e]->getName())
                ->setProductPrice($products[$e]->getPrice())
                ->setQuantity( mt_rand(1,3) )
                ->setTotal(
                    $purchaseItem->getQuantity() * $purchaseItem->getProductPrice()
                )
                ->setPurchase($purchase);

                $manager->persist($purchaseItem);
            }
           
            if($faker->boolean(70)){
                $purchase->setStatus(Purchase::STATUS_PAID);
            }


            $manager->persist($purchase);

        }

        $manager->flush();
    }
}
