<?php
namespace BuilderBundle\Model;

use BuilderBundle\Entity\Project;
use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Factory\ProjectFactory;
use BuilderBundle\Repository\ProjectRepository;

/**
 * Class ProjectModel
 * @package BuilderBundle\Services
 */
class ProjectModel
{
    /** @var ProjectFactory */
    private $projectFactory;

    /** @var ProjectRepository  */
    private $projectRepository;

    /**
     * ProjectService constructor.
     * @param ProjectFactory $projectFactory
     * @param ProjectRepository $projectRepository
     */
    public function __construct(
        ProjectFactory $projectFactory,
        ProjectRepository $projectRepository
    )
    {
        $this->projectFactory = $projectFactory;
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param array $params
     *
     * @return array array
     * @throws \Exception
     */
    public function createProject(array $params)
    {
        if ($this->projectRepository->getCountProjects() >= 4) {
            throw new \Exception('You have exceeded project count limit', ExceptionCode::VALIDATION_PROJECT_TOO_MANY);
        }

        $project = $this->projectFactory->createFromArray($params);
        $this->projectRepository->save($project);

        return ['id' => $project->getId()];
    }

    /**
     * @param integer $id
     *
     * @return array
     * @throws \Exception
     */
    public function getProjectDetails($id)
    {
        $projectData = $this->projectRepository->fetchProjectById($id);

        return $projectData;
    }
    /**
     * @param integer $id
     *
     * @return Project
     * @throws \Exception
     */
    public function getProject($id)
    {
        $project = $this->projectRepository->findById($id);

        return $project;
    }

    /**
     * @param integer $id
     * @param array $params
     *
     * @throws \Exception
     */
    public function editProject($id, array $params)
    {
        $this->projectFactory->validateParams($params);
        $project = $this->projectRepository->findById($id);
        foreach ($params as $name => $value) {
            $setMethodName = sprintf('set%s', ucfirst($name));
            if (method_exists($project, $setMethodName)) {
                $project->$setMethodName($value);
            }
        }
        $this->projectRepository->save($project);
    }

    /**
     * @param $id
     */
    public function removeProject($id)
    {
        //TODO: remove mock
    }

    /**
     * @return array
     */
    public function getMenuProjectList()
    {
        $scope = ['name'];
        return $this->projectRepository->fetchDataWithScope($scope);
    }
}