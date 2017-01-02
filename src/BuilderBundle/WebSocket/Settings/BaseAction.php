<?php
namespace BuilderBundle\WebSocket\Settings;

use Ratchet\ConnectionInterface;
use Thread;

/**
 * Class BaseAction
 * @package BuilderBundle\WebSocket\Settings
 */
abstract class BaseAction  implements ActionHandlerInterface
{
    /**
     * @param string $action
     *
     * @return bool
     */
    public function check($action)
    {
        return false;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function run(array $params)
    {
        return [];
    }

    /**
     * @return array
     */
    public function getActionParams()
    {
        return [];
    }

    /**
     * @param ConnectionInterface $userConnection
     * @param array $userConnections
     * @param integer $requestId
     * @param array $responseData
     */
    public function sendResponse($userConnection, array $userConnections, $requestId, $responseData)
    {
    }
}