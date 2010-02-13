<?php

/**
* Some core code needed for eveything
*/

define('CMA_STATEFILE', 'data/state.json');
define('CMA_DEFAULTSTATEFILE', 'defaultstate.json');

require_once(CONST_CMA_LIB."actor.php");
require_once(CONST_CMA_LIB."jsontemplate.php");

/**
* Get current state and fetch the default if needed
*/
function getState()
{
	if(!file_exists(CMA_STATEFILE))
	{
		$sState = file_get_contents(CMA_DEFAULTSTATEFILE);
		file_put_contents(CMA_STATEFILE, $sState);
	}
	
	$sState = file_get_contents(CMA_STATEFILE);
	if(!$sState)
	{
	  $sState = file_get_contents(CMA_DEFAULTSTATEFILE);
		file_put_contents(CMA_STATEFILE, $sState);
		$sState = file_get_contents(CMA_STATEFILE);
	}
	return $sState;
}

/**
   * @param array $aActor
   * @return actor
   */
  function getActor($aActor, $sInstance)
  {
    require_once('modules/'.$aActor['type'].'/module.php');
    $sClassName = 'actor_'.$aActor['type'];
    $oActor = new $sClassName($aActor, $sInstance);
    return $oActor;
  }
