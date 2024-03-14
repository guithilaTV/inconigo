<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentaireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commentaire::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $dateCommentaire = DateTimeField::new('date_commentaire')->setFormat('dd/MM/yyyy HH:mm:ss');

        if (Crud::PAGE_EDIT === $pageName) {
            $dateCommentaire->setDisabled(true);
        }

        $contenu = TextEditorField::new('contenu');

        if (Crud::PAGE_INDEX === $pageName) {
            $contenu = TextField::new('contenu')->renderAsHtml();
        }

        return [
            $contenu,
            $dateCommentaire,
            AssociationField::new('post')
        ];
    }
}
