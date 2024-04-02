<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    #[Assert\NotBlank(message: 'Email cannot be empty.')]
    #[Assert\Regex('/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/')]
    public string $email;

    #[Assert\NotBlank(message: 'Name cannot be empty.')]
    #[Assert\Type(type: 'string', message: 'Name\'s value {{ value }} is not a string.')]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Name must be not less than {{ limit }} characters.',
        maxMessage: 'Name must be not more than {{ limit }} characters.'
    )]
    public string $name;

    #[Assert\NotBlank(message: 'Age cannot be empty.')]
    #[Assert\Type(type: 'int', message: 'Age\'s value {{ value }} is not a number.')]
    #[Assert\Range(min: 18, max: 110, notInRangeMessage: 'You must be at least {{ limit }} years old. You cannot be older than {{ limit }} years old.')]
    public int $age;

    #[Assert\NotBlank(message: 'Sex cannot be empty.')]
    #[Assert\Choice(choices: ['male', 'female'], message: 'Choose a valid gender.')]
    public string $sex;

    #[Assert\NotBlank(message: 'Birthday cannot be empty.')]
    #[Assert\DateTime(format: 'd.m.Y', message: 'The birthday "{{ value }}" is not a valid date.')]
    public string $birthday;

    #[Assert\NotBlank(message: 'Phone cannot be empty.')]
    #[Assert\Type(type: 'string', message: 'Phone\'s value {{ value }} is not a string.')]
    #[Assert\Regex('/^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/', message: 'Phone\'s value does not fit.')]
    public string $phone;
}