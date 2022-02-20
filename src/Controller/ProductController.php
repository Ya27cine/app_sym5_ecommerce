<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProductController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="product_category", priority=-1)
     */
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        

        if( ! $category)
            throw $this->createNotFoundException("La categorie demandee n'existe pas");

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     */
    public function show($slug, ProductRepository $productRepository, UrlGeneratorInterface $urlGeneratorInterface){

        $product = $productRepository->findOneBy([
            "slug" => $slug
        ]);

        if( ! $product ){
            throw $this->createNotFoundException("Le Produit demandee, il n'existe pas");
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    } 

    /**
     * @Route("admin/product/create", name="product_create")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em){

        $product = new Product;
      $form = $this->createForm(ProductType::class, $product);


      $form->handleRequest($request);

     if( $form->isSubmitted() && $form->isValid() ){
        //$product = $form->getData();

        $product->setSlug( 
            strtolower( $slugger->slug( $product->getName() ) )
        );

        $em->persist( $product );
        $em->flush();

        return $this->redirectToRoute('product_show',[
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
     }

      return  $this->render('product/create.html.twig', [
          'form' => $form->createView()
      ]);
    }



    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas le droit d'acceder a cette ressource !")
     */
    public function edit($id,Request $request, ProductRepository $productRepository, EntityManagerInterface $em){
       
        //  $user = $this->getUser();

        // if($user === null ) return $this->redirectToRoute('security_login');

        // if($this->isGranted('ROLE_ADMIN') === false){
        //     throw new AccessDeniedHttpException("Vous n'avez pas le droit d'acceder a cette ressource !");
        // }


        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product);

        // $form = $this->createForm(ProductType::class, $product, [
        //     'validation_groups' => ["Default", "with-slug"] // 'default' for all filed not have groupe
        // ]);


        $form->handleRequest($request);


        if( $form->isSubmitted() && $form->isValid()){

            $product->setSlug( 
                strtolower( $slugger->slug( $product->getName() ) )
            );

            $em->flush();

            return $this->redirectToRoute('product_show',[
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
