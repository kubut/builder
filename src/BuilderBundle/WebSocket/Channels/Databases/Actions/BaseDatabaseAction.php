<?php
namespace BuilderBundle\WebSocket\Channels\Databases\Actions;

use BuilderBundle\WebSocket\AsyncActions\CreateDatabaseAction;
use BuilderBundle\WebSocket\Services\DatabaseService;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;
use BuilderBundle\WebSocket\Settings\BaseAction;
use Ratchet\ConnectionInterface;

/**
 * Class BaseAction
 * @package BuilderBundle\WebSocket\Settings
 */
abstract class BaseDatabaseAction extends BaseAction implements ActionHandlerInterface
{
    /** @var DatabaseService $databaseService */
    protected $databaseService;

    /**
     * BaseDatabaseAction constructor.
     * @param DatabaseService $databaseService
     */
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
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