<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $partenaire_id = null;

    #[ORM\Column]
    private ?int $structure_id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_active = null;

    #[ORM\ManyToMany(targetEntity: PartnerO::class, mappedBy: 'Permission')]
    private Collection $partnerOs;

    public function __construct()
    {
        $this->partnerOs = new ArrayCollection();
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

    public function getPartenaireId(): ?int
    {
        return $this->partenaire_id;
    }

    public function setPartenaireId(int $partenaire_id): self
    {
        $this->partenaire_id = $partenaire_id;

        return $this;
    }

    public function getStructureId(): ?int
    {
        return $this->structure_id;
    }

    public function setStructureId(int $structure_id): self
    {
        $this->structure_id = $structure_id;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(?bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return Collection<int, PartnerO>
     */
    public function getPartnerOs(): Collection
    {
        return $this->partnerOs;
    }

    public function addPartnerO(PartnerO $partnerO): self
    {
        if (!$this->partnerOs->contains($partnerO)) {
            $this->partnerOs->add($partnerO);
            $partnerO->addPermission($this);
        }

        return $this;
    }

    public function removePartnerO(PartnerO $partnerO): self
    {
        if ($this->partnerOs->removeElement($partnerO)) {
            $partnerO->removePermission($this);
        }

        return $this;
    }
}
