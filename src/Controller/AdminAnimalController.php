<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\Animal1Type;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminAnimalController extends AbstractController
{
    #[Route('/', name: 'app_admin_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository): Response
    {
        return $this->render('admin_animal/index.html.twig', [
            'animals' => $animalRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_animal_show', methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        return $this->render('admin_animal/show.html.twig', [
            'animal' => $animal,
        ]);
    }

    #[Route('/animal/new', name: 'app_admin_animal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $animal = new Animal();
    $form = $this->createForm(Animal1Type::class, $animal);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer la photo téléchargée depuis le formulaire
        $photoFile = $form->get('photos')->getData();

        if ($photoFile) {
            // Générer un nouveau nom unique pour la photo
            $newFilename = uniqid() . '.' . $photoFile->guessExtension();

            // Déplacer la photo dans le répertoire uploads/images
            $photoFile->move(
                $this->getParameter('photos_directory'),
                $newFilename
            );

            // Associer le nom du fichier à l'entité Animal
            $animal->setPhotos([$newFilename]); // Si c'est une seule image
        }

        // Sauvegarder l'animal dans la base de données
        $entityManager->persist($animal);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_animal_index');
    }

    return $this->render('admin_animal/new.html.twig', [
        'animal' => $animal,
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}/edit', name: 'app_admin_animal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(Animal1Type::class, $animal);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer la nouvelle photo téléchargée
        $newPhotoFile = $form->get('photos')->getData();

        if ($newPhotoFile) {
            // Générer un nouveau nom unique pour la nouvelle photo
            $newFilename = uniqid() . '.' . $newPhotoFile->guessExtension();

            // Déplacer la nouvelle photo dans le répertoire désigné
            $newPhotoFile->move(
                $this->getParameter('photos_directory'),
                $newFilename
            );

            // Remplacer le nom de l'ancienne photo par le nouveau dans l'entité (sans la supprimer du répertoire)
            $animal->setPhotos([$newFilename]);  // Remplacez ou ajoutez selon votre logique
        }

        $entityManager->persist($animal);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_animal_index');
    }

    return $this->render('admin_animal/edit.html.twig', [
        'animal' => $animal,
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_admin_animal_delete', methods: ['POST'])]
    public function delete(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($animal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
    }
}
