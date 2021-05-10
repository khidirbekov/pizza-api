<?php

namespace App\Controller\Order;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Order;
use App\Exception\OrderException;
use App\Services\SmsRu;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfirmOrder extends AbstractController
{

    public function __construct(
        private ValidatorInterface $validator
    )
    {
    }

    public function __invoke(Order $data): Order
    {
        $this->validator->validate($data);
        if ($data->isConfirm) {
            throw new OrderException("Заказ уже подтвержден");
        }
        if ($data->plainCode == $data->code) {
            $data->isConfirm = true;
            return $data;
        }
        throw new OrderException(sprintf('Неправильный код'));
    }

}
