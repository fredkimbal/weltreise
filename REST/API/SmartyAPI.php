<?php

require_once $_SESSION['rootfolder'] . '/API/API.php';

/**
 * Description of SmartyAPI
 *
 * @author Andy
 */
class SmartyAPI extends API {

    /**
     * Konstruktor
     * @param type $request
     * @param type $origin
     */
    public function __construct($request) {
        

        parent::__construct($request);
       
        
        //Security Handling        
    }

    protected function createSmarty($path) {

        $smarty = new Smarty();

        $smarty->template_dir = $_SESSION['rootfolder'] . '/API/' . $path . '/templates/';
        $smarty->compile_dir = $_SESSION['rootfolder'] . '/API/' . $path . '/templates_c/';
        $smarty->config_dir = $_SESSION['rootfolder'] . '/API/' . $path . '/configs/';
        $smarty->cache_dir = $_SESSION['rootfolder'] . '/API/' . $path . '/cache/';

        return $smarty;
    }

}
