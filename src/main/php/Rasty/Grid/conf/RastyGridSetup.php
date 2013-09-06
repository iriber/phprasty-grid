<?php

namespace Rasty\Grid\conf;
use Rasty\app\LoadRasty;

class RastyGridSetup {
	
    
    /**
     * inicializamos phprasty grid
     */
    static public function initialize( $sourcesPath="", $rastyHome="" ){
		
    	$rastyLoader = LoadRasty::getInstance();
    	$rastyLoader->loadXml(dirname(__DIR__) . '/conf/rasty.xml',  $sourcesPath, $rastyHome );
    	    	
    }
}