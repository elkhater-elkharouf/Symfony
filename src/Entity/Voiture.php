<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoitureRepository::class)
 */
class Voiture
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
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modele;

    /**
     * @ORM\Column(type="integer")
     */
    private $killometrage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $carburant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transmission;

    /**
     * @ORM\Column(type="integer")
     */
    private $bagage;

    /**
     * @ORM\Column(type="integer")
     */
    private $place;

    /**
     * @ORM\Column(type="integer",unique=true)
     */
    private $matricule;

    /**
     * @ORM\OneToMany(targetEntity=Reservationv::class, mappedBy="voiture")
     */
    private $reservationvs;

    public function __construct()
    {
        $this->reservationvs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getKillometrage(): ?int
    {
        return $this->killometrage;
    }

    public function setKillometrage(int $killometrage): self
    {
        $this->killometrage = $killometrage;

        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): self
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): self
    {
        $this->transmission = $transmission;

        return $this;
    }

    public function getBagage(): ?int
    {
        return $this->bagage;
    }

    public function setBagage(int $bagage): self
    {
        $this->bagage = $bagage;

        return $this;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getMatricule(): ?int
    {
        return $this->matricule;
    }

    public function setMatricule(int $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * @return Collection|Reservationv[]
     */
    public function getReservationvs(): Collection
    {
        return $this->reservationvs;
    }

    public function addReservationv(Reservationv $reservationv): self
    {
        if (!$this->reservationvs->contains($reservationv)) {
            $this->reservationvs[] = $reservationv;
            $reservationv->setVoiture($this);
        }

        return $this;
    }

    public function removeReservationv(Reservationv $reservationv): self
    {
        if ($this->reservationvs->removeElement($reservationv)) {
            // set the owning side to null (unless already changed)
            if ($reservationv->getVoiture() === $this) {
                $reservationv->setVoiture(null);
            }
        }

        return $this;
    }
}
