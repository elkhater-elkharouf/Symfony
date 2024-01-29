<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipationRepository::class)
 */
class Participation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    

    /**
     * @ORM\Column(type="string", length=100)
             * @Assert\NotBlank

     *  @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your name cannot contain a number"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     *          @Assert\NotBlank

     *  @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your prenom cannot contain a number"
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     */
    private $datanes;

    /**
     * @ORM\Column(type="string")
     
     */
    private $numtel;

     /**
     * @ORM\Column(type="string", length=100)
     
     
     */
     
    private $adresse;
     /**
     * @ORM\Column(type="string", length=100)
     
     
     */
     
    private $idevent;

    
    
    

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDatanes(): ?\DateTimeInterface
    {
        return $this->datanes;
    }

    public function setDatanes(\DateTimeInterface $datanes): self
    {
        $this->datanes = $datanes;

        return $this;
    }

    public function getNumtel(): ?string
    {
        return $this->numtel;
    }

    public function setNumtel(string $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }
    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
    public function getIdevent(): ?string
    {
        return $this->adresse;
    }
    public function setIdevent(string $idevent): self
    {
        $this->idevent = $idevent;

        return $this;
    }
    
    
}
