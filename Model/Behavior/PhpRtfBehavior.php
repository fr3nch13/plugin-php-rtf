<?php

// CakePHP friendly wrapper for PHPExcel
// found at: https://github.com/PHPOffice/PHPExcel/wiki

//App::uses('CakeEmail', 'Network/Email');
//App::uses('Shell', 'Console');

// Composer is handling the autoload of the required files.
//// apparently not, if not, load them here

require_once ROOT. DS. 'Vendor'.DS.'nihfo-vendors'.DS.'php-rtf-html'.DS.'rtf-html-php.php';

class PhpRtfBehavior extends ModelBehavior
{
	
	public $settings = array();
	
	protected $_defaults = array(
	);
	
	public $Model = false;
	public $RtfReader = false;
	public $RtfHtml = false;
	public $RtfTables = false;
	
	public function setup(Model $Model, $settings = array())
	{
		$this->Model = $Model;
		
		if (!isset($this->settings[$Model->alias])) 
		{
			$this->settings[$Model->alias] = $this->_defaults;
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
		
		$this->RtfReader = new RtfReader();
		$this->RtfHtml = new RtfHtml();
		$this->RtfTables = new RtfTables();
		
	}
	
	public function PhpRtf_getTables(Model $Model, $rtf_path = false)
	{
		$Model->modelError = false;
		$rtf = file_get_contents($rtf_path);
		$rtf = str_replace("\u8203\'3f", '', $rtf); // get rid of this funky character
		if(!$this->RtfReader->Parse($rtf))
		{
			$Model->modelError = __('Parsing of the RTF file failed.');
			return false;
		}
		
		$results = $this->RtfTables->Format($this->RtfReader->root);
		
		return $results;
	}
	
	public function PhpRtf_getHtml(Model $Model, $rtf_path = false)
	{
		$Model->modelError = false;
		$rtf = file_get_contents($rtf_path);
		$rtf = str_replace("\u8203\'3f", '', $rtf); // get rid of this funky character
		if(!$this->RtfReader->Parse($rtf))
		{
			$Model->modelError = __('Parsing of the RTF file failed.');
			return false;
		}
		
		$results = $this->RtfHtml->Format($this->RtfReader->root);
		return $results;
	}
	
}