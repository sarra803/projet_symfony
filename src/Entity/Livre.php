<?php

namespace App\Entity;
use App\Entity\Librairie;
use App\Repository\LivreRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Reflection\Types\Self_;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 */
class Livre
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
    private $Auteur;

    /**
    * @ORM\Column(type="string", length=255)
    * @Assert\Length(
    * min = 5,
    * max = 50,
    * minMessage = "Le titre d'un livre doit comporter au moins {{ limit }} caractères",
    * maxMessage = "Le titre d'un livre doit comporter au plus {{ limit }} caractères"
    * )
    */

   
    private $Titre;

    /**
     * @ORM\Column(type="integer")
     */
    private $NbPages;

    /**
     * @ORM\ManyToOne(targetEntity=Librairie::class, inversedBy="relationships")
     * @ORM\JoinColumn(nullable=false)
     */
    private $librairie;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuteur(): ?string
    {
        return $this->Auteur;
    }

    public function setAuteur(string $Auteur): self
    {
        $this->Auteur = $Auteur;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): self
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getNbPages(): ?int
    {
        return $this->NbPages;
    }

    public function setNbPages(int $NbPages): self
    {
        $this->NbPages = $NbPages;

        return $this;
    }

    public function getLibrairie(): ?Librairie
    {
        return $this->librairie;
    }

    public function setLibrairie(?Librairie $librairie): self
    {
         $this->librairie = $librairie;
         
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }
    
}
