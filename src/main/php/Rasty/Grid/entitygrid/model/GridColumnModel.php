<?php
namespace Rasty\Grid\entitygrid\model;

/**
 * Representa una columna en la grilla 
 *
 * @author Bernardo Iribarne (bernardo.iribarne@codnet.com.ar)
 * @since 05-08-2013
 *
 */

use Rasty\Grid\entitygrid\EntityGrid;

class GridColumnModel{

	private $name;	
	private $label;
	private $width;
	private $visible;
	private $field;
	private $format;
	private $cssClass;
	private $cssStyle;
	private $textAlign;
	private $group;

	public function __construct(){
		$this->setFormat( new GridValueFormat() );
		$this->setTextAlign( EntityGrid::TEXT_ALIGN_CENTER );
	}


	public function getName()
	{
	    return $this->name;
	}

	public function setName($name)
	{
	    $this->name = $name;
	}

	public function getLabel()
	{
	    return $this->label;
	}

	public function setLabel($label)
	{
	    $this->label = $label;
	}

	public function getWidth()
	{
	    return $this->width;
	}

	public function setWidth($width)
	{
	    $this->width = $width;
	}

	public function getVisible()
	{
	    return $this->visible;
	}

	public function setVisible($visible)
	{
	    $this->visible = $visible;
	}

	public function getField()
	{
	    return $this->field;
	}

	public function setField($field)
	{
	    $this->field = $field;
	}

	public function getFormat()
	{
	    return $this->format;
	}

	public function setFormat($format)
	{
	    $this->format = $format;
	}

	public function getCssClass()
	{
	    return $this->cssClass;
	}

	public function setCssClass($cssClass)
	{
	    $this->cssClass = $cssClass;
	}

	public function getCssStyle()
	{
	    return $this->cssStyle;
	}

	public function setCssStyle($cssStyle)
	{
	    $this->cssStyle = $cssStyle;
	}

	public function getTextAlign()
	{
	    return $this->textAlign;
	}

	public function setTextAlign($textAlign)
	{
	    $this->textAlign = $textAlign;
	}

	public function getGroup()
	{
	    return $this->group;
	}

	public function setGroup($group)
	{
	    $this->group = $group;
	}
	
	public function hasGroup()
	{
	    return  !empty( $this->group );
	}
}