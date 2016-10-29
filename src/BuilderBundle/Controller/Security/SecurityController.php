<?php

namespace BuilderBundle\Controller\Security;

use BuilderBundle\Controller\AbstractController;
use BuilderBundle\Util\Role;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends AbstractController
{
    /**
     * @internal param Request $request
     *
     * @Route("/user/add", name="register", options={"expose"=true})
     *
     * @Method("POST")
     *
     * @ApiDoc(
     *  description="register",
     *  requirements={
     *      {"name"="username", "dataType"="string", "description"="username"},
     *      {"name"="email", "dataType"="string", "description"="email"}
     *  },
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function registerAction(Request $request)
    {
        $this->requireRole(Role::ADMIN);

        $inputData = json_decode($request->getContent(), true);
        if ($request->isMethod('POST')){
            try {
                $generatedPassword = $this->get('app.builder.service.register')->register($inputData);

                return $this->returnSuccess(['password' => $generatedPassword]);
            } catch (\Exception $exception) {
                return $this->returnError($exception->getMessage());
            }
        }
    }
}
