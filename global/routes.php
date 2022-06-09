<?php

/**
 * here I can make routes directive for web pages
 * @param \App\Http\Router
 * @param \App\Http\Response
 * @param \App\Http\Request
 */
function WebRoutes($router){
   $router->setApp('Web');//tell to the router that i wanna use the web app
   

   
   $router->add('/','Default@home','home');
   $router->add('/login','Default@login','login');
   $router->add('/signin','Default@signin','signin');
   $router->add('/(.+)','Error@error404','error');
   

   //on retourne le routeur
   return $router;
}

/**
 * here I can make routes directive for the Api
 * @param \App\Http\Router
 * @param \App\Http\Response
 * @param \App\Http\Request
 */
function ApiRoutes($router){
   $router->setApp('Api');//tell to the router that i wanna use the api app
   $router->add('/api/login','Users@login','login','post');
   $router->add('/api/register','Users@register','register','post');

   
   $router->add('/api/(.+)','Error@error404','error');
   

   //on retourne le routeur
   return $router;
}

 
