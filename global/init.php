<?php
 use App\App;
 use App\Http\Router;
 /**
  * initializing and runing lemon
  */
  $app=new App(ROOT_PATH.'/config/config.php');
  $app->run();
