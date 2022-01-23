<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use App\Entity\Category;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class,[
            'label' => 'Nom du product',
            'attr' => [
               // 'class' => 'form-control',
                'placeholder' => "tapez nom du produit"
            ]
        ])
        ->add('shortDescriotion', TextType::class,[
          'label' => 'desc du product',
          'attr' => [
             // 'class' => 'form-control',
              'placeholder' => "tapez une description"
          ]
      ])
        ->add('price', MoneyType::class,[
          'label' => 'Prix du product',
          'attr' => [
             // 'class' => 'form-control',
              'placeholder' => "le Prix du Produit"
          ]
      ])
      ->add('mainPicture', UrlType::class,[
          'label' => 'Image de Produit',
          'attr'=> [
              'placeholder' => 'Tapez une URL d\'image !'
          ]
      ])
     
      ->add('category', EntityType::class,[
          'label' => 'Nom du product',
            // 'attr' => [
            //     'class' => 'form-control',
            // ],
            'placeholder' => '-- choisir une category--',
            'class' => Category::class,
            'choice_label'=> function(Category $category){
                return strtoupper( $category->getName() );
            }
        ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
