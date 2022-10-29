<?php

namespace App\Form;

use App\Entity\Partner;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe est obligatoire!'
                    ]),
                ],
            ])
            ->add('nom', TextType::class)
            ->add('adresse', TextType::class)
        ;
        if($options['is_edit']) {
            $builder
            ->add('password', PasswordType::class, [
                'mapped' => false
            ]);
        }
        if($options['is_structure']) {
            $builder
            ->add('partner', EntityType::class, [
                'mapped' => false,
                'placeholder' => 'Choisir un partenaire',
                'class' => Partner::class,
                'data' => $options['partner_data'],
                'multiple' => false,
                'expanded' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajouter un partenaire!'
                    ]),
                ],

            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_structure' => false,
            'is_edit' => false,
            'partner_data' => null
        ]);
    }
}
