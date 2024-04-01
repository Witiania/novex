<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\DTO\UserEditDTO;
use App\Repository\UserRepository;
use App\Service\User\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/user', name: 'api_user', methods: ['POST'])]
class UserController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly UserService  $userService,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/new', name: 'api_user_new', methods: ['POST'])]
    public function new(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

        $errors = $this->validator->validate($userDTO);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new JsonResponse($errorsString, 400);
        }

        $this->userService->newUser(
            $userDTO->email,
            $userDTO->name,
            $userDTO->age,
            $userDTO->sex,
            $userDTO->birthday,
            $userDTO->phone
        );

        return new JsonResponse('Success', 200);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'api_user_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $data = $this->userService->showUser($id);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'api_user_edit', methods: ['POST'])]
    public function edit(Request $request, int $id, SerializerInterface $serializer): JsonResponse
    {
        $userDTO = $serializer->deserialize($request->getContent(), UserEditDTO::class, 'json');
        $errors = $this->validator->validate($userDTO);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new JsonResponse($errorsString, 400);
        }
        $this->userService->editUser(
            $id,
            $userDTO->email,
            $userDTO->name,
            $userDTO->age,
            $userDTO->sex,
            $userDTO->birthday,
            $userDTO->phone
        );

        return new JsonResponse('success', 200, [], true);

    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {

        $this->userService->deleteUser($id);

        return new JsonResponse('Success', 200);
    }
}
