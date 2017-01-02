<?php
namespace BuilderBundle\WebSocket\Channels\Instances\Actions;

use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;


/**
 * Class DeleteAction
 * @package BuilderBundle\WebSocket\Channels\Databases\Actions
 */
class DeleteAction extends BaseInstanceAction implements ActionHandlerInterface
{
    const ACTION = 'delete';

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
        return true;
    }

    /**
     * @param \Ratchet\ConnectionInterface $userConnection
     * @param array $userConnections
     * @param int $requestId
     * @param array $responseData
     */
    public function sendResponse($userConnection, array $userConnections, $requestId, $responseData)
    {

    }
}