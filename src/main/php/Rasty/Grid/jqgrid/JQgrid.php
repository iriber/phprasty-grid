<?php
namespace Rasty\Grid\jqgrid;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;

class JQgrid extends RastyComponent{

	private $requestClass;
	private $requestMethod;
	private $requestMethodTotal;

	private $style;

	private $colNames;
	private $colModel;
	private $rowNum = 10;
	private $rowList="[10,20,30]";
	private $sortName;
	private $sortOrder = 'desc';
	private $caption;
	private $height=300;
	private $indexColumnId=0;

	public function setRequestClass( $value ){ $this->requestClass = $value; }
	public function getRequestClass(){ return $this->requestClass; }

	public function setRequestMethod( $value ){ $this->requestMethod = $value; }
	public function getRequestMethod(){ return $this->requestMethod; }

	public function setRequestMethodTotal( $value ){ $this->requestMethodTotal = $value; }
	public function getRequestMethodTotal(){ return $this->requestMethodTotal; }

	public function setStyle( $value ){ $this->style = $value; }
	public function getStyle(){ return $this->style; }

	public function getColNames(){ return $this->colNames;}
	public function setColNames($value){ $this->colNames = $value;}

	public function getColModel(){ return $this->colModel;}
	public function setColModel($value){ $this->colModel = $value;}

	public function getRowNum(){ return $this->rowNum;}
	public function setRowNum($value){ $this->rowNum = $value;}

	public function getRowList(){ return $this->rowList;}
	public function setRowList($value){ $this->rowList = $value;}

	public function getSortName(){ return $this->sortName;}
	public function setSortName($value){ $this->sortName = $value;}

	public function getSortOrder(){ return $this->sortOrder;}
	public function setSortOrder($value){ $this->sortOrder = $value;}

	public function getCaption(){ return $this->caption;}
	public function setCaption($value){ $this->caption = $value;}

	public function getHeight(){ return $this->height;}
	public function setHeight($value){ $this->height = $value;}

	public function getIndexColumnId(){ return $this->indexColumnId;}
	public function setIndexColumnId($value){ $this->indexColumnId = $value;}

	/*
	 colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
	 colModel:[
	 {name:'id',index:'id', width:55},
	 {name:'invdate',index:'invdate', width:90},
	 {name:'name',index:'name asc, invdate', width:100},
	 {name:'amount',index:'amount', width:80, align:"right"},
	 {name:'tax',index:'tax', width:80, align:"right"},
	 {name:'total',index:'total', width:80,align:"right"},
	 {name:'note',index:'note', width:150, sortable:false} ],
	 rowNum:10,
	 rowList:[10,20,30],
	 sortname: 'id',
	 viewrecords: true,
	 sortorder: "desc",
	 caption:"JSON Example"
	 */



	public function getContent(){

		$xtpl = $this->getXTemplate();


		if(empty($this->style))
			$this->style= $this->componentPath . "/css/ui.jqgrid.css";

		$xtpl->assign('style', $this->getStyle() );
			
		$xtpl->assign('requestClass', $this->getRequestClass() );
		$xtpl->assign('requestMethod', $this->getRequestMethod() );
		$xtpl->assign('requestMethodTotal', $this->getRequestMethodTotal() );

		$xtpl->assign('colNames', $this->getColNames() );
		$xtpl->assign('colModel', $this->getColModel() );
		$xtpl->assign('rowNum', $this->getRowNum() );
		$xtpl->assign('rowList', $this->getRowList() );
		$xtpl->assign('sortName', $this->getSortName() );
		$xtpl->assign('sortOrder', $this->getSortOrder() );
		$xtpl->assign('caption', $this->getCaption() );
		$xtpl->assign('height', $this->getHeight() );
		$xtpl->assign('indexColumnId', $this->getIndexColumnId() );

		$xtpl->assign('componentId', $this->getId() );
		$xtpl->assign('componentType', get_class($this) );
		
		$xtpl->parse("main");
		$content .= $xtpl->text("main");

		return $content;
	}

	public function loadItems(){

		$clazz =  $_GET['requestClass'] ;
		$method =  $_GET['requestMethod'] ;
		$methodTotal =  $_GET['requestMethodTotal'] ;
		$indexColumnId =  $_GET['indexColumnId'] ;
		//return JQgrid::getItems( $clazz, $method, $methodTotal, $indexColumnId );

	}

	public function getItems( $clazz='', $method='', $methodTotal='', $indexColumnId='' ){

		$clazz =  RastyUtils::getParamGET( 'requestClass', $clazz ) ;
		$method =  RastyUtils::getParamGET( 'requestMethod', $method ) ;
		$methodTotal =  RastyUtils::getParamGET( 'requestMethodTotal', $methodTotal ) ;
		$indexColumnId =  RastyUtils::getParamGET( 'indexColumnId', $indexColumnId ) ;
		
		$page = RastyUtils::getParamGET( 'page' ); // get the requested page
		$limit = RastyUtils::getParamGET( 'rows' ); // get how many rows we want to have into the grid
		$sidx = RastyUtils::getParamGET( 'sidx' ); // get index row - i.e. user click to sort
		$sord = RastyUtils::getParamGET( 'sord' ); // get the direction
		
		if(!$sidx) $sidx =1; // connect to the database


		$oClass = new ReflectionClass($clazz);
		$oInstance = $oClass->newInstance();

		$reflectionMethodTotal = new ReflectionMethod( get_class( $oInstance ) , $methodTotal);

		$args = array($page, $limit, $sidx, $sord);

		$total_pages = $reflectionMethodTotal->invokeArgs( $oInstance, $args );

		$reflectionMethod = new ReflectionMethod( get_class( $oInstance ) , $method);

		$items = $reflectionMethod->invokeArgs( $oInstance, $args );


		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;

		$i=0;
		foreach ($items as $item) {
			$response->rows[$i]['id']=$item[$indexColumnId];
			/*foreach ($this->getColumns() as $column) {
				//$response->rows[$i]['cell']=array();
				//$response->rows[$i]['cell'][$column] = $item[$column];

				}/
				$response->rows[$i]['cell']=array("id$i","invdate$i","name$i","amount$i","tax$i","total$i","note$i");
				*/
			$response->rows[$i]['cell'] = $item;
			$i++;
		}
		/*

		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;

		$i=0;
		while( $i < 10) {
		$response->rows[$i]['id']= $i;
		$response->rows[$i]['cell']=array("id$i","invdate$i","name$i","amount$i","tax$i","total$i","note$i");
		$i++;
		}*/
		return json_encode($response);
	}


}
?>