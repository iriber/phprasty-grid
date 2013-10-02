<?php
namespace Rasty\Grid\entitygrid;

use Rasty\Grid\entitygrid\model\GridActionModel;
use Rasty\factory\ComponentConfig;
use Rasty\factory\ComponentFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;
use Rasty\utils\ReflectionUtils;
use Rasty\utils\Logger;
use Rasty\utils\XTemplate;
use Rasty\Grid\entitygrid\model\EntityGridModel;
use Rasty\i18n\Locale;
use Rasty\app\RastyMapHelper;

use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;
use Rasty\Menu\menu\model\MenuActionOption;

/**
 * componente grilla para entities.
 *
 * @author Bernardo Iribarne (bernardo.iribarne@codnet.com.ar)
 * @since 05-08-2013
 *
 */
class EntityGrid extends RastyComponent{

	const TEXT_ALIGN_CENTER = 1;
	const TEXT_ALIGN_LEFT = 2;
	const TEXT_ALIGN_RIGHT = 3;
	
	/**
	 * modelo de la grilla
	 * @var EntityGridModel
	 */
	private $model;
	
	private $modelClazz;
	
	private $sortCallback;
	
	private $paginationCallback;
	
	private $selectRowCallback;
	
	private $filter;
	
	private $filterType;
	
	private $checkBoxes = true;
	
		
	protected function parseXTemplate(XTemplate $xtpl){

		$this->model = ReflectionUtils::newInstance( $this->getModelClazz() ); 
		
		$xtpl->assign("sortCallback", $this->getSortCallback() );
		$xtpl->assign("paginationCallback", $this->getPaginationCallback() );
		$xtpl->assign("selectRowCallback", $this->getSelectRowCallback() );
		
		$id = $this->getId();
		if( empty($id) )
			$this->setId( "grid" . $this->getFilterType() );
			
		$xtpl->assign("gridId", $this->getId() );	
		
		//generamos el filter a partir del type.
	    $componentConfig = new ComponentConfig();
	    $componentConfig->setId( "filter" );
		$componentConfig->setType( $this->getFilterType() );
		
	    $this->filter = ComponentFactory::buildByType($componentConfig, $this);
	    
		$this->filter->fill();
		
		$criteria = $this->filter->getCriteria();
		
		$entities = $this->getService()->getEntities( $criteria );
		$entitiesCount = $this->getService()->getEntitiesCount( $criteria );
		$entitiesPageCount = count($entities);
		
		$this->filter->save();
		
		$this->getModel()->setEntities( $entities );
		$this->getModel()->setTotalRows( $entitiesCount );
		
		$this->renderPagination( $xtpl, $entitiesPageCount, $entitiesCount );
		
		$this->renderActions( $xtpl );

		$this->renderHeader( $xtpl );
		
        $this->renderGridHeader( $xtpl);

        $this->renderRowsHeader( $xtpl);

        $this->renderRows( $xtpl);

        $this->renderRowsFooter( $xtpl);

        $this->renderGridFooter( $xtpl);
		
		$this->renderFooter( $xtpl );
		
		$xtpl->assign("model",  get_class( $this->getModel() ) );
	
	}

	public function getType(){
		return "EntityGrid";
	}


	public function getModel()
	{
	    return $this->model;
	}

	public function setModel($model)
	{
	    $this->model = $model;
	}
	
	public function renderActions( XTemplate $xtpl) {
        
    	$model = $this->getModel();
    	
        for ($index = 0; $index < $model->getActionsCount(); $index++) {

            $oActionModel = $model->getActionModel($index);

            $xtpl->assign('name', $oActionModel->getName());
            $xtpl->assign('label', $oActionModel->getLabel());
            $xtpl->assign('action', $oActionModel->getAction());
            $xtpl->assign('callback', $oActionModel->getCallback());
            $xtpl->assign('liClass', $oActionModel->getStyle());
            $xtpl->parse('main.action');
        }
    }
    
    public function renderHeader( XTemplate $xtpl) {
    	
    	$header = $this->getModel()->getHeaderContent();
    	
    	if(!empty ($header)){
    		$xtpl->assign('header', $header );
    		$xtpl->parse('main.header');
    	}
    	
    	
    }
    
