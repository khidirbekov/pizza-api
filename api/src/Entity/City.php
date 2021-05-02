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
)]
class City extends BaseEntity
{
    /**
     * A city name
     *
     * @ORM\Column
     * @Groups ({"city", "pizzeria:read"})
     */
    #[Assert\NotBlank]
    public string $name = '';

    /**
     * Longitude
     *
     * @ORM\Column
     * @Groups ({"city", "pizzeria:read"})
     */
    #[Assert\NotBlank]
    public string $longitude;

    /**
     * Latitude
     *
     * @ORM\Column
     * @Groups ({"city", "pizzeria:read"})
     */
    #[Assert\NotBlank]
    public string $latitude;

    public function getId(): ?int
    {
        return $this->id;
    }
}
