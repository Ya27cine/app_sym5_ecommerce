<?php 

namespace App\Doctrine\Listener;

use App\Entity\Category;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugListener{


    protected $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Category $myEntity){
        // create Slug
        if( empty($myEntity->getSlug()  )){
                $n_slug = strtolower( $myEntity->getName() );
                $myEntity->setSlug( $this->slugger->slug($n_slug));
        }

    }
}



?>