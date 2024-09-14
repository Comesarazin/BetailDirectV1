<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/')]
final class AnimalController extends AbstractController
{
    #[Route('/', name: 'app_animal_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('animal/index.html.twig');
    }

    #[Route('/api/animals', name: 'api_animals', methods: ['GET'])]
    public function getAnimals(Request $request, AnimalRepository $animalRepository): JsonResponse
    {
        $query = $request->query->get('q');
        $type = $request->query->get('type');
        $race = $request->query->get('race');

        $animals = $animalRepository->findByFilters($query, $type, $race);

        $data = [];
        $basePath = '/uploads/images/';

        foreach ($animals as $animal) {
            $photos = array_map(function($photo) use ($basePath) {
                return $basePath . $photo;
            }, $animal->getPhotos());

            $data[] = [
                'id' => $animal->getId(),
                'name' => $animal->getName(),
                'age' => $animal->getAge(),
                'type' => $animal->getType(),
                'race' => $animal->getRace(),
                'description' => $animal->getDescription(),
                'photos' => $photos,
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/new', name: 'app_animal_new', methods: ['GET', 'POST'])]
        public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
        {
            $animal = new Animal();
            $form = $this->createForm(AnimalType::class, $animal);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $photos = $form->get('photos')->getData();
                foreach ($photos as $photo) {
                    $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
    
                    try {
                        $photo->move(
                            $this->getParameter('photos_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
    
                    $animal->addPhoto($newFilename);
                }
    
                $entityManager->persist($animal);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->render('animal/new.html.twig', [
                'animal' => $animal,
                'form' => $form->createView(),
            ]);
        }

    #[Route('/{id}', name: 'app_animal_show', methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        return $this->render('animal/show.html.twig', [
            'animal' => $animal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_animal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_animal_delete', methods: ['POST'])]
    public function delete(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($animal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'app_animal_search', methods: ['GET'])]
    public function search(Request $request, AnimalRepository $animalRepository): Response
    {
        $query = $request->query->get('q');
        $animals = $animalRepository->findBySearchQuery($query);

        return $this->render('animal/index.html.twig', [
            'animals' => $animals,
        ]);
    }
}
