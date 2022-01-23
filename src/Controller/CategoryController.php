<?php

namespace App\Controller;

use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {

        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() ){

            $category->setSlug(
              strtolower( $slugger->slug( $category->getName() ))
            );

            $em->persist( $category );
            $em->flush();

            dump( $category );
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     */
    public function edit($id,Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em, SluggerInterface $slugger){

        $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request );

        if( $form->isSubmitted( )) {
            $category->setSlug(
                strtolower( $slugger->slug( $category->getName() ))
              );

            $em->flush();
            dump($category);
        }

        return $this->render('category/edit.htm.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }
}
