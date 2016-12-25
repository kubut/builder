<?php
namespace BuilderBundle\WebSocket\Channels\Databases\Actions\Server;

use BuilderBundle\WebSocket\Channels\Databases\Actions\BaseDatabaseAction;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;


/**
 * Class CreateAction
 * @package VM\NotesBundle\WebSocket\Action
 */
class ServerUpdateAction extends BaseDatabaseAction implements ActionHandlerInterface
{
    const ACTION = 'serverUpdate';
    const SERVER_USER_ID = 12;
    const SERVER_USER_TOKEN = 'abba';

    private $createActionParams = [
        'projectId',
        'databaseId',
        'status',
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
        return $this->databaseService->updateStatus($params);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function asyncAction(array $params)
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function hasAsyncJob()
    {
        return true;
    }
}