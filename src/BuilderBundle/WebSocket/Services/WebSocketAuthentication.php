<?php
namespace BuilderBundle\WebSocket\Services;
use BuilderBundle\Security\AuthenticationModel;
use BuilderBundle\WebSocket\Settings\ServerCredentials;

/**
 * Class WebSocketAuthentication
 * @package BuilderBundle\WebSocket\Services
 */
class WebSocketAuthentication
{
    /** @var AuthenticationModel  */
    protected $authenticationModel;

    public function __construct(AuthenticationModel $authenticationModel)
    {
        $this->authenticationModel = $authenticationModel;
    }

    /**
     * @param integer $userId
     * @param string  $token
     * @return array|bool
     */
    public function authenticate($userId, $token)
    {
        if ($userId == ServerCredentials::USER && $token == ServerCredentials::PASS) {
            return true;
        }

        return $this->authenticationModel->checkToken($userId, $token);
    }
}