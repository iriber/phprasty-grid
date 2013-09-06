<?php

namespace Rasty\Grid\conf;
use Rasty\app\LoadRasty;

/**
 * Configuración para el módulo rasty grid
 * 
 * @author bernardo
 * @since 06/09/2013
 */
class RastyGridConfig {
	
    
    /**
     * inicializamos phprasty grid
     */
    static public function initialize(){

    	$sourcesPath = dirname(__DIR__) . "/";
    	LoadRasty::getInstance()->loadXml(dirname(__DIR__) . '/conf/rasty.xml',  $sourcesPath );
    	    	
    }
}