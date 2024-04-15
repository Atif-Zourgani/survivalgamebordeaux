<?php

namespace App\Entity;

use App\Repository\UserSessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSessionRepository::class)]
class UserSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'userSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?session $session = null;

    #[ORM\Column]
    private ?int $rank = null;

    #[ORM\Column(nullable: true)]
    private ?int $coin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSession(): ?session
    {
        return $this->session;
    }

    public function setSession(?session $session): static
    {
        $this->session = $session;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getCoin(): ?int
    {
        return $this->coin;
    }

    public function setCoin(?int $coin): static
    {
        $this->coin = $coin;

        return $this;
    }
}
