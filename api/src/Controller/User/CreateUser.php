<?php

namespace App\Controller\User;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUser
{

    public function __construct(
        private UserPasswordEncoderInterface $passwordEncoder,
         private ValidatorInterface $validator
    )
    {
    }

    public function __invoke(User $data): User
    {
        $this->validator->validate($data);
        return $data->setPassword(
            $this->passwordEncoder->encodePassword($data, $data->getPassword())
        );
    }

}
