<?php
namespace App\Controllers\Api;
use \App\Controllers\Controller;
class ErrorController extends Controller{

    protected $canLoadModel=false;


    public function error404($req,$res){
        $res->error404('JSON');

    }

}