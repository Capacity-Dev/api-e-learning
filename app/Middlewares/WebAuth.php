<?php
namespace App\Middlewares;
use \App\Web\Controllers\UsersController;

/**
 * All about user authentification
 */
class WebAuth extends Middleware{

    protected $autorized=array(
        '/login',
        '/error/error404',
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
        
        
        
    }
}
