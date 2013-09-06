<?php
namespace Rasty\Grid\entitygrid\model;

/**
 * Representa una acci'on sobre la grilla. 
 *
 * @author Bernardo Iribarne (bernardo.iribarne@codnet.com.ar)
 * @since 14-12-2011
 *
 */
class GridActionModel{

	private $name;	
	private $label;
	private $action;
	private $isMultiple;
	private $image;	
	private $style;
	private $callback;
	private $confirmationMsg;
	private $onPopUp;
 	private $heightPopup;
 	private $widthPopup;
 	private $onTargetblank;
 	private $hasAllPageItems;


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

	public function getAction()
	{
	    return $this->action;
	}

	public function setAction($action)
	{
	    $this->action = $action;
	}

	public function getIsMultiple()
	{
	    return $this->isMultiple;
	}

	public function setIsMultiple($isMultiple)
	{
	    $this->isMultiple = $isMultiple;
	}

	public function getImage()
	{
	    return $this->image;
	}

	public function setImage($image)
	{
	    $this->image = $image;
	}

	public function getStyle()
	{
	    return $this->style;
	}

	public function setStyle($style)
	{
	    $this->style = $style;
	}

	public function getCallback()
	{
	    return $this->callback;
	}

	public function setCallback($callback)
	{
	    $this->callback = $callback;
	}

	public function getConfirmationMsg()
	{
	    return $this->confirmationMsg;
	}

	public function setConfirmationMsg($confirmationMsg)
	{
	    $this->confirmationMsg = $confirmationMsg;
	}

	public function getOnPopUp()
	{
	    return $this->onPopUp;
	}

	public function setOnPopUp($onPopUp)
	{
	    $this->onPopUp = $onPopUp;
	}

	public function getHeightPopup()
	{
	    return $this->heightPopup;
	}

	public function setHeightPopup($heightPopup)
	{
	    $this->heightPopup = $heightPopup;
	}

	public function getWidthPopup()
	{
	    return $this->widthPopup;
	}

	public function setWidthPopup($widthPopup)
	{
	    $this->widthPopup = $widthPopup;
	}

	public function getOnTargetblank()
	{
	    return $this->onTargetblank;
	}

	public function setOnTargetblank($onTargetblank)
	{
	    $this->onTargetblank = $onTargetblank;
	}

	public function getHasAllPageItems()
	{
	    return $this->hasAllPageItems;
	}

	public function setHasAllPageItems($hasAllPageItems)
	{
	    $this->hasAllPageItems = $hasAllPageItems;
	}
}