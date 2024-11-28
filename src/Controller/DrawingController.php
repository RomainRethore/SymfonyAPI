<?php

namespace App\Controller;

use App\Entity\Drawing;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\DrawingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DrawingController extends AbstractController
{
    #[Route('api/drawings', name: 'app_drawings', methods: ['GET'])]
    public function index(DrawingRepository $drawingRepository, SerializerInterface $serializer): JsonResponse
    {
        $drawings = $drawingRepository->findAll();

        $jsonDrawingsList = $serializer->serialize($drawings, 'json');

        return new JsonResponse($jsonDrawingsList, Response::HTTP_OK, [], true);
    }

    #[Route('api/drawing/{id}', name: 'app_drawing', methods: ['GET'])]
    public function show(DrawingRepository $drawingRepository, SerializerInterface $serializer, int $id): JsonResponse
    {
        $drawing = $drawingRepository->find($id);

        if (!$drawing) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $jsonDrawing = $serializer->serialize($drawing, 'json');

        return new JsonResponse($jsonDrawing, Response::HTTP_OK, [], true);
    }

    #[Route('api/drawing/delete/{id}', name: 'app_delete_drawing', methods: ['DELETE'])]
    public function delete(DrawingRepository $drawingRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $drawing = $drawingRepository->find($id);

        if (!$drawing) {
            return new JsonResponse(['message' => 'Drawing not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($drawing);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Drawing deleted'], Response::HTTP_OK);
    }

    #[Route('api/drawing/create', name: 'app_create_drawing', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $drawing = new Drawing();
        $drawing->setTitle($data['title']);
        $drawing->setAuthor($data['author']);
        $drawing->setImage($data['image']);

        $entityManager->persist($drawing);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($drawing, 'json'), Response::HTTP_CREATED, [], true);
    }

    #[Route('api/drawing/update/{id}', name: 'app_update_drawing', methods: ['PUT'])]
    public function update(DrawingRepository $drawingRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $drawing = $drawingRepository->find($id);

        if (!$drawing) {
            return new JsonResponse(['message' => 'Drawing not found'], Response::HTTP_NOT_FOUND);
        }

        $drawing->setTitle($data['title']);
        $drawing->setAuthor($data['author']);
        $drawing->setImage($data['image']);

        $entityManager->persist($drawing);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($drawing, 'json'), Response::HTTP_OK, [], true);
    }

    #[Route('api/drawing/modify/{id}', name: 'app_modify_drawing', methods: ['PATCH'])]
    public function modify(DrawingRepository $drawingRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $drawing = $drawingRepository->find($id);

        if (!$drawing) {
            return new JsonResponse(['message' => 'Drawing not found'], Response::HTTP_NOT_FOUND);
        }

        if (isset($data['title'])) {
            $drawing->setTitle($data['title']);
        }

        if (isset($data['author'])) {
            $drawing->setAuthor($data['author']);
        }

        if (isset($data['image'])) {
            $drawing->setImage($data['image']);
        }

        $entityManager->persist($drawing);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($drawing, 'json'), Response::HTTP_OK, [], true);
    }
}
