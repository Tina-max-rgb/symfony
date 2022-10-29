<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM ;

#[ORM\Entity(repositoryClass: PartnerRepository::class)]
class Partner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable:false)]
    private User $user;

    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'partner')]
    private Collection $permission;

    #[ORM\OneToMany(targetEntity: Structure::class, mappedBy: 'partner')]
    private Collection $structure;

    public function __construct()
    {
        $this->permission = new ArrayCollection();
        $this->structure = new ArrayCollection();
    }

     public function getId(): int
     {
        return $this->id;
     }

     /**
     * Set user.
     *
     * @param User $user
     *
     * @return Partner
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Collection<int, Permission>
    */
     public function getPermission(): Collection
     {
         return $this->permission;
     }

     public function addPermission(Permission $permission): self
     {
         if (!$this->permission->contains($permission)) {
             $this->permission->add($permission);
         }

         return $this;
     }

     public function removePermission(Permission $permission): self
     {
         $this->permission->removeElement($permission);

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
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        $this->structure->removeElement($structure);

        return $this;
    }

    public function __toString()
    {
        return $this->getUser()->getNom();
    }

    public function updateStructurePermission($permissions): self
    {
        foreach($this->structure as $structure) {            
            $structure_permissions = $structure->getPermission();
            foreach($structure_permissions as $permission) {                
                if(!$this->permission->contains($permission)) {
                    $structure->removePermission($permission);
                }
            }
        }

        return $this;
    }

    public function disableStructure(): self
    {
        foreach ($this->structure as $structure) {
            $structure->getUser()->setDisable();
        }
        return $this;
    }

    public function enableStructure(): self
    {
        foreach ($this->structure as $structure) {
            $structure->getUser()->setEnable();
        }
        return $this;
    }
}
