<?php

namespace App\Controller\User;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;

class UpdateUser
{

    public function __construct(private ValidatorInterface $validator) {}

    public function __invoke(User $data): User
    {
        $this->validator->validate($data);

        return $data;
    }

}
