<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class UserService
{

    public function __construct(
       private readonly UserRepository $userRepository,
       private readonly SerializerInterface $serializer
    )
    {
    }

    /**
     * @throws Exception
     */
    public function newUser(string $email, string $name, int $age, string $sex, string $birthday, string $phone): void
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (null!== $user) {
            throw new Exception('User already exists',409);
        }

        $user = (new User())
            ->setEmail($email)
            ->setName($name)
            ->setAge($age)
            ->setSex($sex)
            ->setBirthday($birthday)
            ->setPhone($phone);

        $this->userRepository->save($user);
    }

    /**
     * @throws Exception
     */
    public function showUser(int $userId): string
    {
        $user = $this->userRepository->find($userId);
        if (null === $user) {
            throw new Exception('User not found',404);
        }

        return $this->serializer->serialize($user, 'json');
    }

    /**
     * @throws Exception
     */
    public function editUser(int $userId, string $email, string $name, int $age, string $sex, string $birthday, string $phone): void
    {
        $user = $this->userRepository->find($userId);
        if (null === $user) {
            throw new Exception('User not found',404);
        }

        $user
            ->setEmail($email)
            ->setName($name)
            ->setAge($age)
            ->setSex($sex)
            ->setBirthday($birthday)
            ->setPhone($phone);

        $this->userRepository->edit();
    }



    /**
     * @throws Exception
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository->find($userId);
        if (null === $user) {
            throw new Exception('User not found',404);
        }
        $this->userRepository->delete($user);

    }
}