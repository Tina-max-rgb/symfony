<?php

namespace App\Entity;

use App\Repository\StructureRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StructureRepository::class)]
class Structure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;    

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable:false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Partner::class, inversedBy: 'structure')]
    #[ORM\JoinColumn(name: 'partner_id', referencedColumnName: 'id', nullable:false)]
    private $partner;

    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'structure', fetch:'EAGER')]
    private Collection $permission;

    public function __construct()
    {
        $this->permission = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getPartner(): ?Partner
    {
        return $this->partner;
    }

    public function setPartner(?Partner $partner): self
    {
        $this->partner = $partner;

        return $this;
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

    public function clearPermissions()
    {
        $this->permission->clear();
        return $this;
    }
}
