<?php

  class actor_kaleidoscope extends actor
  {
    
	/**
		* 
		* @var array
		* array of controls
		*/
		var $aControls = array(
		  array('name' => 'sides','title'=>'Number of Sides', 'default' => 3, 'type'=>'int', 'min'=>3, 'max'=>12),
      array('name' => 'radius','title'=>'Radius', 'default' => 150, 'type'=>'int', 'min'=>50, 'max'=>500),
      array('name' => 'image_url','length'=>100,'title'=>'URL for picture','default' => 'images/Fagus_sylvatica_autumn_leaves.jpg', 'type'=>'url'),
      
      array('name' => 'pos_ppath','title'=>'Where does it go on the screen', 'default' => '150,150;200,200', 'type'=>'path', 'regexp'=>'([0-9]+\,[0-9]+)\;([0-9]+\,[0-9]+\;?)+'),
      array('name' => 'pos_duration','title'=>'How long does the position movement last', 'default' => 60, 'type'=>'int', 'min'=>10, 'max'=>120),
      
      array('name' => 'rotation_duration','title'=>'How long does the rotation last','default' => 60, 'type'=>'int', 'min'=>10, 'max'=>120),
      
      array('name' => 'slip_ppath','title'=>'Where does the triangle slip through','default' => '0,0;100,100', 'type'=>'percentagepath', 'regexp'=>'([0-9]+\,[0-9]+)\;([0-9]+\,[0-9]+\;?)+'),
      array('name' => 'slip_duration','title'=>'Image slip animation last','default' => 60, 'type'=>'int', 'min'=>10, 'max'=>120),
      
      array('name' => 'orginal_scale','title'=>'How much bigger than the kalediscope is the image','default' => 2, 'type'=>'int', 'min'=>1, 'max'=>5),
		  );

	/**
		* 
		* @var array
		* array of values
		*/
		var $aValues = array(
    );

	
	/**
		* 
		* @var array
		* array of calculated values
		*/
		var $aCalculated = array();
		
		
    var $label = 'Kaleidoscope of an Image';
    
       
    function calc()
    {
      //$this->aValues['center_x'] = $this->aValues['output_width'] / 2; 
      //$this->aValues['center_y'] = $this->aValues['output_height'] / 2;
      
      //echo "\n<br><pre>\noKaleidoscope =" .var_export($this, TRUE)."</pre>";
      //$this->aValues['radius'] = round(sqrt(($this->aValues['output_width'] * $this->aValues['output_width']) + ($this->aValues['output_height'] * $this->aValues['output_height'])) + 2, 1);
      $this->aValues['angle_rad'] = M_PI/$this->aValues['sides'];
      $this->aValues['angle_deg'] = rad2deg($this->aValues['angle_rad']);
      $this->aValues['triangle_x'] = round($this->aValues['radius'] * cos($this->aValues['angle_rad']), 1);
      $this->aValues['triangle_y'] = round($this->aValues['radius'] * sin($this->aValues['angle_rad']), 1);
      $this->aValues['triangle_coord'] = "1,1 ".($this->aValues['triangle_x']+1).','.($this->aValues['triangle_y']+1).','.($this->aValues['triangle_x']+1).',1';
      $this->aValues['clip_coord'] = "0,0 ".$this->aValues['triangle_x'].",".$this->aValues['triangle_y'].", ".$this->aValues['triangle_x'].",0";
      $this->aValues['triangle_coord'] = $this->aValues['clip_coord']; 
      $this->aValues['image_data'] = getimagesize(str_replace(' ', '%20', $this->aValues['image_url']));
      
      if($this->aValues['image_data'] == false)
      {
        error('No Image data');
      }
      
      ///$this->aValues['image_width'] = $this->aValues['output_width'] * $this->aValues['orginal_scale'];
      $this->aValues['image_width'] = $this->aValues['radius'] * 2 * $this->aValues['orginal_scale'];
      $this->aValues['image_height'] = $this->aValues['image_data'][1] * ($this->aValues['image_width'] / $this->aValues['image_data'][0]);
      
      $this->aValues['rotations'] = array();
      
      for($i = 0 ; $i < $this->aValues['sides'] ; $i ++)
      {
        $this->aValues['rotations'][] = array('angle_deg' => $this->aValues['angle_deg'] * $i *2);
      }
      
      $fSlipMaxX = ($this->aValues['triangle_x'] - 2) - $this->aValues['image_width'];
      $fSlipMaxY = ($this->aValues['triangle_y'] - 2) - $this->aValues['image_height'];
      
      $this->aValues['slip_path'] = $this->getAnimatedXYFromPath($this->aValues['slip_ppath'], $fSlipMaxX, $fSlipMaxY);
      $this->aValues['pos_path'] = $this->getAnimatedXYFromPath($this->aValues['pos_ppath']);
      //$this->aValues['slip_path'] = $this->getAnimatedXY($this->aValues['slip_control'], $fSlipMaxX, $fSlipMaxY);
      //
      
    }
    
  }
