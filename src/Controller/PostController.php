<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\CommentaireRepository;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/app/post', name: 'app_post')]
    public function index(PostRepository $postRepository, Request $request): Response
    {
        $offset = max(0, $request->get('offset', 0));
        $byJaime = ($request->get('byJaime', 0) != 0);
        $paginator = $postRepository->getPaginator($offset, $byJaime);
        return $this->render('post/index.html.twig', [
            'posts' => $paginator,
            'offset' => $offset,
            'byJaime' => $byJaime,
            'previous' => $offset - PostRepository::LIGNE_PAR_PAGE,
            'next' => min(count($paginator), $offset + PostRepository::LIGNE_PAR_PAGE),
        ]);
    }

    #[Route('/app/postDetail/{id}', name: 'app_post_detail')]
    public function detail(Post $post, CommentaireRepository $commentaireRepository): Response
    {
        $comments = $commentaireRepository->findBy(['post' => $post]);
        return $this->render('post/detail.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    #[Route('/app/postNew', name: 'app_post_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setDatePost(new DateTime());
            $post->setCreateur($this->getUser());

            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded

            if ($imageFile) {
                $newFilename = $post->getId() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_dir'),
                        $newFilename
                    );
                    $post->setImageName($newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('app_post');
        }

        return $this->render('post/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/app/postLike/{id}', name: 'app_post_like')]
    public function like(Post $post, EntityManagerInterface $em): Response
    {
        if ($post->getJaime()->contains($this->getUser())) {
            $post->removeJaime($this->getUser());
        } else {
            $post->addJaime($this->getUser());
        }
        $em->persist($post);
        $em->flush();
        return $this->redirectToRoute('app_post');
    }
}
