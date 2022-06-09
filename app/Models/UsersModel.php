<?php
namespace App\Models;

class UsersModel extends Model{


    public function getUser($data,$one=false){

        $data=$this->prepare('SELECT * FROM users WHERE '.key($data).'=:'.key($data),$data);
        if($one){
            $array=$data->fetch();
            $user=$array;
        }
        else{
            $array=$data->fetchAll();
            $user=$array;
        }
        $data->closeCursor();
        return $user;
    }
    public function getUserDetails($data){
        $this->getUser($data,true);

    }
    public function deleteUser($id){
        $id=(int)$id;
        if($id!==0){

            $req=$this->prepare('DELETE * FROM users WHERE id=:id',array('id'=>$id));
            $req->closeCursor();
        }else
        {
            throw new \Exception("L'id doit etre un entier",'0011');//errorCode
        }

    }
    public function setUser($user){


            $this->insert($user);

    }
    public function addUser(array $data){
        
    }

}