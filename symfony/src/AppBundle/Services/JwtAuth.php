<?php

namespace AppBundle\Services;
use Firebase\JWT\JWT;
class JwtAuth {
    public $manager;
    public function __construct($manager) {
       $this->manager=$manager;
    }
    
    public function signup($email,$password,$getHash=null){
        $key="clave secreta";
        $signup=false;
        $user=$this->manager->getReposity('BackendBundle:User')->findOneBy(
                array(
                    "email"=>$email,
                    "password"=>$password
                    )
                );
        if(is_object($user)){
            $signup=true;
        }
       if($signup==true){
           return array("status"=>"sucess","data"=>"Login Sucess");
       }else{
           return array("status"=>"error","data"=>"Login Failed");
       }
    }
}
