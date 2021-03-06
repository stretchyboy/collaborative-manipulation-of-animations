<?php

  define('CONST_CMA',		dirname(__FILE__)."/");
  define('CONST_CMA_LIB', CONST_CMA."lib/");
  define('CONST_CMA_MODULES', CONST_CMA."modules/");
  
  
  require_once(CONST_CMA_LIB."core.php");


  $sJSON = getState();

  //echo "\n<br><pre>\nsJSON  =" .$sJSON ."</pre>";
  
  $aParameters = json_decode($sJSON, true);
  //echo "\n<br><pre>\naParameters  =" .var_export($aParameters , TRUE)."</pre>";
  
  $aScene = array();

  foreach($aParameters['actors'] as $sInstance => $aActor)
  {
    $oActor =& getActor($aActor, $sInstance);

    $aOut[] = array('sDef'=>$oActor->getDefs(), 'sBody'=>$oActor->getBody()) ;
    $aActorParams[] = $oActor;//json_decode(json_encode($oActor), true);
  }
  
  $aOut = array('aBodies' => $aOut,
                'aDefs'   => $aOut,
                'actors'  => $aActorParams,
                'width'   => $aParameters['width'],
                'height'  => $aParameters['height']
                );
  
  $sTemplate = file_get_contents(dirname(__FILE__).'/templates/output.svg');
  $oTemplate = new JsonTemplate($sTemplate);
  $sOut = $oTemplate->expand($aOut);
  header("Content-Type: image/svg+xml");
  echo $sOut;
  
