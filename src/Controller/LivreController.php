<?php
namespace App\Controller;
use App\Entity\Livre;
use App\Form\LivreForm;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/livre')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'app_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_livre_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface
        $entityManager
    ): Response {
        $livre = new Livre();
        $form = $this->createForm(LivreForm::class, $livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livre);
            $entityManager->flush();
            $this->addFlash('success', 'Livre ajouté avec succès !');
            return $this->redirectToRoute('app_livre_index');
        }
        return $this->render('livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);

    }
    #[Route('/{id}', name: 'app_livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_livre_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Livre $livre,
        EntityManagerInterface
        $entityManager
    ): Response {
        $form = $this->createForm(LivreForm::class, $livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Livre modifié avec succès !');
            return $this->redirectToRoute('app_livre_index');
        }
        return $this->render('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/delete', name: 'app_livre_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Livre $livre,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $livre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
            $this->addFlash('success', 'Livre supprimé avec succès !');
        }
        return $this->redirectToRoute('app_livre_index');
    }
}