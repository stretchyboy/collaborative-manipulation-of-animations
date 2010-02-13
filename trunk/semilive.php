<?php

  define('CONST_CMA',		dirname(__FILE__)."/");
  define('CONST_CMA_LIB', CONST_CMA."lib/");
  define('CONST_CMA_MODULES', CONST_CMA."modules/");
  
  require_once(CONST_CMA_LIB."core.php");
  
  $sJSON = getState();
  
  $aParameters = json_decode($sJSON, true);
  //echo "\n<br><pre>\naParameters  =" .var_export($aParameters , TRUE)."</pre>";
  
  /*$aOut = array(
                'width'   => $aParameters['width'],
                'height'  => $aParameters['height']
                );
  */
  $sTemplate = file_get_contents(dirname(__FILE__).'/templates/semi_live_viewer.html');
  $oTemplate = new JsonTemplate($sTemplate);
  $sOut = $oTemplate->expand($aParameters);
  //header("Content-Type: image/svg+xml");
  echo $sOut;
