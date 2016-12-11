<?php
namespace BuilderBundle\Controller;

use BuilderBundle\Entity\User;
use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Util\ParametersEncryptor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use BuilderBundle\Util\Role;


class ChecklistController extends AbstractController
{
    /**
     * @Route("/checklist/add/{projectId}", name="add_checklist", options={"expose"=true})
     *
     * @Method("POST"),
     *  requirements={
     *      {"name"="projectId", "dataType"="int", "requirement"="int", "description"="projectId"}
     *  }
     *
     * @ApiDoc(
     *  description="Create checklist"
     * )
     * @param Request $request
     * @param integer $projectId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addCheckListAction(Request $request, $projectId)
    {
        $this->requireRole(Role::USER);

        $inputData = $this->parseRequest($request);
        $project = $this->get('app.builder.repository.project')->findById($projectId);

        $data = $this->container->get('app.builder.model.checklist')->createChecklist($project, $inputData);

        return $this->returnSuccess($data);
    }


    /**
     * @Route("/checklist/edit/{id}", name="edit_checklist", options={"expose"=true})
     *
     * @Method("PUT"),
     *  requirements={
     *      {"name"="id", "dataType"="int", "requirement"="int", "description"="id"}
     *  }
     *
     * @ApiDoc(
     *  description="Return project list"
     * )
     * @param Request $request
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function checklistEditAction(Request $request, $id)
    {
        $this->requireRole(Role::USER);
        $inputData = $this->parseRequest($request);

        $this->get('app.builder.model.checklist')->editChecklist($id, $inputData);

        return $this->returnSuccess();
    }

    /**
     * @Route("/checklist/remove/{id}", name="delete_checklist", options={"expose"=true})
     *
     * @Method("DELETE")
     *
     * @ApiDoc(
     *  description="Return project list"
     * )
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function checklistRemoveAction($id)
    {
        $this->requireRole(Role::USER);

        $this->get('app.builder.model.checklist')->removeChecklist($id);

        return $this->returnSuccess();
    }

    /**
     * @Route("/checklist/list/{projectId}/{offset}/{limit}", name="get_checklists", options={"expose"=true})
     *
     * @Method("GET"),
     *  requirements={
     *      {"name"="projectId", "dataType"="int", "requirement"="int", "description"="projectId"},
     *      {"name"="offset", "dataType"="int", "requirement"="int", "description"="offset"},
     *      {"name"="limit", "dataType"="int", "requirement"="int", "description"="limit"}
     *  }
     *
     * @ApiDoc(
     *  description="Create checklist"
     * )
     * @param integer $projectId
     * @param integer $offset
     * @param integer $limit
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getCheckListAction($projectId, $offset, $limit)
    {
        $this->requireRole(Role::USER);

        $data = $this->container->get('app.builder.model.checklist')->getCheckListByProjectId($projectId, $offset, $limit);

        $response = new JsonResponse($data['checklists']);
        $response->headers->set('X-Total-Count', $data['count']);

        return $response;
    }

    /**
     * @Route("/checklist/{hash}/", name="preview_checklist", options={"expose"=true})
     *
     * @Method("GET"),
     *  requirements={
     *      {"name"="hash", "dataType"="string", "requirement"="string", "description"="hash8"}
     *  }
     *
     * @ApiDoc(
     *  description="Create checklist"
     * )
     * @param string $hash
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getCheckListPreviewAction($hash)
    {

        $id = ParametersEncryptor::decrypt($hash);

        $checklist = $this->get('app.builder.repository.checklist')->find($id);
        if (is_null($checklist)) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $data = $this->container->get('app.builder.model.checklist')->getChecklistPreviewById($id);


        return $this->render('@Builder/Default/checklist.html.twig', ['checklist' => $data]);
    }
}
