<?php

namespace BuilderBundle\Listener;

use BuilderBundle\Exception\ExceptionCodeTranslator;
use BuilderBundle\Security\AuthenticationModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Translation\Translator;

/**
 * Class ExceptionListener
 *
 * @package ED\AppBundle\Listener
 */
class AuthenticationListener
{
    /** @var AuthenticationModel  */
    private $authenticationModel;

    /**
     * AuthenticationListener constructor.
     * @param AuthenticationModel $authenticationModel
     */
    public function __construct(AuthenticationModel $authenticationModel)
    {
        $this->authenticationModel = $authenticationModel;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $request = $event->getRequest();
        $user = $event->getAuthenticationToken()->getUser();

        $this->authenticationModel->addToken($request, $user);
    }
}