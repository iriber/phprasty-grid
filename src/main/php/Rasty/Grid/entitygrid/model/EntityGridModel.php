<?php
namespace Rasty\Grid\entitygrid\model;

use Rasty\utils\RastyUtils;
use Rasty\utils\ReflectionUtils;
use Rasty\conf\RastyConfig;
use Rasty\i18n\Locale;

/**
 * Modelo para la grilla
 *
 * @author Bernardo Iribarne (bernardo.iribarne@codnet.com.ar)
 * @since 05-08-2013
 *
 */
abstract class EntityGridModel{

	private $entities;
	
	private $totalRows;
	
	private $columnsModel;
	
	private $filtersModel;
	
	private $actionsModel;
	
	private $rowActionsModel;
	
	private $hasCheckboxes;

	public function __construct(){
		
		$this->entities = array();
		$this->columnsModel = array();
		$this->filtersModel = array();
		$this->actionsModel = array();
		$this->rowActionsModel = array();
		$this->setHasCheckboxes( true );
	}

	/**
	 * servicio para las entities.
	 * @var IEntityGridService
	 */
	public abstract function getService(); 
	
	//public abstract function getRenderer(); 
	
	/**
	 * 
	 */
	public abstract function getFilter();
	
	public function setEntities( array $items){
		$this->entities = $items;
	}

	public function setColumnsModel( $model ){
		$this->columnsModel = $model;
	}

	public function getColumnCount( ){
		return count($this->columnsModel);
	}

	public function addColumn( GridColumnModel $column ){
		$this->columnsModel[] = $column;
	}

	public function getColumnModel( $columnIndex ){

		return $this->columnsModel[$columnIndex];
	}
	
	public function getColumnModelByName( $name ){

		foreach ($this->columnsModel as $oColumnModel) {
				
			if( $oColumnModel->getName() == $name )
			return $oColumnModel;
		}
	}

	/**
	 * le setea un group a un conjunto de columnas
	 * @param string $ds_group nombre del group
	 * @param array $columnNames arreglo con los nombres de las columnas
	 */
	public function setGroupToColumns( $ds_group, array $columnNames ){
	
		foreach ($columnNames as $name) {
			
			$oColumnModel = $this->getColumnModelByName($name);
			$oColumnModel->setGroup( $ds_group );
		}
		
	}

	public function getValueAt($rowIndex, $columnIndex){
		$oObject = $this->entities[$rowIndex];
		return $this->getValue($oObject, $columnIndex);
	}

	public function getEntities(){
		return $this->entities;
	}

	public function getRowsCount(){
		return count( $this->entities );
	}


	public function getValue($anObject, $columnIndex){

		if( $anObject == NULL )
			return "";
			
		$cModel = $this->getColumnModel( $columnIndex );
		
		if( $cModel != null ){
			$columnName = $this->getColumnModel( $columnIndex )->getField();
			//return CdtReflectionUtils::doGetter( $anObject, $columnName );
	
			//pueden ser varios getters, separados por ",".
			$getters = explode(",", $columnName );
			
			$values = array();
			foreach ($getters as $getter) {
				$values[] = ReflectionUtils::doGetter( $anObject, trim($getter) );
			}	

			if( count($values)>1)
				return implode(" ", $values );
			else
				return $values[0];	
		}else{
			return "empty $columnIndex";			
		}
		
	}


	public function getEntityId( $anObject ){
			
		return $this->getValue( $anObject, 0);
			
	}

	public function getDefaultOrderField(){
		return $this->getColumnModel( 0 )->getField();
	}

	public function getDefaultOrderType(){
		return "DESC";
	}
	
	public function getDefaultFilterField(){
		return $this->getFilterModel( 0 )->getName();
	}

	public function buildGridColumnModel( $name, $label, $width, $format=null){
		$oColumn = new GridColumnModel();
		$oColumn->setField( $name );
		$oColumn->setName( $name );
		$oColumn->setLabel( $label );
		$oColumn->setVisible( true );
		$oColumn->setWidth( $width );

		if( empty( $format ))
			$format = new GridValueFormat();
			
		$oColumn->setFormat( $format );

		$this->addColumn( $oColumn );

	}

