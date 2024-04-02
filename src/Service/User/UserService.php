<?php

namespace App\Service\User;

use App\DTO\UserDTO;
use App\DTO\UserEditDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{

    public function __construct(
        private readonly UserRepository      $userRepository,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface  $validator
    )
    {
    }

    /**
     * @throws Exception
     */
    public function newUser(#[MapRequestPayload] UserDTO $userDTO): string
    {
        $errors = $this->validator->validate($userDTO);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new Exception($errorsString, 400);
        }

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

        return $this->serializer->serialize($user, 'json');
    }

    /**
     * @throws Exception
     */
    public function showUser(int $userId): string
    {
        $user = $this->userRepository->find($userId);
        if (null === $user) {
            throw new Exception('User not found', 404);
        }

        return $this->serializer->serialize($user, 'json');
    }

    /**
     * @throws Exception
     */
    public function editUser(int $userId, #[MapRequestPayload] UserEditDTO $UserEditDTO): string
    {
        $errors = $this->validator->validate($UserEditDTO);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new Exception($errorsString, 400);
        }

        $user = $this->userRepository->find($userId);
        if (null === $user) {
            throw new Exception('User not found', 404);
        }

        switch (true) {
            case $UserEditDTO->email !== null:
                $user->setEmail($UserEditDTO->email);
                break;
            case $UserEditDTO->name !== null:
                $user->setName($UserEditDTO->name);
                break;
            case $UserEditDTO->age !== null:
                $user->setAge($UserEditDTO->age);
                break;
            case $UserEditDTO->sex !== null:
                $user->setSex($UserEditDTO->sex);
                break;
            case $UserEditDTO->birthday !== null:
                $user->setBirthday($UserEditDTO->birthday);
                break;
            case $UserEditDTO->phone !== null:
                $user->setPhone($UserEditDTO->phone);
                break;
        }

        $this->userRepository->edit();

        return $this->serializer->serialize($user, 'json');
    }

    /**
     * @throws Exception
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository->find($userId);
        if (null === $user) {
            throw new Exception('User not found', 404);
        }
        $this->userRepository->delete($user);
    }
}