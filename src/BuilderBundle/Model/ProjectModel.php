<?php
namespace BuilderBundle\Model;

use BuilderBundle\Entity\Project;
use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Factory\ProjectFactory;
use BuilderBundle\Repository\ProjectRepository;
use BuilderBundle\Util\GitHelper;
use Symfony\Component\Yaml\Yaml;

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
        $projectId = $project->getId();
        $this->saveConfigFile($projectId, $params);


        return ['id' => $projectId];
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
        $this->saveConfigFile($id, $params);
        $project = $this->projectRepository->findById($id);
        $this->update($project, $params);
    }

    /**
     * @param Project $project
     * @param $params
     */
    public function update(Project $project, $params)
    {
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

    /**
     * @param integer $projectId
     * @param array $data
     */
    private function saveConfigFile($projectId, $data)
    {
        if (isset($data['configScript'])) {
            $directory = $this->kernelDir.'/../web/instances/config/'.$projectId.'/';
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            $filename = $this->isYaml($data) ? 'parameters.yml' : 'database.php';
            file_put_contents($directory.$filename, $data);
        }
    }

    /**
     * @param string $yaml
     * @return bool
     */
    private function isYaml($yaml)
    {
        return strpos($yaml, 'parameters:') !== false;
    }
}