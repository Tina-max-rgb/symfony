<?php

namespace App\Form;

use App\Entity\Partner;
use App\Entity\Permission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PaertnerPermissionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('permission', EntityType::class, [
                'label' => 'Liste des permissions.',
                'class' => Permission::class,
                'multiple' => true,
                'expanded' => true,
                'label_attr' => ['class' => 'checkbox-switch']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
        ]);
    }
}
