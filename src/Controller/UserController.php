<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\DTO\UserEditDTO;
use App\Service\User\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/user', name: 'api_user')]
class UserController extends AbstractController {
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('', name: 'api_user_showAll', methods: ['GET'])]
    public function showAll(Request $request): JsonResponse {
        $page = (int)$request->get('page', 1);
        $paginator = $this->userService->getAllUsersPaginator($page);

        return $this->json([
            'data' => $paginator->getIterator(),
            'totalCount' => $paginator->count(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('', name: 'api_user_new', methods: ['POST'])]
    public function create(#[MapRequestPayload] UserDTO $userDTO): JsonResponse {
        $user = $this->userService->newUser($userDTO);

        return $this->json($user);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'api_user_show', methods: ['GET'])]
    public function show(int $id): JsonResponse {
        $user = $this->userService->showUser($id);

        return $this->json($user);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'api_user_edit', methods: ['POST'])]
    public function edit(#[MapRequestPayload] UserEditDTO $userDTO, int $id): JsonResponse {
        $user = $this->userService->editUser($id, $userDTO);

        return $this->json($user);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse {
        $this->userService->deleteUser($id);

        return $this->json('Success');
    }
}
