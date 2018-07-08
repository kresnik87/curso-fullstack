<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
    
    public function loginAction(Request $request){
        $helpers=$this->get('app.helpers');
        $jwt_auth=$this->get('app.jwt_auth');
        $json=$request->get("json",null);
        if($json!=null){
            $params= json_decode($json);
            $email=(isset($params->email))? $params->email:null;
            $password=(isset($params->password))? $params->password:null;
            $getHash=(isset($params->getHash))? $params->getHash:null;
            $emailConstrains= new Assert\Email();
            $emailConstrains->message="Email invalido";
            $validate_email=$this->get("validator")->validate($email,$emailConstrains);
            if(count($validate_email)==0 && $password!=null){
                if($getHash==null){
                $signup=$jwt_auth->signup($email,$password);
                }else{
                $signup=$jwt_auth->signup($email,$password,true);
                }
                return new JsonResponse($signup);
                 }else{
                return $helpers->json(array("status"=>"error","data"=>"Login Not valid"));
            }
            }else{
                return $helpers->json(array("status"=>"error","data"=>"Enviar datos por el post"));
            }
        //Recibir un json por POST
            die();
    }

    public function pruebasAction(Request $request)
    {
        $helpers=$this->get('app.helpers');
        $hash=$request->get('token',null);
        $check=$helpers->checkAuth($hash,true);
        var_dump($check);die();
        /*
        $em=$this->getDoctrine()->getManager();
        $users=$em->getRepository('BackendBundle:User')->findAll();
        return $helpers->json($users);*/
    }
   
}
