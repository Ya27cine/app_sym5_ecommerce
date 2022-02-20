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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
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

        if($form->isSubmitted() && $form->isValid()){

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
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas le droit d'acceder a cette ressource !")
     */
    public function edit($id,Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em, 
    SluggerInterface $slugger, Security $security){

        $category = $categoryRepository->find($id);

        if( ! $category){
            throw new NotFoundHttpException("cette category n existe pas");
        }
    
       // $this->denyAccessUnlessGranted('CAN_EDIT',  $category);


        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request );

        if( $form->isSubmitted() && $form->isValid() ) {
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
