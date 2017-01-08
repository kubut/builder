<?php
namespace BuilderBundle\Model;

use BuilderBundle\Entity\Instance;
use BuilderBundle\Factory\InstanceFactory;
use BuilderBundle\Repository\InstanceRepository;

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
     * @param $portalUrl
     */
    public function __construct(
        InstanceFactory $instanceFactory,
        InstanceRepository $instanceRepository,
        DatabaseModel $databaseModel,
        ProjectModel $projectModel,
        ChecklistModel $checklistModel,
        UserModel $userModel,
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
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function create(array $params)
    {
        $projectId = $params['params']['projectId'];
        $instanceData = $params['params']['instance'];
        $user = $this->userModel->getUserById($params['userId']);
        $factoryParams = [
            'name' => $instanceData['name'],
            'branch' => $instanceData['branch'],
            'url' => 'http://'.$instanceData['name'].$this->portalUrl,
            'database' => $this->databaseModel->getById($instanceData['databaseId']),
            'project' => $this->projectModel->getProject($projectId),
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
    public function update(Instance $instance)
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

            $data[] = [
                'id' => $instance->getId(),
                'name' => $instance->getName(),
                'status' => $instance->getStatus(),
                'branchName' => $instance->getBranch(),
                'buildDate' => $instance->getBuildDate()->format('Y-m-d H:i:s'),
                'author' => $instance->getUser(),
                "url" => $instance->getUrl(),
                'checklist' => $checklists
            ];
        }

        return $data;
    }
}