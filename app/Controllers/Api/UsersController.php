<?php
namespace App\Controllers\Api;
use \App\Controllers\Controller;
class UsersController extends Controller{
    /**
     * login function
     * @param \App\Http\Request $req
     * @param \App\Http\Response $res
     */
    public function login($req,$res,$params){
        $user=$req->post('username');
        $passwd=$req->post('passwd');
        $userData=$this->userExists($user);
        if($userData){
            if($userData->passwd==sha1($passwd)){
                $token=$res->generateJWT(array(
                    'data'=>array(
                        'username'=>$userData->usrname,
                        'id'=>$userData->id

                    ),
                    'key'=>$req->serverParams('secret_key')
                ));
                return $res->renderJSON(array(
                    'token'=>$token,
                    'user'=>array(
                        'username'=>$userData->username,
                        'privilege'=>$userData->privilege,
                        'image'=>$userData->image
                    )
                ));
            }
            else{
                $res->addHeader('HTTP/1.1 401 Unauthorized');
                return $res->renderJSON(array(
                    'error'=>'mot de passe invalide'
                ));

            }
        }
        else{
            $res->addHeader('HTTP/1.1 401 Unauthorized');
            $res->renderJSON(array(
                'error'=>"le nom d'utilisateur est invalide"
            ));
        }
        
        
    }
    /**
     * Add a new user
     * @param \App\Http\Request $req
     * @param \App\Http\Response $res
     */
    public function register($req,$res,$params){
        $user=$req->post('username');
        $passwd=$req->post('passwd');
        $email=$req->post('email');
        $userData=$this->userExists($user);
        if($userData){
                $res->addHeader('HTTP/1.1 401 Unauthorized');
                return $res->renderJSON(array(
                    'error'=>'Ce nom d\'utilisateur n\'est pas disponible'
                ));
        }
        else{
            //ferify inputs
            if($this->str->isValidUsername($user)){
                if($this->str->isValidPassWord($passwd)){
                    if($this->str->isEmail($email)){
                        $data=array(
                            'usrname'=>$user,
                            'passwd'=>sha1($passwd),
                            'email'=>$email
                        );
                        $this->model->insert($data);
                        return $this->login($req,$res,$params);
                    }else{
                        $res->addHeader('HTTP/1.1 401 Unauthorized');
                        return $res->renderJSON(array(
                            'error'=>'L\'adresse email invalide'
                        ));
                    }
                }else{
                    $res->addHeader('HTTP/1.1 401 Unauthorized');
                    return $res->renderJSON(array(
                        'error'=>'Le mot de passe est trop court ou non autorisé'
                    ));
                }
            }else{
                $res->addHeader('HTTP/1.1 401 Unauthorized');
                return $res->renderJSON(array(
                    'error'=>'ce type de nom d\'utilisateur n\'est pas autorisé'
                ));
            }
        }
        
        
    }
    /**
     * function that verify if the user exists in the DB and return this if positif and false if negatif
     * @param string $username the username
     * @return \App\Database\Tables\UsersTable
     * @return false
     */
    public function userExists(string $username){
        $userData=$this->model->getUser(array('usrname'=>$username),true);
        return $userData?$this->model->getTableInstances($userData,true):false;
    }
    

}