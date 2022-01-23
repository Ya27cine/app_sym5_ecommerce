<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="product_category")
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

        $ur = $urlGeneratorInterface->generate("product_category", ['slug'=> 'abd']);
        dd($ur);

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

     if( $form->isSubmitted() ){
        //$product = $form->getData();

        $product->setSlug( 
            strtolower( $slugger->slug( $product->getName() ) )
        );

        $em->persist( $product );
        $em->flush();

        dump( $product );
     }

      return  $this->render('product/create.html.twig', [
          'form' => $form->createView()
      ]);
    }



    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     */
    public function edit($id,Request $request, ProductRepository $productRepository, EntityManagerInterface $em, SluggerInterface $slugger){
        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);


        if( $form->isSubmitted() ){

            $product->setSlug( 
                strtolower( $slugger->slug( $product->getName() ) )
            );

            $em->flush();
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
