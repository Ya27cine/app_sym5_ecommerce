<?php 
namespace App\Doctrine\Listener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSlugListener{

        protected $slugger;
            public function __construct(SluggerInterface $slugger)
            {
                $this->slugger = $slugger;
            }

        public function prePersist(Product $myEntity, LifecycleEventArgs $event){
            //     $myEntity = $event->getObject();
            //    // dd($myEntity);
            //    if( !  $myEntity instanceof Product){
            //         return;
            //    }

               // create Slug
               if( empty($myEntity->getSlug()  )){
                    $n_slug = strtolower( $myEntity->getName() );
                    $myEntity->setSlug( $this->slugger->slug($n_slug) );
               }


        }
}
?>