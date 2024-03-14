<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $em
    ): Response {
        return $this->renderForm(
            $request,
            $userPasswordHasher,
            $em,
            new Utilisateur()
        );
    }

    #[Route('/app/profile', name: 'app_profile')]
    public function profile(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $em,
        UtilisateurRepository $utilisateurRepository
    ): Response {
        $user = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $user = $utilisateurRepository->find($this->getUser());
        return $this->renderForm(
            $request,
            $userPasswordHasher,
            $em,
            $this->getUser()
        );
    }

    private function checkMail($email)
    {
        $domaineMail = explode("@", $email);
        if ($domaineMail[1] == 'my-digital-school.org') {
            return true;
        } else {
            return false;
        }
    }

    private function renderForm(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $em,
        Utilisateur $user
    ): Response {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if ($this->checkMail($user->getEmail())) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('app_home');;
            } else {
                return throw new Exception("Erreur, vous ne faites pas parti de la secte");
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form
        ]);
    }
}
