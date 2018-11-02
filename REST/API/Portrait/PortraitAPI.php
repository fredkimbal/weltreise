<?php

require_once $_SESSION['rootfolder'] . '/API/SmartyAPI.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/ReportProvider.php';

/**
 * Description of FotosAPI
 *
 * @author Andy
 */
class PortraitAPI extends SmartyAPI {

    public function __construct($request, $origin) {
        parent::__construct($request);
        //Security Handling        
    }

    public function Luana($args) {
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Portrait');
        $smarty->display("Luana.tpl");
    }
    
    public function Andy($args) {
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Portrait');
        $smarty->display("Andy.tpl");
    }

    

}
