<?php
namespace BuilderBundle\WebSocket\Channels\Instances\Actions\Server;

use BuilderBundle\WebSocket\AsyncActions\CreateDatabaseAction;
use BuilderBundle\WebSocket\Channels\Databases\Actions\BaseDatabaseAction;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;


/**
 * Class ServerDeleteAction
 * @package BuilderBundle\WebSocket\Channels\Instanses\Actions\Server
 */
class ServerDeleteAction extends BaseDatabaseAction implements ActionHandlerInterface
{
    const ACTION = 'serverDelete';
    const SERVER_USER_ID = 12;
    const SERVER_USER_TOKEN = 'abba';

    private $createActionParams = [
        'projectId',
        'databaseId',
    ];

    /**
     * @param string $action
     *
     * @return bool
     */
    public function check($action)
    {
        return $action === self::ACTION;
    }

    /**
     * @return array
     */
    public function getActionParams()
    {
        return $this->createActionParams;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function run(array $params)
    {
        return $this->databaseService->deleteDatabase($params);
    }

    /**
     * @return boolean
     */
    public function hasAsyncJob()
    {
        return false;
    }
}