<?php

namespace App\Entity;

class PropertySearch
{

   private $nom;
   private $price;

   
   public function getNom(): ?string
   {
       return $this->nom;
   }

   public function setNom(string $nom): self
   {
       $this->nom = $nom;

       return $this;
   }
   public function getPrice(): ?float
   {
       return $this->price;
   }

   public function setPrice(float $price): self
   {
       $this->price = $price;

       return $this;
   }
}