<?php
namespace BuilderBundle\Repository;

use BuilderBundle\Entity\Checklist;
use BuilderBundle\Entity\Project;
use BuilderBundle\Exception\ExceptionCode;
use Doctrine\ORM\EntityRepository;

/**
 * Class ChecklistRepository
 * @package BuilderBundle\Repository
 */
class ChecklistRepository extends AbstractRepository
{
    /**
     * @param Checklist $checklist
     */
    public function save(Checklist $checklist)
    {
        $this->refresh();

        $this->_em->persist($checklist);
        $this->_em->flush();
        $this->_em->refresh($checklist);
    }

    /**
     * @param integer $id
     *
     * @return Checklist
     * @throws \Exception
     */
    public function findById($id)
    {
        $this->refresh();

        $checklist = $this->find($id);
        if (is_null($checklist)) {
            throw new \Exception('Checklist does not exist', ExceptionCode::CHECKLIST_NOT_EXIST);
        }

        return $checklist;
    }

    /**
     * @param integer $id
     * @param array $scope
     * @return array
     */
    public function fetchDataWithScope($id, array $scope)
    {
        $this->refresh();

        $qb = $this->createQueryBuilder('cl')
            ->select('cl.id')
            ->where('cl.id = :id')
            ->setParameter('id', $id);

        foreach ($scope as $scopeItem) {
            $qb->addSelect('cl.' . $scopeItem);
        }

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param integer $id
     */
    public function remove($id)
    {
        $this->refresh();

        $checklist = $this->findById($id);
        $this->_em->remove($checklist);
        $this->_em->flush();
    }

    /**
     * @param integer $id
     * @param integer $offset
     * @param integer $limit
     *
     * @return array
     */
    public function fetchChecklistByProjectId($id, $offset, $limit)
    {
        $this->refresh();

        return $this->createQueryBuilder('cl')
            ->select('cl.id, cl.name')
            ->where('cl.projectId = :id')
            ->setParameter('id', $id)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param integer $projectId
     *
     * @return mixed
     */
    public function countChecklistsForProject($projectId)
    {
        $this->refresh();

        return $this->createQueryBuilder('cl')
            ->select('count(cl.id)')
            ->where('cl.projectId = :id')
            ->setParameter('id', $projectId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $id
     *
     * @return bool|\Doctrine\Common\Proxy\Proxy|null|object
     */
    public function getReference($id)
    {
        $this->refresh();

        return $this->_em->getReference($this->_entityName, $id);
    }

    /**
     * @param integer $projectId
     * @param array $scope
     * @return array
     */
    public function fetchAllForProjectId($projectId, array $scope)
    {
        $this->refresh();

        $qb = $this->createQueryBuilder('cl')
            ->select('cl.id')
            ->where('cl.projectId = :projectId')
            ->setParameter('projectId', $projectId);;
        
        foreach ($scope as $scopeItem) {
            $qb->addSelect('cl.' . $scopeItem);
        }

        return $qb->getQuery()->getArrayResult();
    }
}