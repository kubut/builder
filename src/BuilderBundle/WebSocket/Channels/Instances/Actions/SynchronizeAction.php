<?php
namespace BuilderBundle\WebSocket\Channels\Instances\Actions;

use BuilderBundle\WebSocket\AsyncActions\CreateDatabaseAction;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;


/**
 * Class SynchronizeAction
 * @package BuilderBundle\WebSocket\Channels\Instances\Actions
 */
class SynchronizeAction extends BaseInstanceAction implements ActionHandlerInterface
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
        return $this->instanceService->getAllInstances($params);
    }

    /**
     * @return boolean
     */
    public function hasAsyncJob()
    {
        return false;
    }
}