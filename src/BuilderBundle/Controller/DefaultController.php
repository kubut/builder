<?php

namespace BuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->render('BuilderBundle:Default:index.html.twig');
    }
}
