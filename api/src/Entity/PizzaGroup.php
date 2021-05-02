<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 *
 * @ORM\Entity
 */
#[ApiResource(
    collectionOperations: [
        'post'
    ],
    itemOperations: [
        'get',
        'delete' => [
            'security' => "is_granted('ROLE_ADMIN')",
        ]
    ],
    denormalizationContext: [
        'groups' => [
            'pizzaGroup:write'
        ]
    ],
    normalizationContext: [
        'groups' => [
            'pizzaGroup:read',
            'GetFile',
            'pizza:read',
            'GetBase'
        ]
    ]
)]
class PizzaGroup extends BaseEntity
{
     /**
     * Price
     *
     * @ORM\Column(type="integer")
     * @Groups({"pizzaGroup:read", "pizzaGroup:write"})
     */
    public int $count;

    /**
     * @var Pizza|null The item that is being reviewed/rated
     *
     * @ORM\ManyToOne(targetEntity=Pizza::class)
     * @Groups({"pizzaGroup:read", "pizza:read", "pizzaGroup:write"})
     */
    public ?Pizza $pizza;

    /**
     * @var Order|null The item that is being reviewed/rated
     *
     *  @ORM\ManyToMany(targetEntity=Order::class, mappedBy="pizzaGroups")
     */
    public $orders;

    public function getId(): ?int
    {
        return $this->id;
    }
}
