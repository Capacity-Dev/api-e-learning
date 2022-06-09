<?php
namespace App\Middlewares;
use \App\Controllers\Api\UsersController;
use Firebase\JWT\JWT;

/**
 * All about user authentification
 */
class ApiAuth extends Middleware{

    protected $autorized=array(
        '/api/login',
        '/api/error/error404',
        '/api/register'
    );

    public function __construct()
    {
        
    }
    /**
     * login function
     * @param \App\Http\Request $req
     * @param \App\Http\Response $res
     */
    public function run($req,$res){
        
        
        
        $url=$req->getUrl();
        if(!in_array($url,$this->autorized)){
            
            if (! isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $res->addHeader('HTTP/1.0 400 Bad Request');
                $res->renderJSON(array('error'=>'Echec d\'identification'));
                $this->breakScript();
            }
            else{
                preg_match('/Bearer\s(\S+)/',$_SERVER['HTTP_AUTHORIZATION'], $matches);
                $jwt=$matches[1];
                if(!$jwt){
                    $res->addHeader('HTTP/1.0 400 Bad Request');
                    $res->renderJSON(array('error'=>'identification incorrecte'));
                    $this->breakScript();
                }
                else{
                    $tokenData=JWT::decode($jwt,$req->serverParams('secret_key'),array_keys(JWT::$supported_algs));
                    $userController=new UsersController();
                    $user=$userController->userExists($tokenData->username);
                    if($user){
                        $req->addCInfo($user->getData());
                    }
                    unset($user);
                    unset($userController);
                }
            }
        }
        
        
    }
}
