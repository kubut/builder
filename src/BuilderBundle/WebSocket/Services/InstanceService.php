<?php
namespace BuilderBundle\WebSocket\Services;

use BuilderBundle\Entity\Database;
use BuilderBundle\Entity\Instance;
use BuilderBundle\Entity\Project;
use BuilderBundle\Model\DatabaseModel;
use BuilderBundle\Model\InstanceModel;
use BuilderBundle\Model\ProjectModel;
use BuilderBundle\Util\GitHelper;
use BuilderBundle\WebSocket\Channels\Instances\Actions\CreateAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\DeleteAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\Server\ServerDeleteAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\Server\ServerUpdateAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\SynchronizeAction;
use BuilderBundle\WebSocket\Settings\ServerCredentials;

/**
 * Class InstanceService
 * @package BuilderBundle\WebSocket\Services
 */
class InstanceService
{
    const CREATE_INSTANCE_SCRIPT = "/../src/BuilderBundle/Scripts/createInstance.sh";
    const NODE = "/../src/BuilderBundle/Scripts/ServersClient.js";
    const INSTANCES_LOCATION = "/../web/instances/";

    /** @var InstanceModel $instanceModel */
    protected $instanceModel;

    private $kernelDir;

    /**
     * DatabaseService constructor.
     * @param InstanceModel $instanceModel
     * @param string $kernelDir
     */
    public function __construct(
        InstanceModel $instanceModel,
        $kernelDir
    )
    {
        $this->instanceModel = $instanceModel;
        $this->kernelDir = $kernelDir;
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
                        "url" => $instance->getUrl()
                    ]
                ]
            ]),
            'command' => base64_encode($command),
            'successAction' => addslashes(json_encode($this->getAsyncCreateResponse($instance))),
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
        $params = [
            'command' => $script,
            'instancesLocation' => $location,
            'projectId' => $instance->getProjectId(),
            'instanceId' => $instance->getId(),
            'gitURL' => $gitURL,
            'buildScript' => $buildScript,
            'node' => $nodeScript
        ];

        $command = implode(' ', array_values($params));

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

        $this->instanceModel->update($instance);

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
}