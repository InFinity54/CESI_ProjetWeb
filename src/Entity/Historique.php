<?php

namespace App\Entity;

use App\Repository\HistoriqueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoriqueRepository::class)
 */
class Historique
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Agent::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity=Vehicle::class, inversedBy="historiques")
     * @ORM\JoinColumn(name="vehicle_id", referencedColumnName="numberplate",nullable=false)
     */
    private $vehicle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateheure_modif;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $nature_modif;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $description_modif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ancienne_valeur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nouvelle_valeur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getDateheureModif(): ?\DateTimeInterface
    {
        return $this->dateheure_modif;
    }

    public function setDateheureModif(\DateTimeInterface $dateheure_modif): self
    {
        $this->dateheure_modif = $dateheure_modif;

        return $this;
    }

    public function getNatureModif(): ?string
    {
        return $this->nature_modif;
    }

    public function setNatureModif(string $nature_modif): self
    {
        $this->nature_modif = $nature_modif;

        return $this;
    }

    public function getDescriptionModif(): ?string
    {
        return $this->description_modif;
    }

    public function setDescriptionModif(?string $description_modif): self
    {
        $this->description_modif = $description_modif;

        return $this;
    }

    public function getAncienneValeur(): ?string
    {
        return $this->ancienne_valeur;
    }

    public function setAncienneValeur(?string $ancienne_valeur): self
    {
        $this->ancienne_valeur = $ancienne_valeur;

        return $this;
    }

    public function getNouvelleValeur(): ?string
    {
        return $this->nouvelle_valeur;
    }

    public function setNouvelleValeur(?string $nouvelle_valeur): self
    {
        $this->nouvelle_valeur = $nouvelle_valeur;

        return $this;
    }
}
