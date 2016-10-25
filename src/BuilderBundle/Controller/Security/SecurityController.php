<?php

namespace BuilderBundle\Controller\Security;

use BuilderBundle\Controller\AbstractController;
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
     * @Route("/register", name="register", options={"expose"=true})
     *
     * @Method("GET")
     *
     * @ApiDoc(
     *  description="register"
     * )
     * @internal param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        return $this->render('BuilderBundle:Default:index.html.twig');
    }
}