	public function renderFooter( XTemplate $xtpl) {
    	
    	$footer = $this->getModel()->getFooterContent();
    	
    	if(!empty ($footer)){
    		$xtpl->assign('footer', $footer );
    		$xtpl->parse('main.footer');
    	}
    	
    	
    }

	public function renderGridHeader( XTemplate $xtpl) {
        
		/*
		$gridId = $this->getId();
    	$model = $this->getModel();
    	$filter = $this->getFilter();
    	
        $page = $filter->getPage();
        $orderType = $filter->getOrderType();
        $orderField = $filter->getOrderBy();
		$rowPerPage = $filter->getRowPerPage();

        $rows = $model->getTotalRows();
        $paginator = $this->getPaginator($rows, $orderType, "", "", $orderField, $page, $rowPerPage );
		//$paginator = $this->getPaginator(10, "ASC", "", "", "oid", 1);
        $this->parsePaginator($xtpl, $paginator);
        */
		$xtpl->assign("pagination", "aca va la paginacion");
		$xtpl->parse("pagination");
    }

    public function renderGridFooter( XTemplate $xtpl ) {
		
    }
    
    
	public function renderRowsHeader( XTemplate $xtpl) {

    	$gridId = $this->getId();
    	$model = $this->getModel();

    	//primero tenemos que chequear si hay grupos de encabezados.
    	//vamos a agrupar por grupos.
    	//por ahora consideramos un único nivel de grupos.
    	$groups = array();
    	for ($index = 0; $index < $model->getColumnCount(); $index++) {

            $oColumnModel = $model->getColumnModel($index);
            
            //si tiene grupo la agregamos al arreglo del grupo.
            if( $oColumnModel->hasGroup() )
            	$groups[ $oColumnModel->getGroup() ][] = $oColumnModel ;
            
    	}	
    	
    	//si hay grupos, los encabezados que no están agrupados, deben tener rowspan
    	//como vamos a considerar un única nivel de grupos, sería rowspan=2
    	$rowspan = (count( $groups ) > 0 )? 2 :1;
    	$xtpl->assign('group_levels', $rowspan);
    	
    	
    	//parseamos el primer nivel de headers.
        for ($index = 0; $index < $model->getColumnCount(); $index++) {

        	$oColumnModel = $model->getColumnModel($index);

        	
        	if( !$oColumnModel->hasGroup() ){
        	
               	$field = $oColumnModel->getField();

        		$xtpl->assign('label', Locale::localize($oColumnModel->getLabel() ));
				$xtpl->assign('group_levels', $rowspan);
	            $xtpl->assign('orderField', $field);
	            $xtpl->assign('orderLabel', $this->localize($oColumnModel->getLabel()));
	            //$orderType = CdtUtils::getParam("orderType_$gridId", "DESC");
	            $orderType = "";//$this->getFilter()->getOrderType();
	            
	            $xtpl->assign('orderTypeAsc', "ASC");
				$xtpl->assign('orderTypeDesc', "DESC");
	
	            $xtpl->assign('actionList', "cmp_grid");
	
	            $xtpl->parse('main.TH.SIMPLE');
        		$xtpl->parse('main.TH');
        	}else{
        	
        	
        		//si es la primer columna del grupo, parseamos el header del grupo.
        		$group = $groups[ $oColumnModel->getGroup() ];
        		$oFirstColumnModel = $group[0];
        		
        		if( $oFirstColumnModel->getName() == $oColumnModel->getName()  ){
        			
        			//primero mostramos el header del grupo.
        			
        			$xtpl->assign('group_label', Locale::localize( $oFirstColumnModel->getGroup() ));
        			$xtpl->assign('group_size', count( $group ) );
        			
               		$field = $oFirstColumnModel->getField();

        			$xtpl->assign('orderField', $field);
		            $xtpl->assign('orderLabel', $this->localize($oFirstColumnModel->getLabel()));
	            	$orderType = "";//$this->getFilter()->getOrderType();
		            if ($orderType == "DESC")
		                $orderType = "ASC";
		            else
		                $orderType = "DESC";
		            $xtpl->assign('orderType', $orderType);
		            
        			$xtpl->parse('main.TH.GROUP');
        			$xtpl->parse('main.TH');
        		}
        		
        	
        	}
		}
		
		//parseamos el segundo nivel de headers.
    	for ($index = 0; $index < $model->getColumnCount(); $index++) {

        	$oColumnModel = $model->getColumnModel($index);

        	
        	if( $oColumnModel->hasGroup() ){

        		//si es la primer columna del grupo, parseamos todo el grupo.
        		//sino no hacemos nada.
        		$group = $groups[ $oColumnModel->getGroup() ];
        		$oFirstColumnModel = $group[0];
        		
        		if( $oFirstColumnModel->getName() == $oColumnModel->getName()  ){

        			foreach ($group as $oSubColumnModel) {
        			
               			$field = $oSubColumnModel->getField();
        					
                		$xtpl->assign('label', Locale::localize( $oSubColumnModel->getLabel() ));
						$xtpl->assign('orderField', $field);
			            $xtpl->assign('orderLabel', $this->localize($oSubColumnModel->getLabel()));
			            $orderType = "";// $this->getGrid()->getFilter()->getOrderType();
			            if ($orderType == "DESC")
			                $orderType = "ASC";
			            else
			                $orderType = "DESC";
			            $xtpl->assign('orderType', $orderType);
			
			            $xtpl->assign('actionList', "cmp_grid");
		        			
		        			
			            $xtpl->parse('main.SUB_HEADER.TH');
		            
        			}
		            
        		}    		
        	}
		}
		
		if(count( $groups ) > 0 )
			$xtpl->parse('main.SUB_HEADER');

			
		if( $this->hasCheckboxes() )
			$xtpl->parse('main.TH_CHECKBOX');
    }

