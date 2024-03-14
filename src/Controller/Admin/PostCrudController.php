<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
    public function configureFields(string $pageName): iterable
    {
        $datePost = DateTimeField::new('date_post')->setFormat('dd/MM/yyyy HH:mm:ss');

        if (Crud::PAGE_EDIT === $pageName) {
            $datePost->setDisabled(true);
        }

        $contenu = TextEditorField::new('contenu');

        if (Crud::PAGE_INDEX === $pageName) {
            $contenu = TextField::new('contenu')->renderAsHtml();
        }

        return [
            $contenu,
            $datePost,
        ];
    }
}
