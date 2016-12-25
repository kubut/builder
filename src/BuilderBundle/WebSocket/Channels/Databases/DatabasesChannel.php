<?php
namespace BuilderBundle\WebSocket\Channels\Databases;

use BuilderBundle\AsyncAction\Async;
use BuilderBundle\Command\ScriptRunnerCommand;
use BuilderBundle\Entity\Database;
use BuilderBundle\WebSocket\Channels\Databases\Actions\Server\ServerUpdateAction;
use BuilderBundle\WebSocket\Services\DatabaseService;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;
use BuilderBundle\WebSocket\Settings\ParamsValidator;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Acl\Exception\Exception;

class DataBasesChannel implements MessageComponentInterface
{
    /** @var DatabaseActionHandlerFactory */
    protected $actionHandler;
    /** @var ParamsValidator $paramsValidator */
    protected $paramsValidator;

    private $connections = [];

    public function __construct(
        DatabaseActionHandlerFactory $actionHandler,
        ParamsValidator $paramsValidator
    )
    {
        $this->actionHandler = $actionHandler;
        $this->paramsValidator = $paramsValidator;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection)
    {
        echo "New connection " . $connection->resourceId . "\n";
    }

    /**
     * @param ConnectionInterface $userConnection
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $userConnection, $msg)
    {
        echo $msg;
        $data = $this->paramsValidator->parseRequest($msg);
        echo "d`ta->";
        $requestId = $this->paramsValidator->getRequestId($data);
        try {
            $this->paramsValidator->validateParams($data);
            $userId = $data['userId'];
            $token = $data['userToken'];
            $action = $data['action'];
//
////            if (!$this->authenticateModel->authenticate($userId, $token)) {
////                throw new NotesException('Authentication error', NotesExceptionCodes::AUTHENTICATION_ERROR);
////            }
            /** @var ActionHandlerInterface $action */
            $action = $this->actionHandler->factory($action);
            $this->paramsValidator->validateParams($data['params'], $action->getActionParams());
            $projectId = $data['params']['projectId'];
            $this->connections[$projectId][] = $userConnection;
            $responseData = $action->run($data);
            $action->sendResponse($userConnection,$this->connections[$projectId], $requestId,$responseData);
            $this->asyncAction($action, $responseData);
        } catch (Exception $e) {
            $userConnection->send("dwadaw");
        }
    }

    /**
     * @param ConnectionInterface $userConnection
     */
    public function onClose(ConnectionInterface $userConnection)
    {
        echo "Closed connection " . $userConnection->resourceId . "\n";
        $userConnection->close();
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     * @param integer $requestId
     * @return array
     */
    public function onError(ConnectionInterface $conn, \Exception $e, $requestId = null)
    {
        $conn->send($e->getMessage());

        $conn->close();
    }

    /**
     * @param ActionHandlerInterface $action
     * @param $params
     * @throws \Exception
     */
    private function asyncAction($action, $params)
    {

        if ($action->hasAsyncJob()) {
                $output = [];
                $return_var = -1;
                exec(base64_decode($params['command'])." ".$params['successAction']." ".$params['errorAction']. " > /dev/null 2>/dev/null &", $output, $return_var);

                if ($return_var !== 0) {
                    throw new \Exception(implode("\n", $output));
                }
        }

}}