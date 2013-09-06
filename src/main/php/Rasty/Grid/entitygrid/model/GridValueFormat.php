<?php
namespace Rasty\Grid\entitygrid\model;


/**
 * Formato para renderizar un valor de la grilla
 *
 * @author Bernardo Iribarne (bernardo.iribarne@codnet.com.ar)
 * @since 05-08-2013
 *
 */

class GridValueFormat{

	
	public function __construct(){
	}
	
	public function format( $value, $item=null ){
		return $value;
	}		
	
	public function getPattern(){
		return "";
	}
	
}