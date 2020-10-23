<?php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 * @ApiResource(
 *     attributes={
 *          "pagination_enabled"=false,
 *          "order": {"numberplate": "asc"}
 *     },
 *     normalizationContext={"groups"={"vehicle","vehicle-agence","vehicle-status"}}
 * )
 */
class Vehicle
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=9)
     * @Groups("vehicle")
     */
    private $numberplate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("vehicle")
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("vehicle")
     */
    private $model;

    /**
     * @ORM\Column(type="date")
     * @Groups("vehicle")
     * @ApiProperty(attributes={
     *     "normalization_context"={
     *         "datetime_format"="Y-m-d|",
     *     },
     * })
     */
    private $manufacture_date;

    /**
     * @ORM\Column(type="float")
     * @Groups("vehicle")
     */
    private $height;

    /**
     * @ORM\Column(type="float")
     * @Groups("vehicle")
     */
    private $width;

    /**
     * @ORM\Column(type="float")
     * @Groups("vehicle")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     * @Groups("vehicle")
     */
    private $power;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("vehicle")
     */
    private $isActivated;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="vehicles")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource
     * @Groups("vehicle-agence")
     */
    private $agence;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="vehicles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("vehicle-status")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("vehicle")
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity=Historique::class, mappedBy="vehicle")
     */
    private $historiques;

    public function __construct()
    {
        $this->historiques = new ArrayCollection();
    }

    public function getNumberplate(): ?string
    {
        return $this->numberplate;
    }

    public function setNumberplate(string $numberplate): self
    {
        $this->numberplate = $numberplate;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getManufactureDate(): ?\DateTimeInterface
    {
        return $this->manufacture_date;
    }

    public function setManufactureDate(\DateTimeInterface $manufacture_date): self
    {
        $this->manufacture_date = $manufacture_date;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    public function getPhotos(): array
    {
        $photos = explode(";", $this->photos);
        return array_unique($photos);
    }

    public function setPhotos(array $photos): self
    {
        $this->photos = implode(";", array_unique($photos));
        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Historique[]
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): self
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques[] = $historique;
            $historique->setVehicle($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): self
    {
        if ($this->historiques->contains($historique)) {
            $this->historiques->removeElement($historique);
            // set the owning side to null (unless already changed)
            if ($historique->getVehicle() === $this) {
                $historique->setVehicle(null);
            }
        }

        return $this;
    }
}