    public function renderRowsFooter( XTemplate $xtpl) {

    }

    public function renderColumn( $item, $index, XTemplate $xtpl) {
    	
    	$model = $this->getModel();
    	
    	$oColumnModel = $model->getColumnModel($index);

        $value = $model->getValue($item, $index);

        $value = $oColumnModel->getFormat()->format($value, $item);

        $cssClass = $oColumnModel->getCssClass();
        $cssStyle = $oColumnModel->getCssStyle();

        $textAlign = $oColumnModel->getTextAlign();
        
        if(!empty($textAlign)){
        
        	switch ($textAlign) {
        		case self::TEXT_ALIGN_CENTER: $cssStyle .= ";text-align:center;";
        		;break;
        		case self::TEXT_ALIGN_LEFT: $cssStyle .= ";text-align:left;";
        		;break;
        		case self::TEXT_ALIGN_RIGHT: $cssStyle .= ";text-align:right;";
        		;break;
        		default:
        			;
        		break;
        	}
        
        }
        
        $xtpl->assign('column_class', $cssClass);
        $xtpl->assign('column_style', $cssStyle);
        $xtpl->assign('value', $value);
        
        $xtpl->parse('main.row.column');
    }

	public function renderRows(XTemplate $xtpl) {

        $model = $this->getModel();

        //$this->renderRowActions("", $xtpl);

        foreach ($model->getEntities() as $item) {
            $this->renderRow($item, $xtpl);
        }
    }

    public function renderRow($item, XTemplate $xtpl) {


        $model = $this->getModel();

        //parseamos el id.
        $xtpl->assign('itemId', $model->getEntityId($item));

        for ($index = 0; $index < $model->getColumnCount(); $index++) {

        	$this->renderColumn( $item, $index, $xtpl);
        	
        }

        
        $xtpl->assign('row_class', $model->getRowStyleClass($item));
        if( $this->hasCheckBoxes() )
			$xtpl->parse('main.row.checkbox');
			
        
        $this->renderRowActions($item, $xtpl);
        
        $xtpl->parse('main.row');
    }

    
    

