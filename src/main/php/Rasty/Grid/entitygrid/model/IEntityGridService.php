<?php
namespace Rasty\Grid\entitygrid\model;

/**
 * Servicio para obtener las entities para la grilla
 *
 * @author Bernardo Iribarne (bernardo.iribarne@codnet.com.ar)
 * @since 05-08-2013
 *
 */
interface IEntityGridService{

	/**
	 * recupera las entities
	 * @param $params
	 */
	function getEntities($filter);
	
	/**
	 * retorna la cantidad total de entities.
	 * @param $params
	 */
	function getEntitiesCount($filter);
	
}