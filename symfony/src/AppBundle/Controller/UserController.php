<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use BackendBundle\Entity\User;

class UserController extends Controller {

    public function newAction(Request $request) {
        $helpers = $this->get("app.helpers");
        $json = $request->get('json', null);
        $params = json_decode($json);
        $data = array("status" => "error", "code" => 400, "msg" => "User no created");

        if ($json != null) {
            $createdAt = new \DateTime("now");
            $image = null;
            $rol = "user";
            $email = (isset($params->email)) ? $params->email : null;
            $name = (isset($params->name) && ctype_alpha($params->name)) ? $params->name : null;
            $surname = (isset($params->surname) && ctype_alpha($params->surname)) ? $params->surname : null;
            $password = (isset($params->password)) ? $params->password : null;
            $emailConstrains = new Assert\Email();
            $emailConstrains->message = "Email invalido";
            $validate_email = $this->get("validator")->validate($email, $emailConstrains);
            if ($email != null && count($validate_email) == 0 && $password != null && $name != null && $surname != null
            ) {
                $user = new User();
                $user->setCreatedAt($createdAt);
                $user->setEmail($email);
                $user->setImage($image);
                $user->setName($name);
                $user->setPassword(hash('sha256', $password));
                $user->setSurname($surname);
                $user->setRole($rol);

                $em = $this->getDoctrine()->getManager();
                $isset_user = $em->getRepository("BackendBundle:User")->findBy(array("email" => $email));
                if (count($isset_user) == 0) {
                    $em->persist($user);
                    $em->flush();
                    $data["status"] = 'success';
                    $data["code"] = 200;
                    $data["msg"] = 'New user created!!!';
                } else {
                    $data = array("status" => "error", "code" => 400, "msg" => "User no created, duplicated!!");
                }
            }
        }
        return $helpers->json($data);
    }

    public function editAction(Request $request) {
       
        $helpers = $this->get("app.helpers");    //Llamando al servicio Heelpers
        $hash = $request->get("auth", null);    //Solicitando el hash del tocken
        $authCheck = $helpers->authCheck($hash);  
        if ($authCheck == true) {
            $identity=$helpers->checkAuth($hash,true);
            $em=$this->getDoctrine()->getManager();
            $user=$em->getRepository("BackendBundle:User")->findOneBy(array(
                "id"=>$identity->sub
            ));
            $json = $request->get('json', null);
            $params = json_decode($json);
            $data = array("status" => "error", "code" => 400, "msg" => "User no created");

            if ($json != null) {
                $createdAt = new \DateTime("now");
                $image = null;
                $rol = "user";
                $email = (isset($params->email)) ? $params->email : null;
                $name = (isset($params->name) && ctype_alpha($params->name)) ? $params->name : null;
                $surname = (isset($params->surname) && ctype_alpha($params->surname)) ? $params->surname : null;
                $password = (isset($params->password)) ? $params->password : null;
                $emailConstrains = new Assert\Email();
                $emailConstrains->message = "Email invalido";
                $validate_email = $this->get("validator")->validate($email, $emailConstrains);
                if ($email != null && count($validate_email) == 0 && $name != null && $surname != null
                ) {
                    $user->setCreatedAt($createdAt);
                    $user->setEmail($email);
                    $user->setImage($image);
                    $user->setName($name);
                    if($password!=NULL){
                    $user->setPassword(hash('sha256', $password));}
                    $user->setSurname($surname);
                    $user->setRole($rol);
                    $em = $this->getDoctrine()->getManager();
                    $isset_user = $em->getRepository("BackendBundle:User")->findBy(array("email" => $email));
                    if (count($isset_user) == 0 ||$identity->email==$email) {
                        $em->persist($user);
                        $em->flush();
                        $data["status"] = 'success';
                        $data["code"] = 200;
                        $data["msg"] = 'New user created!!!';
                    } else {
                        $data = array("status" => "error", "code" => 400, "msg" => "User no created, duplicated!!");
                    }
                }
            } else {
                $data = array("status" => "error", "code" => 400, "msg" => "User no created, duplicated!!");
            }
        }
        return $helpers->json($data);
    }

}
