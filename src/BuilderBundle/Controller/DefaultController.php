<?php
namespace BuilderBundle\Controller;

use BuilderBundle\Exception\ExceptionCode;
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
     * @Route("/", name="main_page", options={"expose"=true})
     *
     * @Method("GET")
     *
     * @ApiDoc(
     *  description="Main page"
     * )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        try {
            $this->requireOneOfRoles([Role::ADMIN, Role::USER]);
            $USER_ROLE = $this->isGranted(Role::ADMIN)? Role::ADMIN : Role::USER;

            return $this->render('@Builder/Default/index.html.twig', ['USER_ROLE' => $USER_ROLE]);
        } catch (\Exception $e) {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }
}
