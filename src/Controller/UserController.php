<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/user', name: 'api_user', methods: ['POST'])]
class UserController extends AbstractController
{
    #[Route('/new', name: 'api_user_new', methods: ['POST'])]
    public function new(Request $request, UserRepository $userRepository): JsonResponse
    {

        $postData = json_decode($request->getContent(), true);
        $user = (new User())
            ->setEmail($postData['email'])
            ->setName($postData['name'])
            ->setAge($postData['age'])
            ->setSex($postData['sex'])
            ->setBirthday($postData['birthday'])
            ->setPhone($postData['phone']);

        $userRepository->save($user);

        return new JsonResponse('Success', 200);
    }

    #[Route('/{id}', name: 'api_user_show', methods: ['GET'])]
    public function show(int $id, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $userRepository->find($id);
        if (null === $user) {
            return new JsonResponse('User not found', 404);
        }

        $jsonContent = $serializer->serialize($user, 'json');

        return new JsonResponse($jsonContent, 200, [], true);
    }

    #[Route('/{id}', name: 'api_user_edit', methods: ['POST'])]
    public function edit(Request $request, int $id, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {

        $user = $userRepository->find($id);
        if (null === $user) {
            return new JsonResponse('User not found', 404);
        }

        $postData = json_decode($request->getContent(), true);

        switch (true) {
            case isset($postData['email']):
                $user->setEmail($postData['email']);
                break;
            case isset($postData['name']):
                $user->setName($postData['name']);
                break;
            case isset($postData['age']):
                $user->setAge($postData['age']);
                break;
            case isset($postData['sex']):
                $user->setSex($postData['sex']);
                break;
            case isset($postData['birthday']):
                $user->setBirthday($postData['birthday']);
                break;
            case isset($postData['phone']):
                $user->setPhone($postData['phone']);
                break;
            default:
                return new JsonResponse('Invalid request', 400);
                break;
        }

        $userRepository->save($user);

        $jsonContent = $serializer->serialize($user, 'json');
        return new JsonResponse($jsonContent, 200, [], true);

    }

    #[Route('/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function delete(int $id, UserRepository $userRepository): JsonResponse
    {

        $user = $userRepository->find($id);
        if (null === $user) {
            return new JsonResponse('User not found', 404);
        }

        $userRepository->delete($user);

        return new JsonResponse('Success', 200);
    }
}
