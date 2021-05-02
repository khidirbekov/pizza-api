<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
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
            'security' => "is_granted('ROLE_ADMIN')"
        ],
        'get',
        'delete' => [
            'security' => "is_granted('ROLE_ADMIN')",
        ]
    ],
    denormalizationContext: [
        'groups' => [
            'pizzeria:write'
        ]
    ],
    normalizationContext: [
        'groups' => [
            'pizzeria:read',
            'GetBase'
        ]
    ]
)]
class Pizzeria extends BaseEntity
{
    /**
     * A city name
     *
     * @ORM\Column
     * @Groups({"pizzeria:read", "pizzeria:write"})
     */
    #[Assert\NotBlank]
    public string $name;

    /**
     * A address name
     *
     * @ORM\Column
     * @Groups({"pizzeria:read", "pizzeria:write"})
     */
    #[Assert\NotBlank]
    public string $address;

    /**
     * @var City|null The item that is being reviewed/rated
     *
     * @ApiFilter(SearchFilter::class)
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity=City::class)
     * @Groups({"pizzeria:read", "pizzeria:write"})
     */
    public ?City $city;

    public function getId(): ?int
    {
        return $this->id;
    }

//    public function setCity(?City $city, bool $updateRelation = true): void
//    {
//        $this->city = $city;
////        if ($updateRelation && null !== $city) {
////            $city->addReview($this, false);
////        }
//    }
//
//    public function getCity(): ?City
//    {
//        return $this->city;
//    }
}
