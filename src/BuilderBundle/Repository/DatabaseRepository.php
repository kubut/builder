<?php
namespace BuilderBundle\Repository;

use BuilderBundle\Entity\Database;
use BuilderBundle\Exception\ExceptionCode;
use Doctrine\ORM\EntityRepository;

/**
 * Class DatabaseRepository
 * @package BuilderBundle\Repository
 */
class DatabaseRepository extends EntityRepository
{
    /**
     * @param Database $database
     */
    public function save(Database $database)
    {
        $this->_em->persist($database);
        $this->_em->flush();
        $this->_em->refresh($database);
    }

    /**
     * @param Database $database
     */
    public function persist(Database $database)
    {
        $this->_em->persist($database);
    }

    /**
     * @param integer $id
     *
     * @return Database
     * @throws \Exception
     */
    public function findById($id)
    {
        $database = $this->find($id);
        if (is_null($database)) {
            throw new \Exception('Database item does not exist', ExceptionCode::DATABASE_NOT_EXIST);
        }

        return $database;
    }

    public function fetchAllDatabasesForProject($projectId)
    {
        return $this->createQueryBuilder('d')
            ->select('d.id, d.name, d.comment, d.status')
            ->where('d.projectId = :projectId')
            ->setParameter('projectId', $projectId)
            ->getQuery()
            ->getArrayResult();
    }

    public function delete($database)
    {
        $this->_em->remove($database);
        $this->_em->flush();
    }
}