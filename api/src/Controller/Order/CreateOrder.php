<?php

namespace App\Controller\Order;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Order;
use App\Exception\OrderException;
use App\Services\SmsRu;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateOrder extends AbstractController
{

    public function __construct(
        private ValidatorInterface $validator
    )
    {
    }

    public function __invoke(Order $data): Order
    {
        $this->validator->validate($data);
        $smsCode = rand(1000, 9999);
        $smsData = new stdClass();
        $smsData->to = $data->phone;
        $smsData->text = $data->customer . ", вы успешно создали заказ. Для подтверждения введите код: " . $smsCode;
        $data->code = $smsCode;

        $smsru = new SmsRu($this->getParameter('app.sms_key'));
        $sms = $smsru->send_one($smsData); // Отправка сообщения и возврат данных в переменную

        if ($sms->status == "OK") { // Запрос выполнен успешно
            return $data;
        } else {
            throw new OrderException("Ошибка с СМС сервисом");
        }
    }

}
