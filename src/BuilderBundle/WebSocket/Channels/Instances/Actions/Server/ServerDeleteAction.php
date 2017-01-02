<?php
namespace BuilderBundle\WebSocket\Channels\Instances\Actions\Server;

use BuilderBundle\WebSocket\AsyncActions\CreateDatabaseAction;
use BuilderBundle\WebSocket\Channels\Databases\Actions\BaseDatabaseAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\BaseInstanceAction;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;


/**
 * Class ServerDeleteAction
 * @package BuilderBundle\WebSocket\Channels\Instanses\Actions\Server
 */
class ServerDeleteAction extends BaseInstanceAction implements ActionHandlerInterface
{
    const ACTION = 'serverDelete';

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
        return $this->instanceService->delete($params);
    }

    /**
     * @return boolean
     */
    public function hasAsyncJob()
    {
        return false;
    }
}