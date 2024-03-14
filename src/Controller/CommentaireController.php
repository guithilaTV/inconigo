<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireFormType;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentaireController extends AbstractController
{
    #[Route('/app/post/commentNew/{postId}', name: 'app_comment_new')]
    public function new(int $postId, PostRepository $postRepository, Request $request, EntityManagerInterface $em): Response
    {
        $post = $postRepository->find($postId);
        $comment = new Commentaire();
        $form = $this->createForm(CommentaireFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setDateCommentaire(new DateTime());
            $comment->setCreateur($this->getUser());
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_post_detail', ['id' => $postId]);
        }

        return $this->render('commentaire/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/app/post/comment/{id}', name: 'app_comment_detail')]
    public function detail(Commentaire $comment): Response
    {
        return $this->render('commentaire/detail.html.twig', [
            'comment' => $comment
        ]);
    }
}
