<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\ManyToMany(targetEntity: Partner::class, mappedBy: 'permission')]
    private Collection $partner;

    #[ORM\ManyToMany(targetEntity: Structure::class, mappedBy: 'permission')]
    private Collection $structure;

    public function __construct()
    {
        $this->partner = new ArrayCollection();
        $this->structure = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
    
    /**
     * @return Collection<int, Partner>
     */
    public function getPartner(): Collection
    {
        return $this->partner;
    }

    public function addPartner(Partner $partner): self
    {
        if (!$this->partner->contains($partner)) {
            $this->partner->add($partner);
            $partner->addPermission($this);
        }

        return $this;
    }

    public function removePartner(Partner $partner): self
    {
        if ($this->partner->removeElement($partner)) {
            $partner->removePermission($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Structure>
     */
    public function getStructure(): Collection
    {
        return $this->structure;
    }

    public function addStructure(Structure $structure): self
    {
        if (!$this->structure->contains($structure)) {
            $this->structure->add($structure);
            $structure->addPermission($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structure->removeElement($structure)) {
            $structure->removePermission($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
