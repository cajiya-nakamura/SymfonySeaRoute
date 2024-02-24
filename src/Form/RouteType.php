<?php

namespace App\Form;

use App\Entity\Port;
use App\Entity\Route;
use App\Entity\RouteCategory;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('depart_port', EntityType::class, [
                'class' => Port::class,
                'choice_label' => 'name',
            ])
            ->add('arrival_port', EntityType::class, [
                'class' => Port::class,
                'choice_label' => 'name',
            ])
            ->add('category_id', EntityType::class, [
                'class' => RouteCategory::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Route::class,
        ]);
    }
}
