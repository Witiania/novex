<?php

namespace App\Service\User;

use App\DTO\UserDTO;
use App\DTO\UserEditDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

class UserService {

    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function newUser(UserDTO $userDTO): User {
        $user = $this->userRepository->findOneBy(['email' => $userDTO->email]);
        if (null !== $user) {
            throw new Exception('User already exists', 409);
        }

        $user = (new User())
            ->setEmail($userDTO->email)
            ->setName($userDTO->name)
            ->setAge($userDTO->age)
            ->setSex($userDTO->sex)
            ->setBirthday($userDTO->birthday)
            ->setPhone($userDTO->phone);

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @throws Exception
     */
    public function showUser(int $userId): User {
        /** @var ?User $user */
        $user = $this->userRepository->find($userId);
        if (null === $user) {
            throw new Exception('User not found', 404);
        }

        return $user;
    }

    /**
     * @throws Exception
     */
    public function editUser(int $userId, UserEditDTO $userEditDTO): User {
        /** @var ?User $user */
        $user = $this->userRepository->find($userId);
        if (null === $user) {
            throw new Exception('User not found', 404);
        }

        if (null !== $userEditDTO->email) {
            $user->setEmail($userEditDTO->email);
        }

        if (null !== $userEditDTO->name) {
            $user->setName($userEditDTO->name);
        }

        if (null !== $userEditDTO->age) {
            $user->setAge($userEditDTO->age);
        }

        if (null !== $userEditDTO->sex) {
            $user->setSex($userEditDTO->sex);
        }

        if (null !== $userEditDTO->birthday) {
            $user->setBirthday($userEditDTO->birthday);
        }

        if (null !== $userEditDTO->phone) {
            $user->setPhone($userEditDTO->phone);
        }

        $this->userRepository->edit();

        return $user;
    }

    /**
     * @throws Exception
     */
    public function deleteUser(int $userId): void {
        /** @var ?User $user */
        $user = $this->userRepository->find($userId);
        if (null === $user) {
            throw new Exception('User not found', 404);
        }
        $this->userRepository->delete($user);
    }

    public function getAllUsersPaginator(int $page = 1): Paginator {
        return $this->userRepository->getAllUsersPaginator($page);
    }
}