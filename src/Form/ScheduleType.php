<?php

namespace App\Form;

use App\Entity\Route;
use App\Entity\Schedule;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class ScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('route_id', EntityType::class, [
                'class' => Route::class,
                'choice_label' => function (Route $route) {
                    return $route->getDepartPort()->getName() . ' - ' . $route->getArrivalPort()->getName();
                },
            ])
        
            ->add('start_date', DateType::class, [
                'data' => new \DateTime('8888-01-01'),
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'style' => 'pointer-events: none;'
                ]
            ])
            ->add('end_date', DateType::class, [
                'data' => new \DateTime('8888-12-31'),
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'style' => 'pointer-events: none;'
                ]
            ])
            ->add('start_hour')
            ->add('start_minute')
            ->add('end_hour')
            ->add('end_minute')
            ->add('vehicle_id', EntityType::class, [
                'class' => Vehicle::class,
                'choice_label' => 'id',
            ]);

        // フォームイベントリスナーを追加
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData(); // フォームのデータを取得

            // start_dateとend_dateの年を8888に設定
            foreach (['start_date', 'end_date'] as $field) {
                if (isset($data[$field])) {
                    $date = new \DateTime($data[$field]);
                    $date->setDate(8888, $date->format('m'), $date->format('d'));
                    $data[$field] = $date->format('Y-m-d');
                }
            }

            $event->setData($data); // 変更したデータをフォームに設定
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Schedule::class,
        ]);
    }
}
