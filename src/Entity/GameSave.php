<?php
namespace App\Entity;

use App\Repository\GameSaveRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameSaveRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'unique_user_slot', fields: ['user', 'slot'])]
class GameSave
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private int $slot = 1;

    #[ORM\Column(length: 100)]
    private string $mapId = 'village_depart';

    #[ORM\Column]
    private int $posX = 0;

    #[ORM\Column]
    private int $posY = 0;

    #[ORM\Column]
    private int $playTime = 0;

    #[ORM\Column]
    private int $gold = 0;

    #[ORM\Column]
    private int $chapter = 1;

    #[ORM\Column(type: 'json')]
    private array $flags = [];

    #[ORM\Column(type: 'json')]
    private array $partyIds = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void { $this->createdAt = new \DateTimeImmutable(); }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void { $this->updatedAt = new \DateTimeImmutable(); }

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }

    public function getSlot(): int { return $this->slot; }
    public function setSlot(int $slot): static { $this->slot = $slot; return $this; }

    public function getMapId(): string { return $this->mapId; }
    public function setMapId(string $mapId): static { $this->mapId = $mapId; return $this; }

    public function getPosX(): int { return $this->posX; }
    public function setPosX(int $posX): static { $this->posX = $posX; return $this; }

    public function getPosY(): int { return $this->posY; }
    public function setPosY(int $posY): static { $this->posY = $posY; return $this; }

    public function getPlayTime(): int { return $this->playTime; }
    public function setPlayTime(int $playTime): static { $this->playTime = $playTime; return $this; }

    public function getGold(): int { return $this->gold; }
    public function setGold(int $gold): static { $this->gold = $gold; return $this; }

    public function getChapter(): int { return $this->chapter; }
    public function setChapter(int $chapter): static { $this->chapter = $chapter; return $this; }

    public function getFlags(): array { return $this->flags; }
    public function setFlags(array $flags): static { $this->flags = $flags; return $this; }

    public function getPartyIds(): array { return $this->partyIds; }
    public function setPartyIds(array $partyIds): static { $this->partyIds = $partyIds; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
}