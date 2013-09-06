<?php
namespace Rasty\Grid\entitygrid\model;

/**
 * colabora en la creación de columnos del grid
 * 
 * @author Bernardo Iribarne (bernardo.iribarne@codnet.com.ar)
 * @since 05-08-2013
 *
 */
use Rasty\Grid\entitygrid\EntityGrid;

class GridModelBuilder{


	public static function buildColumn( $name, $label, $width, $textAlign=EntityGrid::TEXT_ALIGN_LEFT, $format=null){
		
		$oColumn = new GridColumnModel();
		$oColumn->setField( $name );
		$oColumn->setName( $name );
		$oColumn->setLabel( $label );
		$oColumn->setVisible( true );
		$oColumn->setWidth( $width );

		if( empty( $format ))
			$format = new GridValueFormat();
			
		$oColumn->setFormat( $format );
		$oColumn->setTextAlign($textAlign);
		
		return $oColumn;
	}
	
	public static function buildFilterModelFromColumn( GridColumnModel $columnModel, $type="", $bl_hidden=false, $ds_value=""){

		return self::buildFilterModel($columnModel->getName(), $columnModel->getLabel(), $columnModel->getFormat(), $type, $bl_hidden, $ds_value);
	}
	
	public static function buildFilterModel( $name, $label, $format=null, $type="", $bl_hidden=false, $ds_value=""){

		$oFilter = new GridFilterModel();
		$oFilter->setField( $name );
		$oFilter->setName( $name );
		$oFilter->setId( $name );
		$oFilter->setLabel( $label );
		$oFilter->setIsHidden( $bl_hidden );
		$oFilter->setValue( $ds_value );

		if(empty($type))
			$type= GridFilterModel::FILTER_TYPE_STRING ;
		$oFilter->setType( $type );
			
		if( empty( $format ))
			$format = new GridValueFormat();
			
		$oFilter->setFormat( $format );

		return $oFilter;
	}
	
}