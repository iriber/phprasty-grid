<?php
namespace Rasty\Grid\entitygrid\model;

/**
 * Representa filtro para la grilla 
 *
 * @author Bernardo Iribarne (bernardo.iribarne@codnet.com.ar)
 * @since 05-08-2013
 *
 */
class GridFilterModel{

	
	
	private $id;	
	private $name;	
	private $label;
	private $field;
	private $format;
	private $isHidden;
	private $value;//valor para cuando es hidden.
	//operador para el filtro ("=", "LIKE", ">", etc );
	private $operator;
	private $type;//tipo de filtro (ver constantes CDT_CMP_GRID_FILTER_TYPE_... )
	
	
	const FILTER_TYPE_STRING = "string";
	
	
	public function __construct(){
		$this->format = new GridValueFormat();
		$this->operator = "=";
		$this->isHidden = false;
		$this->type = self::FILTER_TYPE_STRING;
	}
	
	

	public function getId()
	{
	    return $this->id;
	}

	public function setId($id)
	{
	    $this->id = $id;
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

	public function getIsHidden()
	{
	    return $this->isHidden;
	}

	public function setIsHidden($isHidden)
	{
	    $this->isHidden = $isHidden;
	}

	public function getValue()
	{
	    return $this->value;
	}

	public function setValue($value)
	{
	    $this->value = $value;
	}

	public function getOperator()
	{
	    return $this->operator;
	}

	public function setOperator($operator)
	{
	    $this->operator = $operator;
	}

	public function getType()
	{
	    return $this->type;
	}

	public function setType($type)
	{
	    $this->type = $type;
	}
}