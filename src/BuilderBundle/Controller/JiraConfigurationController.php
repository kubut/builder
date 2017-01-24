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

/**
 * Class JiraConfigurationController
 * @package BuilderBundle\Controller
 */
class JiraConfigurationController extends AbstractController
{
    /**
     * @Route("/jira/get/{projectId}", name="get_jira_configuration", options={"expose"=true})
     *
     * @Method("GET"),
     *  requirements={
     *      {"name"="projectId", "dataType"="int", "requirement"="int", "description"="projectId"}
     *  }
     *
     * @ApiDoc(
     *  description="Main page"
     * )
     * @param Request $request
     * @param $projectId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getConfigurationAction(Request $request, $projectId)
    {
        $this->requireRole(Role::ADMIN);

        $data = $this->get('app.builder.service.jira')->getJiraConfiguration($projectId);

        return $this->returnSuccess($data);
    }

    /**
     * @Route("/jira/save/{projectId}", name="save_jira_configuration", options={"expose"=true})
     *
     * @Method("POST"),
     *  requirements={
     *      {"name"="projectId", "dataType"="int", "requirement"="int", "description"="projectId"}
     *  }
     *
     * @ApiDoc(
     *  description="Main page"
     * )
     * @param Request $request
     * @param $projectId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveConfigurationAction(Request $request, $projectId)
    {
        $this->requireRole(Role::ADMIN);
        $inputData = $this->parseRequest($request);
        $this->get('app.builder.service.jira')->saveJiraConfiguration($projectId, $inputData);

        return $this->returnSuccess();
    }
}
