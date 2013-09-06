<?php
namespace Rasty\Grid\filter\model;

use Rasty\utils\RastyUtils;
use Rasty\utils\ReflectionUtils;
use Rasty\utils\Logger;

/**
 * Representa un criterio de búsqueda.
 * 
 * @author bernardo
 *
 */
class UICriteria {

	const TYPE_ASC = "ASC";
	const TYPE_DESC = "DESC";
	
	/**
	 * página para la paginación.
	 * @var int
	 */
	private $page;
	
	/**
	 * para ordenar la búsqueda
	 * @var array [name.sortType]
	 */
	private $orders;

	/**
	 * filas por página
	 * @var unknown_type
	 */
	private $rowPerPage;

	/**
	 * propiedades para popular.
	 * @var array
	 */
	private $properties;
	
	
	public function __construct(){
		$this->orders = array();
		$this->rowPerPage=20;
		$this->page=1;
		$this->properties = array();
		
	}
	
	public function addOrder($name, $type=self::TYPE_DESC){
	
		$this->orders[] = array("name" => $name, "type" => $type);			
	}
	

	public function getOrders()
	{
	    return $this->orders;
	}

	public function setOrders($orders)
	{
	    $this->orders = $orders;
	}

	public function getPage()
	{
	    return $this->page;
	}

	public function setPage($page)
	{
	    $this->page = $page;
	}
	
	public function fill(){
	
		$page = RastyUtils::getParamPOST("page",1);
    	$orderBy = RastyUtils::getParamPOST("orderBy");
    	$orderByType = RastyUtils::getParamPOST("orderByType");
    	
    	$this->setPage($page);
    	
    	if( !empty($orderBy) )
    		$this->addOrder( $orderBy, $orderByType );
    	
    		
		foreach ($this->properties as $property) {
			
			//$value = RastyUtils::getParamPOST($property);
			
			$value = RastyUtils::getParamPOST($property);			
			ReflectionUtils::doSetter( $this, $property, $value );
			
		}
	}

	public function getRowPerPage()
	{
	    return $this->rowPerPage;
	}

	public function setRowPerPage($rowPerPage)
	{
	    $this->rowPerPage = $rowPerPage;
	}

	public function addProperty($name){
	
		$this->properties[] = $name;			
	}
	
	public function getProperties()
	{
	    return $this->properties;
	}

	public function setProperties($properties)
	{
	    $this->properties = $properties;
	}
	
		
	/**
	 * llena el filtro con los valores guardados en sesión.
	 */
	public function fillFromSaved(){
	
		//$page = RastyUtils::getParamSESSION("page",1);
    	//$this->setPage($page);

		//Logger::log("begin fillFromSaved");
		
		$orderBy = $this->getSavedProperty("orderBy");
    	$orderByType = $this->getSavedProperty("orderByType");
    	
    	if( !empty($orderBy) )
    		$this->addOrder( $orderBy, $orderByType );
    	
		foreach ($this->properties as $property) {
			
			$value = $this->getSavedProperty($property);			
			ReflectionUtils::doSetter( $this, $property, $value );
			
		}
	}

	
	/**
	 * setea en sesión los valores del filtro.
	 */
	public function save(){
		
		//primero limpiamos la búsqueda anterior.
		$this->cleanSavedProperties();
		
		//Logger::log("begin save");
		foreach ($this->properties as $property) {

			$value = ReflectionUtils::doGetter( $this, $property );

			if( !empty($value) ){
					
				$this->saveProperty($property, $value);
					
			}
		}
		
	}
	public function saveProperty($name, $value){
		
		$nametosave = str_replace('.', '_', $name);
		$_SESSION[get_class($this)][$nametosave] = $value;
		
		//Logger::log("savedProperty($name): $nametosave => $value to " . get_class($this));
		
	}
	
	public function getSavedProperty($name){
		
		$nametosave = str_replace('.', '_', $name);
		$value = (isset($_SESSION[ get_class($this) ][$nametosave] ))?$_SESSION[get_class($this)][$nametosave] :null;
		
		//Logger::log("getSavedProperty($name): $nametosave => $value from " . get_class($this));
		
		return $value;
		
	}
	
	public function cleanSavedProperties(){
		//Logger::log("cleanSavedProperties from " . get_class($this));
		
		unset( $_SESSION[ get_class($this) ] );
	}
	
}
