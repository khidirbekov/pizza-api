<?php
namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class BaseEntity
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"GetBase", "GetObjBase"})
     */
    protected $id;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     * @Groups({"GetBase", "GetObjBase"})
     */
    protected $dateCreate;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     * @Groups({"GetBase", "GetObjBase"})
     */
    protected $dateUpdate;

    public function __construct()
    {
        try {
            $this->dateCreate = new DateTimeImmutable();
            $this->dateUpdate = new DateTimeImmutable();
        } catch (Exception $e) {
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateUpdate(): ?DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function getDateCreate(): ?DateTimeInterface
    {
        return $this->dateCreate;
    }
}