	public function hasCheckBoxes()
	{
	    return $this->getModel()->getHasCheckBoxes();
	}

	
	public function renderPagination( XTemplate $xtpl, $currentRows, $totalRows) {
	
		$criteria = $this->getFilter()->getCriteria();
		
		$page = $criteria->getPage();
		$rowPerPage = $criteria->getRowPerPage();
		
        if(empty($page))
        	$page = 1;
        	
		$firstPage = 1;
		
		$previousPage = ($page > 1)?$page-1:0;
		
		$lastPage = ceil($totalRows / $rowPerPage);
		
		$nextPage = ($page < $lastPage)?$page+1:0;
		
		$limitInferior = (($page-1) * $rowPerPage ) + 1;
		$limitSuperior = $limitInferior + $rowPerPage - 1;
		
		$xtpl->assign("lbl_first",  $this->localize("entitygrid.pagination.first") );
		$xtpl->assign("lbl_previous",  $this->localize("entitygrid.pagination.previous") );
		$xtpl->assign("lbl_next",  $this->localize("entitygrid.pagination.next") );
		$xtpl->assign("lbl_last",  $this->localize("entitygrid.pagination.last") );
		
		$xtpl->assign("rangeFrom",  $limitInferior );
		$xtpl->assign("rangeTo",  $currentRows );
		$xtpl->assign("rangeOf",  $this->localize("entitygrid.pagination.of") );
		
		$xtpl->assign("firstPage",  $firstPage );
		$xtpl->assign("nextPage",  $nextPage );
		$xtpl->assign("previousPage",  $previousPage );
		$xtpl->assign("lastPage",  $lastPage );
		$xtpl->assign("totalRows",  $totalRows );
		
		if( $firstPage < $page )
			$xtpl->parse("main.firstPage");
		
		if( $previousPage>0 && $previousPage < $page )
			$xtpl->parse("main.previousPage");
		
		
		if( $nextPage>0 && $nextPage > $page )
			$xtpl->parse("main.nextPage");
		
		if( $lastPage > $page )
			$xtpl->parse("main.lastPage");
		
			
		
	}
	
	public function renderRowActions( $item, XTemplate $xtpl) {

    	$model = $this->getModel();
        
        $itemId = (!empty($item))?$model->getEntityId($item): "";

        //armamos un menú con las opciones (si es que hay).
        $menuGroups = $model->getMenuGroups($item);
        
        if( count($menuGroups) > 0 ){
	        
	        //generamos el menu a partir del type.
		    $componentConfig = new ComponentConfig();
		    $componentConfig->setId( "menu_$itemId" );
			$componentConfig->setType( "Menu" );
			
		    $menu = ComponentFactory::buildByType($componentConfig, $this);
	        $menu->setMenuGroups($menuGroups);
	        
	        $xtpl->assign("actions", $menu->render());
	    	$xtpl->parse("main.row.row_actions");    	
        }
        
        
    }
    
    protected function renderRowAction(XTemplate $xtpl, GridActionModel $oActionModel, $itemId) {
    
    	$gridId = $this->getId();
    
    	$ds_action = $oActionModel->getAction();
    	
    	$bl_targetblank = $oActionModel->getOnTargetblank();
    
    	$xtpl->assign('action', $ds_action);
    
    	$xtpl->parse('main.row.row_actions.row_action');
    }
    
	public function getService()
	{
	    return $this->model->getService();
	}
	
	public function getFilter()
	{
	    return $this->filter;
	}
	
    

	public function getModelClazz()
	{
	    return $this->modelClazz;
	}

	public function setModelClazz($modelClazz)
	{
		
	    $this->modelClazz = urldecode( $modelClazz );
	}

	public function getSortCallback()
	{
	    return $this->sortCallback;
	}

	public function setSortCallback($sortCallback)
	{
	    $this->sortCallback = $sortCallback;
	}


	public function getPaginationCallback()
	{
	    return $this->paginationCallback;
	}

	public function setPaginationCallback($paginationCallback)
	{
	    $this->paginationCallback = $paginationCallback;
	}

	public function getSelectRowCallback()
	{
	    return $this->selectRowCallback;
	}

	public function setSelectRowCallback($selectRowCallback)
	{
	    $this->selectRowCallback = $selectRowCallback;
	}

	public function getFilterType()
	{
	    return $this->filterType;
	}

	public function setFilterType($filterType)
	{
	    $this->filterType = $filterType;
	}
}