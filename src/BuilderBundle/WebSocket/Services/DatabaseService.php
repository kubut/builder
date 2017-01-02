<?php
namespace BuilderBundle\WebSocket\Services;

use BuilderBundle\Entity\Database;
use BuilderBundle\Entity\Project;
use BuilderBundle\Model\DatabaseModel;
use BuilderBundle\Model\ProjectModel;
use BuilderBundle\WebSocket\Channels\Databases\Actions\CreateAction;
use BuilderBundle\WebSocket\Channels\Databases\Actions\DeleteAction;
use BuilderBundle\WebSocket\Channels\Databases\Actions\Server\ServerDeleteAction;
use BuilderBundle\WebSocket\Channels\Databases\Actions\Server\ServerUpdateAction;
use BuilderBundle\WebSocket\Channels\Databases\Actions\SynchronizeAction;
use BuilderBundle\WebSocket\Settings\ServerCredentials;

/**
 * Class DatabaseService
 * @package BuilderBundle\WebSocket\Services
 */
class DatabaseService
{
    const CREATE_DATABASE_SCRIPT = "/../src/BuilderBundle/Scripts/createDatabase.sh";
    const DELETE_DATABASE_SCRIPT = "/../src/BuilderBundle/Scripts/deleteDatabase.sh";
    const DATABASES_LOCATION = "/../web/sql/";
    const DATABASE_PREFIX = 'bldrdb_';

    /** @var ProjectModel $projectModel */
    protected $projectModel;

    /** @var DatabaseModel $databaseModel */
    protected $databaseModel;

    private $kernelDir;

    /**
     * DatabaseService constructor.
     * @param ProjectModel $projectModel
     * @param DatabaseModel $databaseModel
     * @param string $kernelDir
     */
    public function __construct(
        ProjectModel $projectModel,
        DatabaseModel $databaseModel,
        $kernelDir
    )
    {
        $this->projectModel = $projectModel;
        $this->databaseModel = $databaseModel;
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
        /**
         * @var Database $database
         * @var Project $project
         */
        list($database, $project) = $this->createDatabaseInstanceForProject($params);

        $command = $this->createDatabaseCommand($database, $project->getSqlFile());

        return [
            'initResponse' => json_encode([
                'projectId' => $database->getProjectId(),
                'database' => [
                    'id' => $database->getId(),
                    'name' => $database->getName(),
                    'comment' => $database->getComment(),
                    'status' => $database->getStatus()
                ]
            ]),
            'command' => base64_encode($command),
            'successAction' => addslashes(json_encode($this->getAsyncUpdateResponse($database, true))),
            'errorAction' => addslashes(json_encode($this->getAsyncUpdateResponse($database, false))),
        ];
    }
    /**
     * @param $params
     * @return array
     *
     * @throws \Exception
     */
    public function delete($params)
    {
        /**
         * @var Database $database
         * @var Project $project
         */
        $database = $this->databaseModel->getById($params['params']['databaseId']);

        $command = $this->deleteDatabaseCommand($database);

        return [
            'command' => base64_encode($command),
            'successAction' => addslashes(json_encode($this->getAsyncDeleteResponse($database))),
            'errorAction' => addslashes(json_encode($this->getAsyncDeleteResponse($database))),
        ];
    }

    /**
     * @param $params
     * @return mixed
     */
    public function updateStatus($params)
    {
        $database = $this->databaseModel->getById($params['params']['databaseId']);
        $database->setStatus($params['params']['status']);
        $this->databaseModel->update($database);

        return [
            'initResponse' => json_encode([
                'action' => CreateAction::CREATE_ACTION,
                'projectId' => $database->getProjectId(),
                "database" => [
                    "id" => $database->getId(),
                    "name" => $database->getName(),
                    "comment" => $database->getComment(),
                    "status" => $database->getStatus()
                ]
            ])
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    public function deleteDatabase(array $params)
    {
        $this->databaseModel->delete($params['params']['databaseId']);

        return [
            'initResponse' => json_encode([
                'action' => DeleteAction::ACTION,
                "params" => [
                    'projectId' => $params['params']['projectId'],
                    'databaseId' => $params['params']['databaseId'],
                ]
            ])
        ];
    }
    /**
     * @param $params
     *
     * @return array
     */
    public function createDatabaseInstanceForProject($params)
    {
        $projectId = $params['params']['projectId'];
        $comment = $params['params']['comment'];
        $project = $this->projectModel->getProject($projectId);
        $name = strtoupper($project->getName()) . $projectId . rand(0, 255);

        /** @var Database $database */
        $database = $this->databaseModel->createDatabaseForProject($project, $name, $comment);

        return [
            $database,
            $project
        ];
    }

    /**
     * @param Database $database
     * @param $databaseFileName
     * @return string
     */
    public function createDatabaseCommand(Database $database, $databaseFileName)
    {
        $script = realpath($this->kernelDir . self::CREATE_DATABASE_SCRIPT);
        $databaseFile = realpath($this->kernelDir . self::DATABASES_LOCATION . $databaseFileName);
        $databaseName = self::DATABASE_PREFIX . strtoupper($database->getName()) . $database->getId();

        $command = implode(' ', [$script, $databaseName, $databaseFile]);

        return $command;
    }
    /**
     * @param Database $database
     * @return string
     */
    public function deleteDatabaseCommand(Database $database)
    {
        $script = realpath($this->kernelDir . self::DELETE_DATABASE_SCRIPT);

        $command = implode(' ', [$script, "bldrdb_".$database->getName().$database->getId()]);

        return $command;
    }

    /**
     * @param Database $database
     * @param bool $done
     * @return array
     */
    public function getAsyncUpdateResponse($database, $done = true)
    {
        $status = $done ? Database::STATUS_BAKED : Database::STATUS_BURNED;

        return [
            'action' => ServerUpdateAction::ACTION,
            'userId' => ServerCredentials::USER,
            'userToken' => ServerCredentials::PASS,
            'params' => [
                'projectId' => $database->getProjectId(),
                'databaseId' => $database->getId(),
                'status' => $status
            ]
        ];
    }

    public function getAllDatabases($params)
    {
        return [
            'initResponse' => json_encode([
                'action' => SynchronizeAction::ACTION,
                'params' => [
                    'projectId' => $params['params']['projectId'],
                    'databases' => $this->databaseModel->fetchAllDatabases($params['params']['projectId'])
                ]
            ])
        ];
    }

    /**
     * @param Database $database
     * @return array
     */
    public function getAsyncDeleteResponse($database)
    {
        return [
            'action' => ServerDeleteAction::ACTION,
            'userId' => ServerCredentials::USER,
            'userToken' => ServerCredentials::PASS,
            'params' => [
                'projectId' => $database->getProjectId(),
                'databaseId' => $database->getId(),
            ]
        ];
    }
}