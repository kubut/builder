<?php
namespace BuilderBundle\WebSocket\Channels\Instances\Actions;

use BuilderBundle\WebSocket\AsyncActions\CreateDatabaseAction;
use BuilderBundle\WebSocket\Services\DatabaseService;
use BuilderBundle\WebSocket\Services\InstanceService;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;
use BuilderBundle\WebSocket\Settings\BaseAction;
use Ratchet\ConnectionInterface;

/**
 * Class BaseInstanceAction
 * @package BuilderBundle\WebSocket\Channels\Instances\Actions
 */
abstract class BaseInstanceAction extends BaseAction implements ActionHandlerInterface
{
    /** @var InstanceService $instanceService */
    protected $instanceService;

    /**
     * BaseInstanceAction constructor.
     * @param InstanceService $instanceService
     */
    public function __construct(InstanceService $instanceService)
    {
        $this->instanceService = $instanceService;
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