<?php

namespace App\Form;

use App\Entity\Exercise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa ćwiczenia',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Typ ćwiczenia',
                'choices' => [
                    'Klatka piersiowa' => 'klatka piersiowa',
                    'Barki' => 'barki',
                    'Bicepsy' => 'bicepsy',
                    'Tricepsy' => 'tricepsy',
                    'Plecy' => 'plecy',
                    'Uda' => 'uda',
                    'Łydki' => 'łydki',
                    'Brzuch' => 'brzuch',
		    'Cardio' => 'cardio',
		    'Rozgrzewka' => 'rozgrzewka',
                ],
            ])
            ->add('youtubeLink', TextType::class, [
                'label' => 'Link do YouTube',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }
}

