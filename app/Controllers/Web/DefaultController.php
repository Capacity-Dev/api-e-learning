<?php
namespace App\Controllers\Web;
use \App\Controllers\Controller;
/**
 * controller for all guest pages
 */
class DefaultController extends Controller{
    /**
     * the login page
     * @param Object $req the request object
     * @param Object $res the response object
     */
    public function home($req,$res,$params=null){
        
        $res->render('sub/home');
    }
    
    

}