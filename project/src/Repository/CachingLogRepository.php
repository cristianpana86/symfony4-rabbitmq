<?php

namespace CPANA\App\Repository;

use CPANA\App\Entity\CachingLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CachingLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method CachingLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method CachingLog[]    findAll()
 * @method CachingLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CachingLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CachingLog::class);
    }

    /**
     * Fetches rows from caching_log table where id greater than X
     * @param $lastRow
     * @return mixed
     */
    public function getRecordsAfterLastRow($lastRow)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'SELECT * FROM caching_log WHERE id > :lastRow';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['lastRow' => $lastRow]);
        $results = $stmt->fetchAll();
        if(!is_array($results)) {
            return [];
        } else {
            return $results;
        }
    }

    /**
     * Count rows frim caching_log table
     * @return int
     */
    public function countRows(): int
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'SELECT count(id) FROM caching_log';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Delete all info from DB
     */
    function cleanPreviousData()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $conn->executeQuery("SET FOREIGN_KEY_CHECKS = 0");
        $conn->executeQuery("TRUNCATE image");
        $conn->executeQuery("TRUNCATE record");
        $conn->executeQuery("TRUNCATE caching_log");
        $conn->executeQuery("SET FOREIGN_KEY_CHECKS = 1");
    }
}
