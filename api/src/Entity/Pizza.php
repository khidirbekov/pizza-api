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
        'post' => [
            'security' => "is_granted('ROLE_ADMIN')",
        ],
        'get'
    ],
    itemOperations: [
        'put' => [
            'security' => "is_granted('ROLE_ADMIN')",
        ],
        'get',
        'delete' => [
            'security' => "is_granted('ROLE_ADMIN')",
        ]
    ],
    denormalizationContext: [
        'groups' => [
            'pizza:write'
        ]
    ],
    normalizationContext: [
        'groups' => [
            'pizza:read',
            'GetFile',
            'GetBase'
        ]
    ]
)]
class Pizza extends BaseEntity
{
    /**
     * Pizza name
     *
     * @ORM\Column
     *  @Groups({"pizza:read", "pizza:write"})
     */
    #[Assert\NotBlank]
    public string $name;

    /**
     * Description
     *
     * @ORM\Column
     * @Groups({"pizza:read", "pizza:write"})
     */
    #[Assert\NotBlank]
    public string $description;

    /**
     * Price
     *
     * @ORM\Column(type="integer")
     * @Groups({"pizza:read", "pizza:write"})
     */
    public int $price;

    /**
     * @var File|null The item that is being reviewed/rated
     *
     * @ORM\ManyToOne(targetEntity=File::class)
     * @Groups({"pizza:read", "pizza:write", "GetFile"})
     */
    public ?File $image;

    public function getId(): ?int
    {
        return $this->id;
    }
}
