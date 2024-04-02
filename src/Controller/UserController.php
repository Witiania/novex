<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\DTO\UserEditDTO;
use App\Repository\UserRepository;
use App\Service\User\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/user', name: 'api_user')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly UserService        $userService,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/new', name: 'api_user_new', methods: ['POST'])]
    public function new(#[MapRequestPayload] UserDTO $userDTO): JsonResponse
    {
        $result = $this->userService->newUser($userDTO);

        return new JsonResponse($result, 200, [], true);
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
    public function edit(#[MapRequestPayload] UserEditDTO $userDTO, int $id): JsonResponse
    {
        $errors = $this->validator->validate($userDTO);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new JsonResponse($errorsString, 400);
        }

        $result = $this->userService->editUser($id, $userDTO);

        return new JsonResponse($result, 200, [], true);

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

    #[Route('/', name: 'api_user_showAll', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $jsonResult = $this->userService->showAllUsers();

        return new JsonResponse($jsonResult, 200, [], true);
    }
}
