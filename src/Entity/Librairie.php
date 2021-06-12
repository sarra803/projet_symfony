<?php

namespace App\Entity;

use App\Repository\LibrairieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LibrairieRepository::class)
 */
class Librairie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Adresse;

    /**
     * @ORM\Column(type="integer")
     */
    private $NbEmploye;

    /**
     * @ORM\Column(type="integer")
     */
    private $NbLivre;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="librairie", orphanRemoval=true)
     */
    private $relationships;

    public function __construct()
    {
        $this->relationships = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getNbEmploye(): ?int
    {
        return $this->NbEmploye;
    }

    public function setNbEmploye(int $NbEmploye): self
    {
        $this->NbEmploye = $NbEmploye;

        return $this;
    }

    public function getNbLivre(): ?int
    {
        return $this->NbLivre;
    }

    public function setNbLivre(int $NbLivre): self
    {
        $this->NbLivre = $NbLivre;

        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getRelationships(): Collection
    {
        return $this->relationships;
    }

    public function addRelationship(Livre $relationship): self
    {
        if (!$this->relationships->contains($relationship)) {
            $this->relationships[] = $relationship;
            $relationship->setLibrairie($this);
        }

        return $this;
    }

    public function removeRelationship(Livre $relationship): self
    {
        if ($this->relationships->removeElement($relationship)) {
            // set the owning side to null (unless already changed)
            if ($relationship->getLibrairie() === $this) {
                $relationship->setLibrairie(null);
            }
        }

        return $this;
    }
}
