<?php

namespace App\Entity;

use App\Repository\CookieJarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CookieJarRepository::class)]
class CookieJar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $LastCookieDateTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $LastCookieWinner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $LastCookieItem = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastCookieDateTime(): ?\DateTimeInterface
    {
        return $this->LastCookieDateTime;
    }

    public function setLastCookieDateTime(\DateTimeInterface $LastCookieDateTime): static
    {
        $this->LastCookieDateTime = $LastCookieDateTime;

        return $this;
    }

    public function getLastCookieWinner(): ?string
    {
        return $this->LastCookieWinner;
    }

    public function setLastCookieWinner(?string $LastCookieWinner): static
    {
        $this->LastCookieWinner = $LastCookieWinner;

        return $this;
    }

    public function getLastCookieItem(): ?string
    {
        return $this->LastCookieItem;
    }

    public function setLastCookieItem(?string $LastCookieItem): static
    {
        $this->LastCookieItem = $LastCookieItem;

        return $this;
    }
}
