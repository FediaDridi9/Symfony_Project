<?php

namespace App\Controller\Admin;

use App\Entity\Plat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class PlatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Plat::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('description'),
            IntegerField::new('prix')
                ->formatValue(fn ($value) => $value !== null ? $value . ' DT' : '0 DT'), // Affiche en DT
            ImageField::new('photo')
                ->setBasePath('uploads/photos')
                ->setUploadDir('public/uploads/photos')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
            AssociationField::new('categorie'),
        ];
    }
}