<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
Metodo el cual crea los nombres comunes que se pueden usar en una pagina
y las manda por variables
*/

class Globals
{
	
	function __construct($config = array() )
	{
		foreach ($config as $key => $value){
			$data[$key] = $value;
		}
		
		$CI =& get_instance();
		
		$CI->load->vars($data);
	}
}