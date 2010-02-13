<?php

/**
 * An animated actor in the full CMA scene
 * 
 * This would be a kaleioscope or clippath ainmation or simliar.
 * 
 * This class will provide all the utility methods for getting a control panel and the svg both via php and javascript
 * 
 * The member variables of this class are a bit in the air as all subclassess are allowed as many as they like we will try to secure this up later.
 * 
 * @author martyn
 */
class actor
{
	/**
	 * @var string an identifier for this instance to put in xml ids etc.
	 */
	var $sInstance = '';
	
	/**
	 * @var string the path of the directory the module is defined in
	 */
	var $sModulePath = '';
	
	
	/**
		* 
		* @var array
		* array of controls
		*/
		var $aControls = array();

	/**
		* 
		* @var array
		* array of values
		*/
		var $aValues = array();
	
	/**
		* 
		* @var string
		* type of module
		*/
		var $type = null;
		
		/**
		* 
		* @var string
		* Label of module
		*/
		var $label = null;
		
		
    /**
     * @param array $aParams
     * @param bool $bCalc  
     * @return bool
     */
    function __construct($aParams, $sInstance = null, $bCalc = true)
    {
      
      $this->type = str_replace("actor_","", get_class($this));
       foreach(array_keys($this->aControls) as $iKey)
       {
         $aField = $this->aControls[$iKey];
         if(!isset($this->aControls[$iKey]['type']))
         {
           $this->aControls[$iKey]['type'] = 'int';
         }
         
         $this->aControls[$iKey]['aType'][$this->aControls[$iKey]['type']] = 1;
         
         if(isset($aField['default']))
         {
           $this->aValues[$aField['name']] = $aField['default'];
         }
       }
       
        //echo "\n<br><pre>\nthis->aControls =" .var_export($this->aControls, TRUE)."</pre>";
        
       
       if($sInstance)
       {
         $this->sInstance = $sInstance;
       }
       else
       {
         $this->sInstance = time(); // just a test string
       }
       
       foreach($aParams as $sKey => $xValue)
       {
         $this->aValues[$sKey] = $xValue;
       }
       
       $this->sModulePath = CONST_CMA_MODULES.(str_replace("actor_","", get_class($this)));
       if($bCalc)
       {
       	$this->calc();
       }
       return true;
    }
    
    /**
     * sets any additional member variable of this object needed for the template
     */
    function calc()
    {
          
    }
    
    /**
     * @param string $sTemplateName
     * @return string
     */
    function expandTemplate($sTemplateName)
    {
      $aValues = $this->aValues;
      $aValues['sInstance'] = $this->sInstance;
      $aValues['type'] = str_replace("actor_","", get_class($this));
      $sJSON = json_encode($aValues);
      $sTemplateFile = $this->sModulePath.'/'.$sTemplateName;
      $sTemplate = file_get_contents($sTemplateFile);
      $oTemplate = new JsonTemplate($sTemplate);
      $sOut = $oTemplate->expand($sJSON);
      return $sOut;	
    }
    
    function getDefs()
    {
      return $this->expandTemplate('defs.svg');
    }
    
    
    function getBody()
    {
      return $this->expandTemplate('body.svg');
    }
    
    function getAnimatedXYFromPath($sControlPath, $fMaxX = 100, $fMaxY = 100)
    {
      $aControlPath = array();
      $aSections = split(';', trim($sControlPath));
      foreach( $aSections as $sSection)
      {
        $aSections = split(',', $sSection);
        $aControlPath[] = array('x'=>(int) trim($aSections[0]), 'y'=>(int) trim($aSections[1]));
      }
      return $this->getAnimatedXY($aControlPath, $fMaxX, $fMaxY);
    }
    
    
    function getAnimatedXY($aControlPath, $fMaxX = 100, $fMaxY = 100)
    {
      $aClosedPath = $aControlPath;
      $aClosedPath[] = $aControlPath[0];
      $aAnim = array();
      foreach($aClosedPath as $aPos)
      {
        $aAnim[] = ($aPos['x'] / 100 * $fMaxX) .",".($aPos['y'] / 100 *$fMaxY);
      }
     
      $sAnimation = join(';', $aAnim);
      return $sAnimation;
    }
  
	
}