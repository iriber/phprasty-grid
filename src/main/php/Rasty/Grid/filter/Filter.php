<?php

namespace Rasty\Grid\filter;

use Rasty\Grid\filter\model\UICriteria;
use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;
use Rasty\utils\XTemplate;
use Rasty\utils\ReflectionUtils;
use Rasty\utils\Logger;

use Rasty\Forms\form\Form;

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
abstract class Filter extends Form{

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
	
	/**
	 * uicriteria asociado al filter.
	 * @var UICriteria
	 */
	private $criteria;

	
	public function __construct(){

		parent::__construct();
		
		$this->setMethod("POST");
		$this->setSelectRowCallback("showMenuRow");
		$this->setLabelSubmit( $this->localize("filter.buscar"));
		
	}
	
	protected function initDefaults(){
	
		
	}
	
	public function fill(){
		
		foreach ($this->getProperties() as $property) {
			
			//$value = RastyUtils::getParamPOST($property);
			Logger::log("fill property $property", __CLASS__);
			$input = $this->getComponentById($property);
			$value = $input->getPopulatedValue( $this->getMethod() );			
			ReflectionUtils::doSetter( $this->getCriteria(), $property, $value );
			
		}
		
		//le agregramos el order y la paginación.
		$orderBy = RastyUtils::getParamPOST("orderBy");
		$orderByType = RastyUtils::getParamPOST("orderByType");
		if(!empty($orderBy))
			$this->getCriteria()->addOrder($orderBy, $orderByType);
		
		$page = RastyUtils::getParamPOST("page");
		$this->getCriteria()->setPage($page);
		
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		$this->initDefaults();
		
		$xtpl->assign("model", urlencode($this->getGridModelClazz()) );

		$xtpl->assign("filter",  $this->getType() );
		
		$xtpl->assign("legend", $this->getLegend() );

		$xtpl->assign("legend_again", $this->getLegendAgain() );
		
		$xtpl->assign("lbl_submit", $this->getLabelSubmit() );
		
		$xtpl->assign("resultDiv", $this->getResultDiv() );
		
		$selectCallback = $this->getSelectRowCallback();
		if(empty($selectCallback))
			$selectCallback = "showMenuRow";
		$xtpl->assign("selectRowCallback", $selectCallback  );
		
	}

	public function getCriteria(){
	
		return $this->criteria;
		
	}

	/*
	public function save(){
	
		return $this->getCriteria()->save();
	}*/
	
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
	    
	    //FIXME $this->criteria->fillFromSaved();
	    
	    
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