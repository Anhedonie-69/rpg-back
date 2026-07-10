<?php
namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: 'game_character')]
#[ORM\HasLifecycleCallbacks]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // Identité
    #[ORM\Column(length: 50)]
    private string $name = '';

    #[ORM\Column(length: 20)]
    private string $class = ''; // monk / warrior / red_mage / alchemist

    #[ORM\Column]
    private int $slot = 1; // position dans l'équipe 1-4

    #[ORM\Column]
    private bool $unlocked = false; // disponible dans l'histoire ?

    // Progression
    #[ORM\Column]
    private int $level = 1;

    #[ORM\Column]
    private int $experience = 0;

    // Stats de base (max)
    #[ORM\Column]
    private int $hpMax = 100;

    #[ORM\Column]
    private int $mpMax = 50;

    #[ORM\Column]
    private int $atk = 10;

    #[ORM\Column]
    private int $def = 10;

    #[ORM\Column]
    private int $mag = 10;

    #[ORM\Column]
    private int $res = 10;

    #[ORM\Column]
    private int $spd = 10; // crucial pour le CTB !

    #[ORM\Column]
    private int $lck = 5;

    // Stats actuelles (sauvegardées entre les combats)
    #[ORM\Column]
    private int $hpCurrent = 100;

    #[ORM\Column]
    private int $mpCurrent = 50;

    // Statuts persistants (entre combats)
    #[ORM\Column(type: 'json')]
    private array $statusEffects = [];

    // Compétences débloquées
    #[ORM\Column(type: 'json')]
    private array $skills = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void { $this->createdAt = new \DateTimeImmutable(); }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void { $this->updatedAt = new \DateTimeImmutable(); }

    // Getters / Setters
    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getClass(): string { return $this->class; }
    public function setClass(string $class): static { $this->class = $class; return $this; }

    public function getSlot(): int { return $this->slot; }
    public function setSlot(int $slot): static { $this->slot = $slot; return $this; }

    public function isUnlocked(): bool { return $this->unlocked; }
    public function setUnlocked(bool $unlocked): static { $this->unlocked = $unlocked; return $this; }

    public function getLevel(): int { return $this->level; }
    public function setLevel(int $level): static { $this->level = $level; return $this; }

    public function getExperience(): int { return $this->experience; }
    public function setExperience(int $experience): static { $this->experience = $experience; return $this; }

    public function getHpMax(): int { return $this->hpMax; }
    public function setHpMax(int $hpMax): static { $this->hpMax = $hpMax; return $this; }

    public function getMpMax(): int { return $this->mpMax; }
    public function setMpMax(int $mpMax): static { $this->mpMax = $mpMax; return $this; }

    public function getAtk(): int { return $this->atk; }
    public function setAtk(int $atk): static { $this->atk = $atk; return $this; }

    public function getDef(): int { return $this->def; }
    public function setDef(int $def): static { $this->def = $def; return $this; }

    public function getMag(): int { return $this->mag; }
    public function setMag(int $mag): static { $this->mag = $mag; return $this; }

    public function getRes(): int { return $this->res; }
    public function setRes(int $res): static { $this->res = $res; return $this; }

    public function getSpd(): int { return $this->spd; }
    public function setSpd(int $spd): static { $this->spd = $spd; return $this; }

    public function getLck(): int { return $this->lck; }
    public function setLck(int $lck): static { $this->lck = $lck; return $this; }

    public function getHpCurrent(): int { return $this->hpCurrent; }
    public function setHpCurrent(int $hp): static { $this->hpCurrent = max(0, min($hp, $this->hpMax)); return $this; }

    public function getMpCurrent(): int { return $this->mpCurrent; }
    public function setMpCurrent(int $mp): static { $this->mpCurrent = max(0, min($mp, $this->mpMax)); return $this; }

    public function getStatusEffects(): array { return $this->statusEffects; }
    public function setStatusEffects(array $effects): static { $this->statusEffects = $effects; return $this; }

    public function getSkills(): array { return $this->skills; }
    public function setSkills(array $skills): static { $this->skills = $skills; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }

    public function isAlive(): bool { return $this->hpCurrent > 0; }
    public function isDead(): bool { return $this->hpCurrent <= 0; }
}
