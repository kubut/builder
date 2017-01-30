<?php
namespace BuilderBundle\Controller;

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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        try {
            $this->requireOneOfRoles([Role::ADMIN, Role::USER]);
            $USER_ROLE = $this->isGranted(Role::ADMIN)? Role::ADMIN : Role::USER;
            /** @var User $user */
            $user = $this->getUser();
            $token = $request->getSession()->get('userToken');
            $userId = $user->getId();
            $websocketServer = "ws://".$this->getParameter('socket_host').":".$this->getParameter('socket_port');
            $databaseSocket = $websocketServer."/".$this->getParameter('socket_databases');
            $instancesSocket = $websocketServer."/".$this->getParameter('socket_instances');

            return $this->render('@Builder/Default/index.html.twig', [
                'USER_ROLE' => $USER_ROLE,
                'showModal' => $user->getActivated()? 'false' : 'true',
                'USER_ID' => $userId,
                'USER_TOKEN' => $token,
                'WS_DATABASES' => $databaseSocket,
                'WS_INSTANCES' => $instancesSocket,

            ]);
        } catch (\Exception $e) {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }
}
