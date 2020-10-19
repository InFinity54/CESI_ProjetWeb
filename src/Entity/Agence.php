<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 * @ApiResource
 */
class Agence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("vehicle-agence")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("vehicle-agence")
     */
    private $nom_ag;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse_ag;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $complement_ag;

    /**
     * @ORM\Column(type="integer")
     */
    private $codepostal_ag;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville_ag;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fax_ag;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $image_ag = "default.png";

    /**
     * @ORM\OneToMany(targetEntity=Vehicle::class, mappedBy="agence")
     */
    private $vehicles;

    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAg(): ?string
    {
        return $this->nom_ag;
    }

    public function setNomAg(string $nom_ag): self
    {
        $this->nom_ag = $nom_ag;

        return $this;
    }

    public function getAdresseAg(): ?string
    {
        return $this->adresse_ag;
    }

    public function setAdresseAg(string $adresse_ag): self
    {
        $this->adresse_ag = $adresse_ag;

        return $this;
    }

    public function getComplementAg(): ?string
    {
        return $this->complement_ag;
    }

    public function setComplementAg(?string $complement_ag): self
    {
        $this->complement_ag = $complement_ag;

        return $this;
    }

    public function getCodepostalAg(): ?int
    {
        return $this->codepostal_ag;
    }

    public function setCodepostalAg(int $codepostal_ag): self
    {
        $this->codepostal_ag = $codepostal_ag;

        return $this;
    }

    public function getVilleAg(): ?string
    {
        return $this->ville_ag;
    }

    public function setVilleAg(string $ville_ag): self
    {
        $this->ville_ag = $ville_ag;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getFaxAg(): ?string
    {
        return $this->fax_ag;
    }

    public function setFaxAg(string $fax_ag): self
    {
        $this->fax_ag = $fax_ag;

        return $this;
    }

    public function getImageAg(): ?string
    {
        return $this->image_ag;
    }

    public function setImageAg(string $image_ag): self
    {
        $this->image_ag = $image_ag;

        return $this;
    }

    /**
     * @return Collection|Vehicle[]
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): self
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles[] = $vehicle;
            $vehicle->setAgence($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): self
    {
        if ($this->vehicles->contains($vehicle)) {
            $this->vehicles->removeElement($vehicle);
            // set the owning side to null (unless already changed)
            if ($vehicle->getAgence() === $this) {
                $vehicle->setAgence(null);
            }
        }

        return $this;
    }
}
