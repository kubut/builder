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


class ProjectController extends AbstractController
{
    /**
     * @Route("/files/databases", name="get_databases_files", options={"expose"=true})
     *
     * @Method("GET")
     *
     * @ApiDoc(
     *  description="Get all SQL files"
     * )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getSQLFilesAction()
    {
        $files = $this->container->get('app.builder.service.file_finder')->getFilesFromPath('sql');

        return $this->returnSuccess($files);
    }

    /**
     * @Route("/project/add", name="add_project", options={"expose"=true})
     *
     * @Method("PUT")
     *
     * @ApiDoc(
     *  description="creates new project entity"
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addProjectAction(Request $request)
    {
        $this->requireRole(Role::ADMIN);
        $inputData = $this->parseRequest($request);

        $response = $this->container->get('app.builder.model.project')->createProject($inputData);
        return $this->returnSuccess($response);
    }

    /**
     * @Route("/project/update/{id}", name="edit_project", options={"expose"=true})
     *
     * @Method("PUT")
     *
     * @ApiDoc(
     *  description="creates new project entity"
     * )
     * @param Request $request
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editProjectAction(Request $request, $id)
    {
        $this->requireRole(Role::ADMIN);
        $inputData = $this->parseRequest($request);

        $this->container->get('app.builder.model.project')->editProject($id, $inputData);
        return $this->returnSuccess();
    }

    /**
     * @Route("/project/details/{id}", name="project_details", options={"expose"=true})
     *
     * @Method("GET"),
     *  requirements={
     *      {"name"="id", "dataType"="int", "requirement"="int", "description"="id"}
     *  }
     *
     * @ApiDoc(
     *  description="creates new project entity"
     * )
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function projectDetailAction($id)
    {
        $this->requireRole(Role::ADMIN);

        $response = $this->container->get('app.builder.model.project')->getProjectDetails($id);
        return $this->returnSuccess($response);
    }
    /**
     * @Route("/project/delete/{id}", name="delete_project", options={"expose"=true})
     *
     * @Method("DELETE")
     *
     * @ApiDoc(
     *  description="project delete mock",
     *  requirements={
     *      {"name"="id", "dataType"="int", "requirement"="int", "description"="id"}
     *  }
     * )
     * @param integer $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteProjectAction($id)
    {
        $this->requireRole(Role::ADMIN);
        $this->get('app.builder.model.project')->removeProject($id);

        return $this->returnSuccess();
    }

    /**
     * @Route("/project/list", name="get_projects", options={"expose"=true})
     *
     * @Method("GET")
     *
     * @ApiDoc(
     *  description="Return project list"
     * )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function projectListAction()
    {
        $this->requireRole(Role::USER);
        $projects = $this->get('app.builder.model.project')->getMenuProjectList();

        return $this->returnSuccess($projects);
    }
}
