<?php
namespace BuilderBundle\WebSocket\Channels\Databases\Actions;

use BuilderBundle\WebSocket\AsyncActions\CreateDatabaseAction;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;


/**
 * Class CreateAction
 * @package VM\NotesBundle\WebSocket\Action
 */
class CreateAction extends BaseDatabaseAction implements ActionHandlerInterface
{
    const CREATE_ACTION = 'create';

    private $createActionParams = [
        'projectId',
        'comment',
    ];

    /**
     * @param string $action
     *
     * @return bool
     */
    public function check($action)
    {
        return $action === self::CREATE_ACTION;
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
        return $this->databaseService->create($params);
    }

    /**
     * @return boolean
     */
    public function hasAsyncJob()
    {
        return true;
    }
}