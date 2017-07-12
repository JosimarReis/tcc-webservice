<?php

namespace RestServerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class MiningController extends FOSRestController
{
    /**
    * @Rest\Get("/api/teste")
    */
    public function indexAction()
    {
        return [
        ['page'=>'Inicio'],
        ['outro']
        ];
    }
    /**
    * @Rest\Get("/api/t")
    */
    public function postAction(Request $request)
    {
        
        //parse_str($request->getContent(), $teste);
        
        return ['oi'=>'mundo'];
    }
}