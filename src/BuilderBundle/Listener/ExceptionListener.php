<?php

namespace BuilderBundle\Listener;

use BuilderBundle\Exception\ExceptionCodeTranslator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Translator;

/**
 * Class ExceptionListener
 *
 * @package ED\AppBundle\Listener
 */
class ExceptionListener
{
    /** @var Translator */
    private $translator;

    /** @var string */
    private $kernelEnvironment;

    /**
     * ExceptionListener constructor.
     *
     * @param Translator $translator
     * @param string $kernelEnvironment
     */
    public function __construct(Translator $translator, $kernelEnvironment)
    {
        $this->translator = $translator;
        $this->kernelEnvironment = $kernelEnvironment;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        list($code, $httpCode, $message) = $this->parseException($exception);

        $response = $this->getResponse($code, $httpCode, $message, $exception->getMessage());

        $event->setResponse($response);
    }

    /**
     * @param \Exception $exception
     *
     * @return array
     */
    private function parseException(\Exception $exception)
    {
        $messageCode = ExceptionCodeTranslator::getTranslatorCode($exception->getCode());
        $httpCode = Response::HTTP_BAD_REQUEST;
        $code = $exception->getCode();

        $message = $this->translator->trans($messageCode);


        return [$code, $httpCode, $message];
    }

    /**
     * @param integer $code
     * @param integer $httpCode
     * @param string $message
     * @param string $originalMessage
     *
     * @return JsonResponse
     */
    private function getResponse($code, $httpCode, $message, $originalMessage)
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
                'originalMessage' => $this->kernelEnvironment == 'dev' ? $originalMessage : ''
            ]
        ], $httpCode);
    }
}