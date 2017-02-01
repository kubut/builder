<?php
namespace BuilderBundle\WebSocket\Services;

use BuilderBundle\Entity\Database;
use BuilderBundle\Entity\Instance;
use BuilderBundle\Entity\Project;
use BuilderBundle\Model\ChecklistModel;
use BuilderBundle\Model\DatabaseModel;
use BuilderBundle\Model\InstanceModel;
use BuilderBundle\Model\ProjectModel;
use BuilderBundle\Util\GitHelper;
use BuilderBundle\WebSocket\Channels\Instances\Actions\BuildAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\CreateAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\DeleteAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\Server\ServerDeleteAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\Server\ServerUpdateAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\Server\UpdateChecklistItem;
use BuilderBundle\WebSocket\Channels\Instances\Actions\ServerBuildAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\SynchronizeAction;
use BuilderBundle\WebSocket\Settings\ServerCredentials;

/**
 * Class InstanceService
 * @package BuilderBundle\WebSocket\Services
 */
class InstanceService
{
    const CREATE_INSTANCE_SCRIPT = "/../src/BuilderBundle/Scripts/createInstance.sh";
    const BUILD_INSTANCE_SCRIPT = "/../src/BuilderBundle/Scripts/buildInstance.sh";
    const NODE = "/../src/BuilderBundle/Scripts/ServersClient.js";
    const INSTANCES_LOCATION = "/../web/instances/";

    /** @var InstanceModel $instanceModel */
    protected $instanceModel;

    /** @var ChecklistModel $checklistModel */
    protected $checklistModel;

    /** @var string  */
    private $kernelDir;

    /** @var string  */
    private $portalUrl;

    /**
     * DatabaseService constructor.
     * @param InstanceModel $instanceModel
     * @param ChecklistModel $checklistModel
     * @param string $kernelDir
     * @param $portalUrl
     */
    public function __construct(
        InstanceModel $instanceModel,
        ChecklistModel $checklistModel,
        $kernelDir,
        $portalUrl
    )
    {
        $this->instanceModel = $instanceModel;
        $this->kernelDir = $kernelDir;
        $this->checklistModel = $checklistModel;
        $this->portalUrl = $portalUrl;
    }

    /**
     * @param $params
     * @return array
     *
     * @throws \Exception
     */
    public function create($params)
    {
        /** @var Instance $instance */
        $instance = $this->instanceModel->create($params);
        $command = $this->createCommand($instance);

        return [
            'initResponse' => json_encode([
                "action" => CreateAction::CREATE_ACTION,
                "params" => [
                    "projectId" => $instance->getProjectId(),
                    "instance" => [
                        "id" => $instance->getId(),
                        "name" => $instance->getName(),
                        "status" => $instance->getStatus(),
                        "branchName" => $instance->getBranch(),
                        "buildDate" => $instance->getBuildDate()->format('Y-m-d H:i:s'),
                        "author" => $instance->getUser(),
                        "url" => $instance->getUrl(),
                        "checklist" =>!is_null(($instance->getChecklistId())) ? $this->checklistModel->getChecklistPreviewById($instance->getChecklistId()) : []
                    ]
                ]
            ]),
            'command' => base64_encode($command),
            'successAction' => json_encode($this->getAsyncCreateResponse($instance)),
            'errorAction' => '',
        ];
    }

    /**
     * @param $params
     * @return array
     */
    public function build($params)
    {
        $instance = $this->instanceModel->update($params);
        $command = $this->buildCommand($instance);

        return [
            'initResponse' => json_encode([
                "action" => BuildAction::ACTION,
                "params" => [
                    "projectId" => $instance->getProjectId(),
                    "instance" => [
                        "id" => $instance->getId(),
                        "name" => $instance->getName(),
                        "status" => $instance->getStatus(),
                        "branchName" => $instance->getBranch(),
                        "buildDate" => $instance->getBuildDate()->format('Y-m-d H:i:s'),
                        "author" => $instance->getUser(),
                        "url" => $instance->getUrl(),
                        "checklist" =>!is_null(($instance->getChecklistId())) ? $this->checklistModel->getChecklistPreviewById($instance->getChecklistId()) : []
                    ]
                ]
            ]),
            'command' => base64_encode($command),
            'successAction' => json_encode($this->getAsyncCreateResponse($instance)),
            'errorAction' => '',
        ];
    }

