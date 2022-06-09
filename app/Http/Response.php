<?php
namespace App\Http;
use \App\Http\Router;
use \Views\ViewLoader;
use \Firebase\JWT\JWT;

/**
 * The server Response
 */

class Response{

    protected $header=[];
    protected $content;
    protected $router;
    protected $csrfToken;
    public static $instance;

    public static function getInstance()
    {
       if(is_null(self::$instance)){
           self::$instance=new Response();
       }
        return self::$instance;
    }

    
    public function __construct()
    {
        $this->route=Router::getInstance();
        $this->setCsrfToken();
    }
    /**
     * here i send the 404 error
     */
    public function error404($app=null){

        $this->setHeader('HTTP/1.1 404 Not Found');
        if($app=='JSON')$this->renderJSON(array('error'=>'page not found'));
        else $this->render('sub/404');
        

    }
    /**
     * here i redirect to the 404 error
     */
    public function goTo404Error(){

        $this->redirect('/error/error404');
    }

    /**
     * here we put the header in the header variable
     * @param array|string $header the title of header
     * @return void
     */
    public function setHeader($header){
        if(is_string($header)) return $this->header=[$header];
        if(is_array($header)) return $this->header=$header;
        
    }
    /**
     * add the header
     * @param array|string $header
     */
    public function addHeader($header){
        if(is_string($header)) return array_push($this->header,$header);
        if(is_array($header)) return $this->header=array_merge($this->header,$header);
    }
    /**
     * method used to send the header to users
     * @return void
     */
    public function sendHeader(){

        if(!empty($this->header)){
            foreach ($this->header as $header) {
                header($header);
            }
        }
    }
    /**
     * set The response content
     * @param String $content
     */
    public function setContent($content){
        $this->content=$content;
    }
    /**
     * get The content of a page
     * @return String $this->content
     */
    public function getContent(){
        return $this->content;
    }
    /**
     * This is the Simple redirection
     * @param String $path the path or uri where to redirect
     * @return void
     */
    public function redirect($path){

        header('location:'.$path);
        exit();

    }
    /**
     * generate the user jwt token
     * 
     */
    function generateJWT($options){

        return JWT::encode($options['data'],$options['key']);

    }
    /**
     * Create the user Cookie
     */
    public function setCookie($name,$value='',$expire=0,$path=null,$domain=null,$secure=false,$httpOnly=true){
        setcookie($name,$value,$expire,$path,$domain,$secure,$httpOnly);
    }
    /**
     * generate the csrf token
     */
    public function generateCsrfToken(){
        $this->csrfToken=sha1(time().'lemon'.rand(17,80));
    }
    public function setCsrfToken(){
        if(is_null($this->csrfToken)) $this->generateCsrfToken();
        $this->setSession('token',$this->csrfToken);
    }
    public function getCsrfToken(){
        return $this->csrfToken;
    }
    /**
     * create or set a user session
     */
    public function setSession($key,$value){
        $_SESSION[$key]=$value;
    }
    /**
     * Delete or unset session
     * @param string $key The session key
     */
    public function unsetSession(string $key){
        unset($_SESSION[$key]);
    }
    /**
     * i return the complete response to the user
     */
    public function getResponse(){
        $this->sendHeader();
        echo $this->getContent();

    }
    public function getRouter(){
        if(is_null($this->router)){
            $this->router=Router::getInstance();
        }
        return $this->router;
    }
    public function renderJSON(array $data){
        $this->addHeader('Content-Type:application/json');
        $this->setContent(json_encode($data));
    }
    public function render($page){
        $this->addHeader('Content-Type:text/html');
        $this->setContent(ViewLoader::load($page));
    }
}