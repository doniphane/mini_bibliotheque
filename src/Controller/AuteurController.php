<?php
namespace App\Controller;
use App\Entity\Auteur;
use App\Form\AuteurForm;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/auteur')]
class AuteurController extends AbstractController
{
    #[Route('/', name: 'app_auteur_index', methods: ['GET'])]
    public function index(AuteurRepository $auteurRepository): Response
    {
        return $this->render('auteur/index.html.twig', [
            'auteurs' => $auteurRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_auteur_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface
        $entityManager
    ): Response {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurForm::class, $auteur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auteur);
            $entityManager->flush();
            $this->addFlash('success', 'Auteur ajouté avec succès !');
            return $this->redirectToRoute('app_auteur_index');
        }
        return $this->render('auteur/new.html.twig', [
            'auteur' => $auteur,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_auteur_show', methods: ['GET'])]
    public function show(Auteur $auteur): Response
    {
        return $this->render('auteur/show.html.twig', [
            'auteur' => $auteur,
        ]);
    }
    #[Route('/{id}/delete', name: 'app_auteur_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Auteur $auteur,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $auteur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($auteur);
            $entityManager->flush();
            $this->addFlash('success', 'Auteur supprimé avec succès !');
        }
        return $this->redirectToRoute('app_auteur_index');
    }
}