<?php

namespace App\Form;

use App\Entity\Structure;
use App\Entity\Permission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class StructurePermissionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('permission', EntityType::class, [
                'placeholder' => 'Merci de les permissions!',
                'class' => Permission::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.partner', 'pr')
                        ->leftJoin('pr.structure', 's')
                        ->andWhere('s.id = :id' )
                        ->setParameter('id', $options['permission_id']);
                },
                'multiple' => true,
                'expanded' => true,
                'label_attr' => ['class' => 'checkbox-switch']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Structure::class,
            'permission_id' => null
        ]);
    }
}
