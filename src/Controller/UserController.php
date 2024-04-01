<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/new', name: 'app_user_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        $postData = json_decode($request->getContent(), true);
        $user = (new User())
            ->setEmail($postData['email'])
            ->setName($postData['name'])
            ->setAge($postData['age'])
            ->setSex($postData['sex'])
            ->setBirthday($postData['birthday'])
            ->setPhone($postData['phone']);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse('Success', 200);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager,  SerializerInterface $serializer): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        if (null === $user) {
            return new JsonResponse('User not found', 404);
        }

        $jsonContent = $serializer->serialize($user, 'json');

        return new JsonResponse($jsonContent, 200, [], true);
    }

    #[Route('/{id}', name: 'app_user_edit', methods: ['POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {

        $user = $entityManager->getRepository(User::class)->find($id);
        if (null === $user) {
            return new JsonResponse('User not found', 404);
        }

        $postData = json_decode($request->getContent(), true);

        $user
        ->setEmail($postData['email'])
        ->setName($postData['name'])
        ->setAge($postData['age'])
        ->setSex($postData['sex'])
        ->setBirthday($postData['birthday'])
        ->setPhone($postData['phone']);

        $entityManager->flush();

        $jsonContent = $serializer->serialize($user, 'json');
        return new JsonResponse($jsonContent, 200, [], true);

    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        if (null === $user) {
            return new JsonResponse('User not found', 404);
        }
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse('Success', 200);
    }
}
