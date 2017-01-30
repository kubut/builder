<?php
namespace BuilderBundle\Model;

use BuilderBundle\Entity\Instance;
use BuilderBundle\Factory\InstanceFactory;
use BuilderBundle\Repository\InstanceRepository;
use BuilderBundle\Services\JiraService;

/**
 * Class InstanceModel
 */
class InstanceModel
{
    /** @var InstanceFactory $instanceFactory */
    private $instanceFactory;

    /** @var  InstanceRepository $instanceRepository */
    private $instanceRepository;

    /** @var DatabaseModel $databaseModel */
    private $databaseModel;

    /** @var ProjectModel $projectModel */
    private $projectModel;

    /** @var ChecklistModel $checklistModel */
    private $checklistModel;

    /** @var UserModel $userModel */
    private $userModel;

    /** @var  JiraService */
    private $jiraService;

    /** @var string */
    private $portalUrl;

    /**
     * InstanceModel constructor.
     * @param InstanceFactory $instanceFactory
     * @param InstanceRepository $instanceRepository
     * @param DatabaseModel $databaseModel
     * @param ProjectModel $projectModel
     * @param ChecklistModel $checklistModel
     * @param UserModel $userModel
     * @param JiraService $jiraService
     * @param $portalUrl
     */
    public function __construct(
        InstanceFactory $instanceFactory,
        InstanceRepository $instanceRepository,
        DatabaseModel $databaseModel,
        ProjectModel $projectModel,
        ChecklistModel $checklistModel,
        UserModel $userModel,
        JiraService $jiraService,
        $portalUrl
    )
    {
        $this->instanceFactory = $instanceFactory;
        $this->instanceRepository = $instanceRepository;
        $this->databaseModel = $databaseModel;
        $this->projectModel = $projectModel;
        $this->checklistModel = $checklistModel;
        $this->userModel = $userModel;
        $this->portalUrl = $portalUrl;
        $this->jiraService = $jiraService;
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function create(array $params)
    {
        $projectId = $params['params']['projectId'];
        $project = $this->projectModel->getProject($projectId);
        $projectDisplayName = strtolower(str_replace(' ', '', $project->getName()));
        $instanceData = $params['params']['instance'];
        $user = $this->userModel->getUserById($params['userId']);
        $factoryParams = [
            'name' => $instanceData['name'],
            'branch' => $instanceData['branch'],
            'url' => 'http://'.$instanceData['name']."-".$projectDisplayName.".".$this->portalUrl,
            'database' => $this->databaseModel->getById($instanceData['databaseId']),
            'project' => $project,
            'user' => sprintf('%s %s', $user->getName(), $user->getSurname())
        ];
        if (isset($instanceData['checklistId'])) {
            $factoryParams['checklist'] = $this->checklistModel->getById($instanceData['checklistId']);
        }
        if (isset($instanceData['jiraTaskSymbol'])) {
            $factoryParams['jiraTaskSymbol'] = $instanceData['jiraTaskSymbol'];
        }

        $instance = $this->instanceFactory->createFromArray($factoryParams);
        $this->instanceRepository->save($instance);

        return $instance;
    }

    /**
     * @param $instanceId
     * @return Instance
     * @throws \Exception
     */
    public function getById($instanceId)
    {
        return $this->instanceRepository->findById($instanceId);
    }

    /**
     * @param Instance $instance
     */
    public function save(Instance $instance)
    {
        $this->instanceRepository->save($instance);
    }

    /**
     * @param integer $projectId
     * @return array
     */
    public function getByProjectId($projectId)
    {
        $instances = $this->instanceRepository->findByProjectId($projectId);

        $data = [];
        /** @var Instance $instance */
        foreach ($instances as $instance) {
            $checklists = !is_null(($instance->getChecklistId())) ? $this->checklistModel->getChecklistPreviewById($instance->getChecklistId()) : [];
            $data[$instance->getId()] = [
                'id' => $instance->getId(),
                'name' => $instance->getName(),
                'status' => $instance->getStatus(),
                'branchName' => $instance->getBranch(),
                'buildDate' => $instance->getBuildDate()->format('Y-m-d H:i:s'),
                'author' => $instance->getUser(),
                "url" => $instance->getUrl(),
                'checklist' => $checklists,
            ];
            $jiraInfo = $this->jiraService->getTaskInfo($instance);
            if (!empty($jiraInfo)) {
                $data[$instance->getId()]["jiraInformation"] = $jiraInfo;
            }


        }

        return array_values($data);
    }

    /**
     * @param $params
     * @return Instance
     */
    public function update($params)
    { /** @var Instance $instance */
        $instance = $this->getById($params['params']['instance']['instanceId']);
        $instanceData = $params['params']['instance'];

        $updateParams = [
            'name' => $instanceData['name'],
            'branch' => $instanceData['branch'],
            'database' => $this->databaseModel->getById($instanceData['databaseId']),
        ];
        $updateParams['checklist'] = null;
        if (isset($instanceData['checklistId'])) {
            $updateParams['checklist'] = $this->checklistModel->getById($instanceData['checklistId']);
        }
        if (isset($instanceData['jiraTaskSymbol'])) {
            $updateParams['jiraTaskSymbol'] = $instanceData['jiraTaskSymbol'];
        }
        foreach ($updateParams as $name => $value) {
            $setMethodName = sprintf('set%s', ucfirst($name));
            if (method_exists($instance, $setMethodName)) {
                $instance->$setMethodName($value);
            }
        }

        $this->save($instance);

        return $instance;
    }
}