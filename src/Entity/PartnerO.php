<?php

namespace App\Entity;

use App\Repository\PartnerORepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM ;


#[ORM\Entity(repositoryClass: PartnerORepository::class)]
class PartnerO
{
    #[ORM\Id]
  #[ORM\GeneratedValue]
    
    #[ORM\Column]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
   
    private ?string $nom = null;
    
 #[ORM\Column(length: 255)]
                           
                            private ?string $email = null;

    #[ORM\Column]
   
    private ?int $user_id = null;

    #[ORM\Column(nullable: true)]
  
    private ?bool $is_active = null;

   #[ORM\Column]
   
    private ?int $id_permission = null;

   #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'partnerOs')]
   private Collection $Permission;

   public function __construct()
   {
       $this->Permission = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

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

    public function getIdPermission(): ?int
    {
        return $this->id_permission;
    }

    public function setIdPermission(int $id_permission): self
    {
        $this->id_permission = $id_permission;

        return $this;
    }

    /**
     * @return Collection<int, Permission>
     */
    public function getPermission(): Collection
    {
        return $this->Permission;
    }

    public function addPermission(Permission $permission): self
    {
        if (!$this->Permission->contains($permission)) {
            $this->Permission->add($permission);
        }

        return $this;
    }

    public function removePermission(Permission $permission): self
    {
        $this->Permission->removeElement($permission);

        return $this;
    }
}
