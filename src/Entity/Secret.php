<?php

namespace App\Entity;

use App\Repository\SecretRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecretRepository::class)]
class Secret
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $secret;

    #[ORM\Column(type: 'integer')]
    private ?int $expireAfterViews;

    #[ORM\Column(type: 'integer')]
    private ?int $expireAfter;

    #[ORM\Column(type: 'datetime')]
    private DateTime|string $dateCreated;

    public function __construct()
    {
        $this->dateCreated = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    public function getExpireAfterViews(): ?int
    {
        return $this->expireAfterViews;
    }

    public function setExpireAfterViews(int $expireAfterViews): self
    {
        $this->expireAfterViews = $expireAfterViews;

        return $this;
    }

    public function getExpireAfter(): ?int
    {
        return $this->expireAfter;
    }

    public function setExpireAfter(int $expireAfter): self
    {
        $this->expireAfter = $expireAfter;

        return $this;
    }

//    public function getDateAsDateTime(): ?\DateTime
//    {
//        return $this->date;
//    }

    public function getDateCreated(): string
    {
        return $this->dateCreated->format('Y-m-d H:i:s');
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = DateTime::createFromInterface($dateCreated);

        return $this;
    }

    public function asArray(): array
    {
        return [
            $this->id,
            $this->secret,
            $this->expireAfterViews,
            $this->expireAfter,
            $this->dateCreated
        ];
    }
}
