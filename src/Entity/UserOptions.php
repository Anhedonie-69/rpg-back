<?php
namespace App\Entity;

use App\Repository\UserOptionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserOptionsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UserOptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'options')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private int $volumeMaster = 80;

    #[ORM\Column]
    private int $volumeMusic = 70;

    #[ORM\Column]
    private int $volumeSfx = 100;

    #[ORM\Column]
    private bool $fullscreen = false;

    #[ORM\Column]
    private bool $showFps = false;

    #[ORM\Column(length: 10)]
    private string $textSpeed = 'normal';

    #[ORM\Column(length: 10)]
    private string $keyboardLayout = 'azerty';

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

    public function getVolumeMaster(): int { return $this->volumeMaster; }
    public function setVolumeMaster(int $v): static { $this->volumeMaster = $v; return $this; }

    public function getVolumeMusic(): int { return $this->volumeMusic; }
    public function setVolumeMusic(int $v): static { $this->volumeMusic = $v; return $this; }

    public function getVolumeSfx(): int { return $this->volumeSfx; }
    public function setVolumeSfx(int $v): static { $this->volumeSfx = $v; return $this; }

    public function isFullscreen(): bool { return $this->fullscreen; }
    public function setFullscreen(bool $v): static { $this->fullscreen = $v; return $this; }

    public function isShowFps(): bool { return $this->showFps; }
    public function setShowFps(bool $v): static { $this->showFps = $v; return $this; }

    public function getTextSpeed(): string { return $this->textSpeed; }
    public function setTextSpeed(string $v): static { $this->textSpeed = $v; return $this; }

    public function getKeyboardLayout(): string { return $this->keyboardLayout; }
    public function setKeyboardLayout(string $v): static { $this->keyboardLayout = $v; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
}