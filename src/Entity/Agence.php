<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 */
class Agence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="integer")
     */
    private $numero;

    /**
     * @ORM\Column(type="integer")
     */
    private $fax_ag;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $image_ag;

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

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getFaxAg(): ?int
    {
        return $this->fax_ag;
    }

    public function setFaxAg(int $fax_ag): self
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
}