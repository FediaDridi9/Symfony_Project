<?php

namespace App\Form;

use App\Entity\Plat;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // === DATE : pas de réservation dans le passé ===
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de réservation',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => (new \DateTime('today'))->format('Y-m-d'), // Date minimale = aujourd’hui
                ],
                'data' => new \DateTime('today'), // Date par défaut = aujourd’hui
            ])

            // === HEURE : uniquement de 12:00 à 23:00, par 15 minutes ===
            ->add('heure', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de réservation',
                'html5' => true,
                'input' => 'datetime',
                'attr' => [
                    'class' => 'form-control',
                    'min' => '09:00',     // Ouverture à midi
                    'max' => '20:00',     // Fermeture à 20h
                    'step' => '900',      // 900 secondes = 15 minutes
                ],
            ])

            // === NOMBRE DE PERSONNES ===
            ->add('nbPersonne', IntegerType::class, [
                'label' => 'Nombre de personnes',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 10,
                    'placeholder' => 'Ex: 4',
                ],
            ])

            // === CHOIX DES PLATS ===
            ->add('plats', EntityType::class, [
                'class' => Plat::class,
                'choice_label' => 'nom',
                'label' => 'Choisir les plats',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-select',
                    'size' => 8, // Affiche 8 lignes visibles
                ],
                'placeholder' => '— Sélectionnez un ou plusieurs plats —',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}