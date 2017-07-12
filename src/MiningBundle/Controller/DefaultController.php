<?php

namespace MiningBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('MiningBundle:Default:index.html.twig');
    }
    /**
     * @Route("/mining", name="mining")
     */
    public function miningAction(Request $request)
    {
        return $this->render('MiningBundle:Mining:mining.html.twig');
    }
}
