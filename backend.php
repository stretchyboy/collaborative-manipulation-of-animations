<?php
  define('CONST_CMA',		dirname(__FILE__)."/");
  define('CONST_CMA_LIB', CONST_CMA."lib/");
  define('CONST_CMA_MODULES', CONST_CMA."modules/");
  
  
  require_once(CONST_CMA_LIB."core.php");


$filename  = CMA_STATEFILE;

// store new message in the file
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
//echo "\n<br><pre>\nmsg  =" .var_export($msg , TRUE)."</pre>";
if ($msg != '')
{
	$msg = stripslashes($msg);
	//add the defaulkting and calulating code here.
  
	$aParameters = json_decode($msg, true);
  //echo "\n<br><pre>\naParameters  =" .var_export($aParameters , TRUE)."</pre>";
  
  
  foreach($aParameters['actors'] as $sInstance => $aActor)
  {
    $oActor =& getActor($aActor, $sInstance);

    $aOut[] = array('sDef'=>$oActor->getDefs(), 'sBody'=>$oActor->getBody()) ;
    $aActorParams[$sInstance] = $oActor->aValues;//json_decode(json_encode($oActor), true);
  }
  
  $aOut = $aParameters;
	$aOut['actors'] = $aActorParams;
	//echo "\n<br><pre>\naOut =" .var_export($aOut, TRUE)."</pre>";
	
	$sOut = json_encode($aOut);
	//echo "\n<br><pre>\nsOut  =" .$sOut ."</pre>";
	
	file_put_contents($filename,$sOut);
  echo json_encode(array('recieved'=>1));
  flush();
  die();
}

// infinite loop until the data file is not modified
$lastmodif    = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
$currentmodif = filemtime($filename);
while ($currentmodif <= $lastmodif) // check if the data file has been modified
{
  usleep(10000); // sleep 10ms to unload the CPU
  clearstatcache();
  $currentmodif = filemtime($filename);
}

// return a json array
$response = array();
$response['msg']       = file_get_contents($filename);
$response['timestamp'] = $currentmodif;
//echo "\n<br><pre>\nresponse =" .var_export($response, TRUE)."</pre>";
echo json_encode($response);
flush();

?>