<?php

namespace App\Controller\Order;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Order;
use App\Exception\OrderException;
use App\Services\SmsRu;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChangeOrderStatus extends AbstractController
{

    public function __construct(
        private ValidatorInterface $validator
    )
    {
    }

    public function __invoke(Order $data): Order
    {
        $this->validator->validate($data);
        if (!$data->isConfirm) {
            throw new OrderException("Сначала подтвердите заказ");
        }
        if ($data->status == "completed") {
            $smsData = new stdClass();
            $smsData->to = $data->phone;
            $smsData->text = $data->customer . ", ваш заказ уже готов. Вы можете забрать!";

            $smsru = new SmsRu($this->getParameter('app.sms_key'));
            $sms = $smsru->send_one($smsData); // Отправка сообщения и возврат данных в переменную

            if ($sms->status == "OK") { // Запрос выполнен успешно
                return $data;
            } else {
                throw new OrderException('Произошла ошибка с CМС');
            }
        } else if ($data->status == "active" || $data->status == "created") {
            return $data;
        }
        throw new OrderException('Некорректный статус');
    }
}
