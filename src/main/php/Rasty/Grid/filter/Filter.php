<?php

namespace Rasty\Grid\filter;

use Rasty\Grid\filter\model\UICriteria;
use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;
use Rasty\utils\XTemplate;
use Rasty\utils\ReflectionUtils;
/**
 * componente filter.
 * 
 * Cada filter tendrá las properties por las cuales
 * se desea filtrar. Por otro lado, tendrá un form para "llenar" dichas properties.
 * Luego utilizará las properties para generar el criterio de búsqueda.
 * 
 * @author Bernardo Iribarne (bernardo.iribarne@codnet.com.ar)
 * @since 22-03-2013
 *
 */
abstract class Filter extends RastyComponent{

	/**
	 * legend para el fieldset.
	 * @var string
	 */
	private $legend;

	/**
	 * legend para el fieldset.
	 * volver a intentar la búsqueda.
	 * @var string
	 */
	private $legendAgain;
	
	/**
	 * label para el buscar
	 * @var string
	 */
	private $labelSubmit;
	
	/**
	 * class del gridmodel para la grilla
	 * donde muestra los resultados.
	 * @var string
	 */
	private $gridModelClazz;
	
	/**
	 * class del uicriteria
	 * @var string
	 */
	private $uicriteriaClazz;

	/**
	 * identificador del div donde se mostrarán
	 * los resultados
	 * @var string
	 */
	private $resultDiv;

	/**
	 * función javascript a invocar cuando se selecciona una fila.
	 * @var string
	 */
	private $selectRowCallback;
	

	private $criteria;
	
	public function __construct(){

		
	}
	
	protected function initDefaults(){
	
		if( empty( $this->labelSubmit ) ){
			$this->setLabelSubmit( $this->localize("filter.buscar"));
		}
		
		
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		$this->initDefaults();
		
		$xtpl->assign("model",  $this->getGridModelClazz() );
		
		$xtpl->assign("legend", $this->getLegend() );

		$xtpl->assign("legend_again", $this->getLegendAgain() );
		
		$xtpl->assign("lbl_submit", $this->getLabelSubmit() );
		
		$xtpl->assign("resultDiv", $this->getResultDiv() );
		
		$xtpl->assign("selectRowCallback", $this->getSelectRowCallback() );
		
	}

	public function getCriteria(){
	
		return $this->criteria;
		
	}

	public function save(){
	
		return $this->getCriteria()->save();
	}
	
	public function getLegend()
	{
	    return $this->legend;
	}

	public function setLegend($legend)
	{
	    $this->legend = $legend;
	}

	public function getLabelSubmit()
	{
	    return $this->labelSubmit;
	}

	public function setLabelSubmit($labelSubmit)
	{
	    $this->labelSubmit = $labelSubmit;
	}

	public function getGridModelClazz()
	{
	    return $this->gridModelClazz;
	}

	public function setGridModelClazz($gridModelClazz)
	{
	    $this->gridModelClazz = $gridModelClazz;
	}

	public function getUicriteriaClazz()
	{
	    return $this->uicriteriaClazz;
	}

	public function setUicriteriaClazz($uicriteriaClazz)
	{
	    $this->uicriteriaClazz = $uicriteriaClazz;
	    
	    $this->criteria = ReflectionUtils::newInstance( $uicriteriaClazz );
	    
	    $this->criteria->fillFromSaved();
	}
	

	public function getResultDiv()
	{
	    return $this->resultDiv;
	}

	public function setResultDiv($resultDiv)
	{
	    $this->resultDiv = $resultDiv;
	}

	public function getSelectRowCallback()
	{
	    return $this->selectRowCallback;
	}

	public function setSelectRowCallback($selectRowCallback)
	{
	    $this->selectRowCallback = $selectRowCallback;
	}

	public function getLegendAgain()
	{
	    return $this->legendAgain;
	}

	public function setLegendAgain($legendAgain)
	{
	    $this->legendAgain = $legendAgain;
	}

	public function setCriteria($criteria)
	{
	    $this->criteria = $criteria;
	}
}