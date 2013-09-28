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
	
	
	public function __construct(){
		$this->orders = array();
		$this->rowPerPage=20;
		$this->page=1;
		
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
	
	public function getRowPerPage()
	{
	    return $this->rowPerPage;
	}

	public function setRowPerPage($rowPerPage)
	{
	    $this->rowPerPage = $rowPerPage;
	}
		
}