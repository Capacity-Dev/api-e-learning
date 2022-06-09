<?php
    namespace Views;
    class ViewLoader{

        public static function load($view,$data=null){
            //geting protocol and host name
            if(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on'){
                $protocol='https';
            }else{
                $protocol='http';
            }
            $host=$_SERVER['HTTP_HOST'];
            $domain=$protocol.'://'.$host;
            function link($name){
                return $domain.$router->getPath($name);
            }
            //create the view path
            $view=__DIR__.'/pages/'.$view.'.voc.php';
            ob_start();
            include($view);
            $viewContent=ob_get_clean();
            if(isset($parent)){
                ob_start();
                    include(__DIR__.'/pages/'.$parent.'.voc.php');
                $page=ob_get_clean();
                return $page;
            }
            else {
                return $viewContent;
            }
        }
    }