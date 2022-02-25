<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class,[
                'label' => 'le nom complete',
                'attr' =>[
                    'placeholder' => 'le nom complete de la livrison'
                ]
            ])
            ->add('addresse', TextareaType::class,[
                'label' => 'l addresse complete',
                'attr' =>[
                    'placeholder' => 'l addresse  complete de la livrison'
                ]
            ])
            ->add('codepostal', TextType::class,[
                'label' => 'le code postale complete',
                'attr' =>[
                    'placeholder' => 'le code postale complete de la livrison'
                ]
            ])
            ->add('city', TextType::class,[
                'label' => 'la ville ',
                'attr' =>[
                    'placeholder' => 'la ville complete de la livrison'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
