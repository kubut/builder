<?php
namespace BuilderBundle\WebSocket\Channels\Instances\Actions;

use BuilderBundle\WebSocket\AsyncActions\CreateDatabaseAction;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;


/**
 * Class CreateAction
 * @package VM\NotesBundle\WebSocket\Action
 */
class SynchronizeAction extends BaseDatabaseAction implements ActionHandlerInterface
{
    const ACTION = 'synchronize';

    private $createActionParams = [
        'projectId',
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
        return $this->databaseService->getAllDatabases($params);
    }

    /**
     * @return boolean
     */
    public function hasAsyncJob()
    {
        return false;
    }
}