<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function getMostRecentVehicles()
    {
        return $this->createQueryBuilder("v")
            ->orderBy("v.manufacture_date", "desc")
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function getBrands()
    {
        return $this->createQueryBuilder("v")
            ->select("v.brand")
            ->distinct(true)
            ->orderBy("v.brand", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function getModels()
    {
        return $this->createQueryBuilder("v")
            ->select("v.model, v.brand")
            ->distinct(true)
            ->orderBy("v.model", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function getOldestManufactureDate()
    {
        return $this->createQueryBuilder("v")
            ->select("MIN(v.manufacture_date) AS minmanufacturedate")
            ->groupBy("v.manufacture_date")
            ->getQuery()
            ->getResult()[0]["minmanufacturedate"];
    }

    public function getLowestWeight()
    {
        return $this->createQueryBuilder("v")
            ->select("MIN(v.weight) AS minweight")
            ->groupBy("v.weight")
            ->getQuery()
            ->getResult()[0]["minweight"];
    }

    public function getHighestWeight()
    {
        return $this->createQueryBuilder("v")
            ->select("MAX(v.weight) AS maxweight")
            ->groupBy("v.weight")
            ->getQuery()
            ->getResult()[0]["maxweight"];
    }

    public function getLowestPower()
    {
        return $this->createQueryBuilder("v")
            ->select("MIN(v.power) AS minpower")
            ->groupBy("v.power")
            ->getQuery()
            ->getResult()[0]["minpower"];
    }

    public function getHighestPower()
    {
        return $this->createQueryBuilder("v")
            ->select("MAX(v.power) AS maxpower")
            ->groupBy("v.power")
            ->getQuery()
            ->getResult()[0]["maxpower"];
    }

    public function findVehicles(array $filters, bool $isActivated)
    {
        $query = $this->createQueryBuilder("v");
        $query->where("v.isActivated = ".(($isActivated) ? 1 : 0));

        if (isset($filters["brand"]) && $filters["brand"] !== null && $filters["brand"] !== "")
        {
            $query->andWhere("v.brand = '".$filters["brand"]."'");
        }

        if (isset($filters["model"]) && $filters["model"] !== null && $filters["model"] !== "")
        {
            $query->andWhere("v.model = '".$filters["model"]."'");
        }

        if (isset($filters["agence"]) && $filters["agence"] !== null && $filters["agence"] !== "")
        {
            $query->andWhere("v.agence = ".$filters["agence"]);
        }

        if (isset($filters["manufactureDateStart"]) && $filters["manufactureDateStart"] !== null && $filters["manufactureDateStart"] !== "" && isset($filters["manufactureDateEnd"]) && $filters["manufactureDateEnd"] !== null && $filters["manufactureDateEnd"] !== "")
        {
            $query->andWhere("v.manufacture_date BETWEEN '".$filters["manufactureDateStart"]."' AND '".$filters["manufactureDateEnd"]."'");
        }

        if (isset($filters["height"]) && $filters["height"] !== null && $filters["height"] !== "")
        {
            $query->andWhere("v.height = ".str_replace(",", ".", $filters["height"]));
        }

        if (isset($filters["width"]) && $filters["width"] !== null && $filters["width"] !== "")
        {
            $query->andWhere("v.width = ".str_replace(",", ".", $filters["height"]));
        }

        if (isset($filters["weight"][0]) && $filters["weight"][0] !== null && $filters["weight"][0] !== "" && isset($filters["weight"][1]) && $filters["weight"][1] !== null && $filters["weight"][1] !== "" and $filters["weight"][0] !== $filters["weight"][1])
        {
            $query->andWhere("v.weight BETWEEN ".$filters["weight"][0]." AND ".$filters["weight"][1]);
        }

        if (isset($filters["power"][0]) && $filters["power"][0] !== null && $filters["power"][0] !== "" && isset($filters["power"][1]) && $filters["power"][1] !== null && $filters["power"][1] !== "" and $filters["power"][0] !== $filters["power"][1])
        {
            $query->andWhere("v.power BETWEEN ".$filters["power"][0]." AND ".$filters["power"][1]);
        }

        if (isset($filters["status"]) && $filters["status"] !== null && $filters["status"] !== "")
        {
            $query->andWhere("v.status = ".$filters["status"]);
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Vehicle[] Returns an array of Vehicle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vehicle
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
