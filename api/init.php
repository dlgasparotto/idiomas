<?php

include 'sys/mutil.php';
include 'sys/mbd.php';
include 'sys/mreuter.php';

spl_autoload_register('autoload');

function autoload($className) {

  $class = strtolower($className);
  
  if ($class == "aplicacao") {
  	$path = "sys/maplicacao.php";
  } else {
    $path = "resources/".$class."s/m".$class."s.php";
  }

  include $path;
  
}

?>