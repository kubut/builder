<?php
namespace BuilderBundle\WebSocket\Channels\Instances\Actions\Server;

use BuilderBundle\WebSocket\Channels\Databases\Actions\BaseDatabaseAction;
use BuilderBundle\WebSocket\Channels\Instances\Actions\BaseInstanceAction;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;
use Ratchet\ConnectionInterface;


/**
 * Class ServerUpdateAction
 * @package BuilderBundle\WebSocket\Channels\Instanses\Actions\Server
 */
class ServerUpdateAction extends BaseInstanceAction implements ActionHandlerInterface
{
    const ACTION = 'serverStatus';

    private $createActionParams = [
        'projectId',
        'instanceId',
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
        return $this->instanceService->updateStatus($params);
    }

    /**
     * @return boolean
     */
    public function hasAsyncJob()
    {
        return true;
    }
    /**
     *
     * @param ConnectionInterface $userConnection
     * @param array $userConnections
     * @param integer $requestId
     * @param array $responseData
     */
    public function sendResponse($userConnection, array $userConnections, $requestId, $responseData)
    {
        /** @var ConnectionInterface $conn */
        foreach ($userConnections as $conn) {
            $conn->send($responseData['initResponse']);
        }
    }
}