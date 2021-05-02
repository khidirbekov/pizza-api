<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\User\CreateUser;
use App\Controller\User\CurrentUser;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => ['access_control' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')"],
        'post' => [
            'controller' => CreateUser::class,
            'security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')",
            'groups' => ["user:create"]
        ],
        'current' => [
            'security' => "is_granted('ROLE_USER')",
            'method' => 'get',
            'path' => '/users/current',
            'controller' => CurrentUser::class,
            'pagination_enabled' => 'false',
            'normalization_context' => [
                'groups' => 'user:read'
            ],
            'swagger_context' => [
                 'parameters' => ['']
            ]
        ]
    ],
    itemOperations: [
        'put' => [
            'controller' => CreateUser::class,
            'security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or object.owner == user",
            'denormalization_context' => [
                'groups' => ["user:update"]
            ]
        ],
        'get' => [
            'access_control' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or object.owner == user",
        ],
        'delete' => ['access_control' => "is_granted('ROLE_ADMIN')"]
    ],
    normalizationContext: [
        'groups' => ['user:read']
    ]
)]
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "user:update"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read", "user:update", "user:create"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups({"user:read", "user:update", "user:create"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups({"user:read", "user:update", "user:create"})
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups({"user:read", "user:update", "user:create"})
     */
    private $phone;

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:read", "user:update", "user:create"})
     */
    private $roles = [];

    /**
     * @var User The owner
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    public $owner;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:create"})
     */
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): string
    {
        return (string) $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
