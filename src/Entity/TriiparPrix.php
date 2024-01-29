<?php

namespace App\Entity;

class TriiparPrix
{

   private $prix;

   
   public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }}
