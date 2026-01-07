<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateField::new('date'),
            TimeField::new('heure'),
            IntegerField::new('nbPersonne'),
            AssociationField::new('user')
                ->formatValue(fn ($value) => $value ? $value->getPrenom() . ' ' . $value->getNom() : ''),
            AssociationField::new('plats'),
            ChoiceField::new('statut')
                ->setChoices([
                    'En attente' => 'en_attente',
                    'Confirmée' => 'confirmée',
                    'Refusée' => 'refusée',
                ])
                ->renderAsBadges([
                    'en_attente' => 'warning',
                    'confirmée' => 'success',
                    'refusée' => 'danger',
                ]),
        ];
    }

    // === SUPPRIMER LE BOUTON "NEW" (Ajouter une réservation) ===
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Supprime uniquement le bouton "New" sur la page liste (INDEX)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
}