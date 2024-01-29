<?php

namespace App\Entity;

class TriParPrix
{

   private $price;

   
   public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }}