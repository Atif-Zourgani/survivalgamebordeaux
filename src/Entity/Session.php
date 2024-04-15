<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Ce nom de session est déjà utilisé.')]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $seatCount = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'session')]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: UserSession::class, mappedBy: 'session')]
    private Collection $userSessions;

    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'session')]
    private Collection $games;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->userSessions = new ArrayCollection();
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSeatCount(): ?int
    {
        return $this->seatCount;
    }

    public function setSeatCount(int $seatCount): static
    {
        $this->seatCount = $seatCount;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSession($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSession() === $this) {
                $user->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserSession>
     */
    public function getUserSessions(): Collection
    {
        return $this->userSessions;
    }

    public function addUserSession(UserSession $userSession): static
    {
        if (!$this->userSessions->contains($userSession)) {
            $this->userSessions->add($userSession);
            $userSession->setSession($this);
        }

        return $this;
    }

    public function removeUserSession(UserSession $userSession): static
    {
        if ($this->userSessions->removeElement($userSession)) {
            // set the owning side to null (unless already changed)
            if ($userSession->getSession() === $this) {
                $userSession->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->addSession($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            $game->removeSession($this);
        }

        return $this;
    }
}
