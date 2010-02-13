<?php

  class actor_sierpinsky extends actor
  {
    
   var $label = 'Sierpinsky Triangle';
    
	/**
		* 
		* @var array
		* array of controls
		*/
		var $aControls = array(
		  array('name' => 'levels','title'=>'Number of Levels', 'default' => 5, 'type'=>'int', 'min'=>3, 'max'=>6),
      array('name' => 'size','title'=>'Size', 'default' => 150, 'type'=>'int', 'min'=>50, 'max'=>500, 'live'=>true),
      array('name' => 'pos_ppath','title'=>'Where does it go on the screen', 'default' => '150,150;200,200', 'type'=>'path', 'regexp'=>'([0-9]+\,[0-9]+)\;([0-9]+\,[0-9]+\;?)+'),
      array('name' => 'pos_duration','title'=>'How long does the position movement last', 'default' => 60, 'type'=>'int', 'min'=>10, 'max'=>120),
      array('name' => 'rotation_duration','title'=>'How long does the rotation last','default' => 60, 'type'=>'int', 'min'=>10, 'max'=>120),
		  );
		
    function calc()
    {
      $aLevels = array();
      for($i = 1; $i <= $this->aValues['levels']; $i++)
      {
        $aLevels[] = array('level' => $i, 'last' => $i -1);
      }
      $this->aValues['aLevels'] = $aLevels;
      $this->aValues['scale'] = $this->aValues['size'] / 2;
      $this->aValues['pos_path'] = $this->getAnimatedXYFromPath($this->aValues['pos_ppath']);
      //echo "\n<br><pre>\nthis->aValues =" .var_export($this->aValues, TRUE)."</pre>";
    }
  }
