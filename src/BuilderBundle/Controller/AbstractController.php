<?php
namespace BuilderBundle\Controller;

use BuilderBundle\Exception\ExceptionCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractController
 *
 * @package BuilderBundle\Controller
 */
abstract class AbstractController extends Controller
{
    /**
     * @param array $data
     * @return JsonResponse
     */
    public function returnSuccess(array $data = [])
    {
        return new JsonResponse($data);
    }

    /**
     * @param string $message
     * @param int $code
     * @param int $httpCode
     * @return JsonResponse
     */
    public function returnError($message, $code = 0, $httpCode = Response::HTTP_BAD_REQUEST)
    {
        return new JsonResponse([
            'error' => [
                'message' => $message,
                'code' => $code
            ]
        ], $httpCode);
    }

    /**
     * @param Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function parseRequest(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        if (is_null($request)) {
            throw new \Exception('Invalid json strong', ExceptionCode::INVALID_JSON);
        }

        return $requestData;
    }

    /**
     * @param string $role
     * @throws \Exception
     */
    protected function requireRole($role)
    {
        if (!$this->isGranted($role)) {
            throw new \Exception('permission denied', ExceptionCode::PERMISSION_DENIED);
        }
    }

    /**
     * @param array $roles
     * @return bool
     * @throws \Exception
     */
    protected function requireOneOfRoles(array $roles)
    {
        foreach ($roles as $role) {
            if ($this->isGranted($role)) {
                return true;
            }
        }

        throw new \Exception('permission denied', ExceptionCode::PERMISSION_DENIED);
    }
}