	public function buildFilterModel( $name, $label, $width, $format=null, $type="", $bl_hidden=false, $ds_value="", $ds_operator="LIKE"){

		$oFilter = new GridFilterModel();
		$oFilter->setField( $name );
		$oFilter->setName( $name );
		$oFilter->setLabel( $label );
		$oFilter->setIsHidden( $bl_hidden );
		$oFilter->setValue( $ds_value );
		$oFilter->setType( $type );
			
		if(empty($ds_operator))
		$ds_operator="LIKE";
		$oFilter->setOperator( $ds_operator );

		if( empty( $format ))
			$format = new GridValueFormat();
			
		$oFilter->setFormat( $format );

		$this->addFilter( $oFilter );
	}

	public function buildFilterHiddenModel( $name, $ds_value="", $ds_operator="="){

		if(empty($ds_operator))
		$ds_operator="=";		
		$this->buildFilterModel( $name, $name, 15, null, null, true, $ds_value, $ds_operator);			
	}

	public function buildModel( $name, $label, $width, $format=null, $type="", $bl_hidden=false, $ds_value="", $ds_operator="LIKE"){
		if(empty($ds_operator))
		$ds_operator="LIKE";

		$this->buildGridColumnModel( $name, $label, $width, $format );		
		$this->buildFilterModel( $name, $label, $width, $format, $type, $bl_hidden, $ds_value, $ds_operator );

	}

	public function buildAction( $action, $name, $label, $ds_image, $ds_style,  $bl_multiple=false, $ds_callback=""){
		$oAction = new GridActionModel();
		$oAction->setAction( $action );
		$oAction->setName( $name );
		$oAction->setLabel( $label );
		$oAction->setIsMultiple( $bl_multiple );
		$oAction->setImage( $ds_image );
		$oAction->setStyle( $ds_style );
		$oAction->setCallback( $ds_callback );
		$this->addAction( $oAction );
	}

	public function buildRowAction( $action, $name, $label, $ds_image, $ds_style="", $ds_callback="", $bl_multiple=false, $confirmMsg="", $isPopup=false, $nu_heightPopup=500, $nu_widthPopup=750){
		$oAction = new GridActionModel();
		$oAction->setAction( $action );
		$oAction->setName( $name );
		$oAction->setLabel( $label );
		$oAction->setIsMultiple( $bl_multiple );
		$oAction->setImage( $ds_image );
		$oAction->setStyle( $ds_style );
		$oAction->setCallback( $ds_callback );
		$oAction->setConfirmationMsg( $confirmMsg );
		$oAction->setOnPopUp( $isPopup );
		$oAction->setHeightPopup(  $nu_heightPopup );
		$oAction->setWidthPopup(  $nu_widthPopup );
		return $oAction ;
	}

	public function getFiltersCount( ){
		return count($this->filtersModel);
	}

	public function addFilter( GridFilterModel $filter ){
		$this->filtersModel[] = $filter;
	}

	public function getFilterModel( $index ){

		return $this->filtersModel[ $index ];
	}

	public function getFilterModelByName( $name ){
		foreach ($this->filtersModel as $oFilterModel) {				
			if( $oFilterModel->getName() == $name )
			return $oFilterModel;
		}
	}

	public function getTotalRows()
	{
		return $this->totalRows;
	}

	public function setTotalRows($totalRows)
	{
		$this->totalRows = $totalRows;
	}

	public function getColumnsModel()
	{
		return $this->columnsModel;
	}

	public function getFiltersModel()
	{
		return $this->filtersModel;
	}

	public function setFiltersModel($filtersModel)
	{
		$this->filtersModel = $filtersModel;
	}


	public function getActionsModel()
	{
		return $this->actionsModel;
	}

	public function setActionsModel($actionsModel)
	{
		$this->actionsModel = $actionsModel;
	}

	public function getActionsCount( ){
		return count($this->actionsModel);
	}

	public function addAction( GridActionModel $action ){
		$this->actionsModel[] = $action;
	}

	public function getActionModel( $index ){

		return $this->actionsModel[$index];
	}

	public function getRowActionsModel( $item ){
		return $this->rowActionsModel;
	}

	public function setRowActionsModel($actionsModel)
	{
		$this->rowActionsModel = $actionsModel;
	}

	public function getRowActionsCount( ){
		return count($this->rowActionsModel);
	}

	public function addRowAction( GridActionModel $action ){
		$this->rowActionsModel[]  = $action ;
	}

