<?php
namespace BuilderBundle\Controller;

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


class UserController extends AbstractController
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
        $inputData = $this->parseRequest($request);
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

    /**
     * @Route("/user/delete/{id}", name="delete_user", options={"expose"=true})
     *
     * @Method("DELETE")
     *
     * @ApiDoc(
     *  description="user delete mock",
     *  requirements={
     *      {"name"="id", "dataType"="int", "requirement"="int", "description"="id"}
     *  }
     * )
     * @param integer $userId
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteUserAction($userId)
    {
        $this->requireRole(Role::ADMIN);
        $this->get('app.builder.service.user')->removeUser($userId);

        return $this->returnSuccess();
    }

    /**
     * @Route("/user/password/change", name="change_password", options={"expose"=true})
     *
     * @Method("PATCH")
     *
     * @ApiDoc(
     *  description="password change"
     * )
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function changePasswordAction(Request $request)
    {
        $this->requireRole(Role::USER);
        $inputData = $this->parseRequest($request);
        /** @var User $user */
        $user = $this->getUser();

        $this->get('app.builder.service.user')->changePassword($user, $inputData['password']);

        return $this->returnSuccess();
    }

    /**
     * @Route("/user/password/reset/{id}", name="reset_user_password", options={"expose"=true})
     *
     * @Method("PATCH")
     *
     * @ApiDoc(
     *  description="password reset",
     *  requirements={
     *      {"name"="id", "dataType"="int", "requirement"="int", "description"="id"}
     *  }
     * )
     * @param integer $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function resetPasswordAction($id)
    {
        $this->requireRole(Role::ADMIN);

        $this->get('app.builder.service.user')->resetPassword($id);

        return $this->returnSuccess();
    }
}