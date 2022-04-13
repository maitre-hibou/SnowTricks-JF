<?php

namespace App\Tricks\Entity;

use DateTime;
use App\Media\Entity\Media;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use App\Tricks\Repository\TricksRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TricksRepository::class)]
#[HasLifecycleCallbacks]
class Tricks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;
    
    #[ORM\Column(type: 'string', length: 255)]
    private $group_stunt;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\Column(type: 'datetime')]
    private $updated_at;

    #[ORM\OneToMany(mappedBy: 'tricks', targetEntity: Media::class, orphanRemoval: true, cascade: ["persist"])]
    private $medias;

    public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGroupStunt(): ?string
    {
        return $this->group_stunt;
    }

    public function setGroupStunt(string $group_stunt): self
    {
        $this->group_stunt = $group_stunt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    #[PrePersist]
    public function prePersist()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt($this->getCreatedAt());
    }

    #[PreUpdate]
    public function preUpdate()
    {
        $this->setUpdatedAt(new DateTime());
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedias(Media $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setTricks($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getTricks() === $this) {
                $media->setTricks(null);
            }
        }

        return $this;
    }

    public function getHeaderMedia()
    {
        $medias = $this->getMedias();

        if (null !== $medias) {            
            foreach ($medias as $media) {
                if (true === $media->getHeader()) {
                    return $media;
                }
            }
        }
        return null;
    }
}
