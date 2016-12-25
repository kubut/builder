<?php
namespace BuilderBundle\WebSocket\Settings;

use BuilderBundle\WebSocket\AsyncActions\CreateDatabaseAction;
use Ratchet\ConnectionInterface;

interface ActionHandlerInterface
{
    /**
     * @param string $action
     *
     * @return bool
     */
    public function check($action);

    /**
     * @param array $params
     *
     * @return array
     */
    public function run(array $params);

    /**
     * @return array
     */
    public function getActionParams();

    /**
     * @param ConnectionInterface $userConnection
     * @param array $userConnections
     * @param integer $requestId
     * @param array $responseData
     */
    public function sendResponse($userConnection, array $userConnections, $requestId, $responseData);

    /**
     * @return boolean
     */
    public function hasAsyncJob();
}