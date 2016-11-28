<?php
namespace BuilderBundle\Controller\Security;

use BuilderBundle\Controller\AbstractController;
use BuilderBundle\Entity\User;
use BuilderBundle\Exception\ExceptionCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use BuilderBundle\Util\Role;


class AdminController extends AbstractController
{
    /**
     * @Route("/user/add", name="user_add", options={"expose"=true})
     *
     * @Method("POST")
     *
     * @ApiDoc(
     *  description="register",
     *  requirements={
     *      {"name"="name", "dataType"="string", "description"="username"},
     *      {"name"="surname", "dataType"="string", "description"="username"},
     *      {"name"="email", "dataType"="string", "description"="email"},
     *      {"name"="role", "dataType"="bool", "requirement"="bool", "description"="isAdmin"}
     *  },
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function registerAction(Request $request)
    {
        $this->requireRole(Role::ADMIN);
        $responseData = [];
        $inputData = json_decode($request->getContent(), true);
        if ($request->isMethod('POST')){
            try {
                $generatedPassword = $this->get('app.builder.service.register')->register($inputData);

                $responseData = $this->returnSuccess(['password' => $generatedPassword]);
            } catch (\Exception $exception) {
                return $this->returnError($exception->getMessage());
            }
        }

        return $this->returnSuccess($responseData);
    }

    /**
     * @Route("/user/list", name="get_users", options={"expose"=true})
     *
     * @Method("GET")
     *
     * @ApiDoc(
     *  description="user list"
     * )
     * @return JsonResponse
     */
    public function userListAction()
    {
        $this->requireRole(Role::ADMIN);

        /** @var User $user */
        $user = $this->getUser();

        $data = $this->get('app.builder.service.user')->getAllUsers($user->getId());

        return $this->returnSuccess($data);
    }
}