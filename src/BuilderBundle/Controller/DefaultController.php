<?php

namespace BuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use BuilderBundle\Util\Role;


class DefaultController extends AbstractController
{
    /**
     * @internal param Request $request
     *
     * @Route("/", name="main_page", options={"expose"=true})
     *
     * @Method("GET")
     *
     * @ApiDoc(
     *  description="Main page"
     * )
     * @internal param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // TODO: this is temporary (because frontend was blocked by backend) and should be reviewed
        try {
            $this->requireOneOfRoles(array(Role::ADMIN, Role::USER));
            return $this->render('@Builder/Default/index.html.twig');
        } catch (\Exception $e) {
            return $this->render('@Builder/Default/demo-login.html.twig');
//            return $this->redirectToRoute('fos_user_security_login');
        }
    }
}
