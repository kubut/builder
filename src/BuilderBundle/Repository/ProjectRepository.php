<?php
namespace BuilderBundle\Repository;

use BuilderBundle\Entity\Project;
use BuilderBundle\Exception\ExceptionCode;
use Doctrine\ORM\EntityRepository;

/**
 * Class ProjectRepository
 * @package BuilderBundle\Repository
 */
class ProjectRepository extends EntityRepository
{
    /**
     * @param Project $project
     */
    public function save(Project $project)
    {
        $this->_em->persist($project);
        $this->_em->flush();
        $this->_em->refresh($project);
    }

    /**
     * @return integer
     */
    public function getCountProjects()
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param integer $id
     *
     * @return array
     * @throws \Exception
     */
    public function fetchProjectById($id)
    {
        $projectData = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameters([
                'id' => $id,
            ])
            ->getQuery()
            ->getArrayResult();

        if (empty($projectData)) {
            throw new \Exception('Project does not exist', ExceptionCode::PROJECT_NOT_EXIST);
        }

        return $projectData[0];
    }

    /**
     * @param integer $id
     *
     * @return Project
     * @throws \Exception
     */
    public function findById($id)
    {
        $project = $this->find($id);
        if (is_null($project)) {
            throw new \Exception('Project does not exist', ExceptionCode::PROJECT_NOT_EXIST);
        }

        return $project;
    }

    /**
     * @param array $scope
     * @return array
     */
    public function fetchDataWithScope(array $scope)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id');

        foreach ($scope as $scopeItem) {
            $qb->addSelect('p.' . $scopeItem);
        }

        return $qb->getQuery()->getArrayResult();
    }
}