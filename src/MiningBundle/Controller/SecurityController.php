<?php

namespace MiningBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;
use MiningBundle\Document\User;

class MFRuralController extends Controller
{
    private $users;

    public function __construct(){
        $users[] = new User('Josimar','josimar@gmail.com');
        $users[] = new User('Suzana','suzana@gmail.com');
    }

    /**
    * @Route("/api/security/get/user/register")
    */
    public function getUserRegister(Request $request){
        var_dump($request);
    }

    /**
    * @Route("/api/security/get/user/auth")
    */
    public function getUserAuth(Request $request){
        $user = new User();
        $user->setEmail($request->get('email'));

        $retorno = [
            'user' => $this->getUser($user)
        ];

       return new Response(
        json_encode($retorno, JSON_UNESCAPED_UNICODE),
        201,
         array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json; charset=utf-8;')
       );

    }

    private function getUser(User $user){
        $retorno = new User();
        foreach($this->users as $u){
            if($u->getEmail() == $user->getEmail()){
                $retorno = $u;
            }
        }
        return $retorno;
    }

}