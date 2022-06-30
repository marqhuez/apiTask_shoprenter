<?php

namespace App\Entity;

use App\Repository\SecretRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecretRepository::class)]
class Secret
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private ?string $hash;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $secretText;

    #[ORM\Column(type: 'datetime')]
    private DateTime|string $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTime|string $expiresAt;

	#[ORM\Column(type: 'integer')]
	private ?int $remainingViews;

	public function __construct(int $expiresAfter)
    {
		$this->hash = md5(rand(0, 1000) . time());
		$budapestTimezone = new DateTimeZone("GMT+2");
        $this->createdAt = new DateTime("now", $budapestTimezone);
        $this->expiresAt = new DateTime("+" . $expiresAfter . " minutes", $budapestTimezone);
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

	public function getSecretText(): ?string
	{
		return $this->secretText;
	}

	public function setSecretText(?string $secretText): void
	{
		$this->secretText = $secretText;
	}

	public function getRemainingViews(): ?int
	{
		return $this->remainingViews;
	}

	public function setRemainingViews(?int $remainingViews): void
	{
		$this->remainingViews = $remainingViews;
	}

    public function getCreatedAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = DateTime::createFromInterface($createdAt);

        return $this;
    }

    public function getExpiresAt(): string
    {
        return $this->expiresAt->format('Y-m-d H:i:s');
    }

    public function setExpiresAt(\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = DateTime::createFromInterface($expiresAt);

        return $this;
    }

    public function asArray(): array
    {
        return [
            $this->hash,
            $this->secretText,
            $this->remainingViews,
            $this->getCreatedAt(),
            $this->getExpiresAt()
        ];
    }

    public function asAssocArray(): array
    {
        return [
            "hash" => $this->hash,
            "secretText" => $this->secretText,
            "createdAt" => $this->getCreatedAt(),
            "expiresAt" => $this->getExpiresAt(),
            "remainingViews" => $this->remainingViews
        ];
    }
}
