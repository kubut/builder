<?php
namespace BuilderBundle\Repository;

use BuilderBundle\Entity\Instance;
use BuilderBundle\Exception\ExceptionCode;
use Doctrine\ORM\EntityRepository;

/**
 * Class InstanceRepository
 * @package BuilderBundle\Repository
 */
class InstanceRepository extends AbstractRepository
{
    /**
     * @param Instance $instance
     */
    public function save(Instance $instance)
    {
        $this->refresh();

        $this->_em->persist($instance);
        $this->_em->flush();
        $this->_em->refresh($instance);
    }

    /**
     * @param integer $instanceId
     * @return Instance
     * @throws \Exception
     */
    public function findById($instanceId)
    {
        $this->refresh();

        $instance = $this->find($instanceId);
        if (is_null($instance)) {
            throw new \Exception('Instance item does not exist', ExceptionCode::INSTANCE_NOT_EXIST);
        }

        return $instance;
    }

    /**
     * @param integer $projectId
     * @return Instance
     */
    public function findByProjectId($projectId)
    {
        $this->refresh();

        return $this->createQueryBuilder('i')
            ->where('i.projectId = :projectId')
            ->setParameter('projectId', $projectId)
            ->getQuery()
            ->getResult();
    }
}