    private function createCommand(Instance $instance)
    {
        $script = realpath($this->kernelDir . self::CREATE_INSTANCE_SCRIPT);
        $location = $this->kernelDir . self::INSTANCES_LOCATION;
        $nodeScript = $this->kernelDir.self::NODE;
        /** @var Project $project */
        $project = $instance->getProject();
        $gitURL = GitHelper::createSecureGitURL($project);
        $buildScript = $project->getInstallScript();
        $database = $instance->getDatabase();
        $projectDisplayName = strtolower(str_replace(' ', '', $project->getName()));
        $params = [
            'command' => $script,
            'instancesLocation' => $location,
            'projectId' => $instance->getProjectId(),
            'instanceId' => $instance->getId(),
            'gitURL' => $gitURL,
            'buildScript' => empty($buildScript) ? '/': $buildScript,
            'node' => $nodeScript,
            'instanceName' => $instance->getName()."-".$projectDisplayName.".".$this->portalUrl,
            'databaseName' => str_replace(' ', '', $database->getName()).$database->getId()
        ];

        $command = implode(' ', array_values($params));
        echo $command;

        return $command;
    }

    /**
     * @param Instance $instance
     * @return array
     */
    private function getAsyncCreateResponse($instance)
    {
        return [
            "action" => ServerUpdateAction::ACTION,
            'userId' => ServerCredentials::USER,
            'userToken' => ServerCredentials::PASS,
            "params" => [
                "projectId" => $instance->getProjectId(),
                "instanceId" => $instance->getId(),
                "status" => "5T4TU5"
            ]
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    public function updateStatus(array $params)
    {
        $instanceId = $params['params']['instanceId'];
        $status = $params['params']['status'];
        $instance = $this->instanceModel->getById($instanceId);
        $instance->setStatus($status);

        $this->instanceModel->save($instance);

        return [
            'initResponse' => json_encode([
                "action" => "update",
                "params" => [
                    "projectId" => $instance->getProjectId(),
                    "instanceId" => $instance->getId(),
                    "status" => $status
                ]]
            ),
        ];
    }

    /**
     * @param $params
     * @return array
     */

    public function delete($params)
    {
        return [];
    }

    /**
     * @param $params
     * @return array
     */
    public function getAllInstances(array $params)
    {
        $projectId = $params['params']['projectId'];
        $instances = $this->instanceModel->getByProjectId($projectId);

        return [
            'initResponse' => json_encode([
                    "action" => SynchronizeAction::ACTION,
                    "params" => [
                        "projectId" => $projectId,
                        "instances" => $instances
                    ]
                ]
            ),
        ];
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function updateChecklistItem(array $params)
    {
        $checkListItemId = $params['params']['itemId'];
        $isSolved = $params['params']['itemSolved'];

        $this->checklistModel->editChecklistItem($checkListItemId, $isSolved);

        return [
            'initResponse' => json_encode([
                    "action" => UpdateChecklistItem::ACTION,
                    "params" => [
                        "projectId" => $params['params']['itemId'],
                        "instanceId" => $params['params']['itemId'],
                        "checkListId" => $params['params']['itemId'],
                        "itemId" => $checkListItemId,
                        "itemSolved" => $isSolved,
                    ]]
            ),
        ];
    }

    /**
     * @param Instance $instance
     * @return string
     */
    private function buildCommand(Instance $instance)
    {
        $script = realpath($this->kernelDir . self::BUILD_INSTANCE_SCRIPT);
        $location = $this->kernelDir . self::INSTANCES_LOCATION;
        $nodeScript = $this->kernelDir.self::NODE;
        /** @var Project $project */
        $project = $instance->getProject();
        $buildScript = $project->getInstallScript();
        $database = $instance->getDatabase();
        $params = [
            'command' => $script,
            'instancesLocation' => $location,
            'projectId' => $instance->getProjectId(),
            'instanceId' => $instance->getId(),
            'gitBranch' => $instance->getBranch(),
            'buildScript' => empty($buildScript) ? '/': $buildScript,
            'node' => $nodeScript,
            'databaseName' => str_replace(' ', '', $database->getName()).$database->getId()
        ];

        $command = implode(' ', array_values($params));
        echo $command;

        return $command;
    }
}