	public function getRowActionModel( $index ){

		return $this->rowActionsModel[ $index ];
	}

	protected function getDefaultRowActions($item, $ds_entityName, $ds_entityLabel, $view=true, $update=true, $delete=true, $bl_multiple_delete=false, $nu_heightPopup=500, $nu_widthPopup=750 ){
		$actions = array();

		if( $view)
		$actions[] = $this->buildViewAction( $item, $ds_entityName, $ds_entityLabel, $nu_heightPopup, $nu_widthPopup ) ;

		if( $update )
		$actions[] = $this->buildUpdateAction( $item, $ds_entityName, $ds_entityLabel ) ;

		if( $delete )
		$actions[] = $this->buildDeleteAction( $item, $ds_entityName, $ds_entityLabel, $this->getMsgConfirmDelete( $item ), $bl_multiple_delete ) ;

		return $actions;

	}

	protected function buildViewAction( $item, $ds_entityName, $ds_entityLabel, $nu_heightPopup=500, $nu_widthPopup=750){
		$action = $this->buildRowAction( "view_$ds_entityName", "view_$ds_entityName", "entitygrid.view" , "entitygrid.view.img", "view", "", false, "", true, $nu_heightPopup, $nu_widthPopup ) ;
		return $action;
	}

	protected function buildUpdateAction( $item, $ds_entityName, $ds_entityLabel){
		$action = $this->buildRowAction( "update_" . $ds_entityName . "_init", "update_$ds_entityName" . "_init", "entitygrid.edit", "entitygrid.edit.img", "edit", "", false, "", false ) ;
		return $action;
	}


	protected function buildDeleteAction( $item, $ds_entityName, $ds_entityLabel, $msg, $bl_multiple_delete){
		$action =  $this->buildRowAction( "delete_$ds_entityName", "delete_$ds_entityName", "entitygrid.delete" , "entitygrid.delete.img", "delete", "delete_items('delete_$ds_entityName')", $bl_multiple_delete, $msg, false) ;
		return $action;
	}

	protected function getMsgConfirmDelete( $item ){
		if(!empty($item)){
			$id = $this->getEntityId( $item );
		}else{
			$id="";
		}

		$msg = "entity.delete.question";
		$params[] = $id;
		$msg = RastyUtils::formatMessage($msg, $params);

		return RastyUtils::quitarEnters($msg);
	}

	public function resetActions(){
		$this->actionsModel = array();
	}

	public function resetRowActions(){
		$this->rowActionsModel = array();
	}
	public function resetFiltersModels(){
		$this->filtersModel = array();
	}

	public function setFilterModelOptions( $filterName, $options, $type){
		$oFilterModel = $this->getFilterModelByName( $filterName );
		$oFilterModel->setItems( $options );
		$oFilterModel->setType( $type );
	}

	public function enhanceCriteria( ){}
	
	public function getRowStyleClass( $item ){
		return "grid_row_class";
	}
	
	public function getHeaderContent(){
		return "";
	}
	
	public function getFooterContent(){
		return "";
	}
	
	public function buildExportPdfAction( $gridId ){
	
		$callback = "export_"  . $gridId . "( 'cmp_grid_pdf' ); return false;";
		
		$this->buildAction( "none", "pdf", CDT_UI_LBL_EXPORT_PDF, "image", "pdf", false, $callback );	
	}
	
	public function buildExportXlsAction( $gridId ){
	
		$callback = "export_"  . $gridId . "( 'cmp_grid_xls' ); return false;"; 
			
		$this->buildAction( "none", "xls", CDT_UI_LBL_EXPORT_XLS, "image", "excel", false, $callback );
			
	}
	
	public function getExportTitle(){
		return $this->getTitle();
	}



	public function getHasCheckboxes()
	{
	    return $this->hasCheckboxes;
	}

	public function setHasCheckboxes($hasCheckboxes)
	{
	    $this->hasCheckboxes = $hasCheckboxes;
	}
	
	/**
	 * opciones de menú dado el item
	 * @param unknown_type $item
	 */
	public function getMenuGroups( $item ){
	
		return array();
	}

	public function localize($keyMessage){
		return Locale::localize( $keyMessage );
	}
	
	public function getWebPath(){
		
		return RastyConfig::getInstance()->getWebPath();
	}
	
}