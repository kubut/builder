<?php
namespace BuilderBundle\Controller;

use BuilderBundle\Entity\Project;
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


class BuildController extends AbstractController
{
    /**
     * @Route("/build/{projectId}", name="get_build_options", options={"expose"=true})
     *
     * @Method("GET"),
     *  requirements={
     *      {"name"="projectId", "dataType"="int", "requirement"="int", "description"="projectId"}
     *  }
     *
     * @ApiDoc(
     *  description="Build options"
     * )
     * @param integer $projectId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($projectId)
    {
        /** @var Project $project */
        $project = $this->get('app.builder.repository.project')->find($projectId);
        $checklists = $this->get('app.builder.model.checklist')->fetchAllCheckLists($project->getId());
        $databases = $this->get('app.builder.model.database')->fetchAllDatabases($project->getId());
        $branches = $this->get('app.builder.model.project')->fetchAllBranchesFromURL($project);


        return $this->returnSuccess([
            "checklists" => $checklists,
            "jiraTasks" => [],
            "branches" => $branches,
            "databases" => $databases
        ]);
    }
}
