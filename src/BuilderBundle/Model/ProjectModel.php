<?php
namespace BuilderBundle\Model;

use BuilderBundle\Entity\Project;
use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Factory\ProjectFactory;
use BuilderBundle\Repository\ProjectRepository;
use BuilderBundle\Util\GitHelper;

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

    private $kernelDir;

    /**
     * ProjectService constructor.
     * @param ProjectFactory $projectFactory
     * @param ProjectRepository $projectRepository
     * @param $kernelDir
     */
    public function __construct(
        ProjectFactory $projectFactory,
        ProjectRepository $projectRepository,
        $kernelDir
    )
    {
        $this->projectFactory = $projectFactory;
        $this->projectRepository = $projectRepository;
        $this->kernelDir = $kernelDir;
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

    /**
     * @param Project $project
     * @return array
     */
    public function fetchAllBranchesFromURL($project)
    {
        $gitURL = GitHelper::createSecureGitURL($project);

        $branches = [];
        $output = [];
        exec($this->kernelDir.'/../src/BuilderBundle/Scripts/branches.sh '.$gitURL." 2>&1", $branches, $output);

        return $branches;
    }
}