<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Table(name="`order`")
 * @ORM\Entity
 */
#[ApiResource(
    collectionOperations: [
        'post',
        'get' => ['security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_WAITER')"]
    ],
    itemOperations: [
        'put',
        'get',
        'delete' => [
            'security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_WAITER')",
        ]
    ],
    denormalizationContext: [
        'groups' => ['order:write']
    ],
    normalizationContext: [
        'groups' => [
            "order:read",
            "pizzeria:read",
            "GetBase",
            "pizzaGroup:read",
            'pizza:read',
            'GetFile'
        ]
    ]
)]
class Order extends BaseEntity
{
    /**
     * Customer name
     *
     * @ORM\Column
     *  @Groups({"order:read", "order:write"})
     */
    #[Assert\NotBlank]
    public string $customer;

    /**
     * Phone number
     *
     * @ORM\Column
     *  @Groups({"order:read", "order:write"})
     */
    #[Assert\NotBlank]
    public string $phone;

    /**
     * Pizzeria
     *
     * @ORM\ManyToOne(targetEntity=Pizzeria::class)
     * @Groups({"order:read", "order:write", "pizzeria:read", "GetBase", "pizza:read", "GetFile"})
     */
    #[Assert\NotBlank]
    public Pizzeria $pizzeria;

    /**
     * @var PizzaGroup[]|ArrayCollection $pizzaGroups
     * @ORM\ManyToMany(targetEntity="PizzaGroup", inversedBy="orders")
     * @ORM\JoinTable(
     *  name="pizza_group_order",
     *  joinColumns={
     *      @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="pizza_group_id", referencedColumnName="id")
     *  }
     * )
     * @Groups({"order:read", "order:write", "pizzaGroup:read", "GetBase"})
     */
    #[Assert\NotBlank]
    public $pizzaGroups;

    /**
     * Price
     *
     * @ORM\Column
     * @Groups({"order:read", "order:write"})
     */
    public string $summaryPrice;

    /**
     *
     * @ORM\Column (type="boolean")
     * @Groups({"order:read", "order:write", "GetFile"})
     */
    public bool $isConfirm = false;

    /**
     * @ORM\Column
     * @Groups({"order:read", "order:write", "GetFile"})
     */
    public string $status = "created";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        parent::__construct();
        $this->pizzaGroups = new ArrayCollection();
    }
}
