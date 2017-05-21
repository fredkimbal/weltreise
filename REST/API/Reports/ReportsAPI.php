<?php

require_once $_SESSION['rootfolder'] . '/API/SmartyAPI.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/ReportProvider.php';
/**
 * Description of FotosAPI
 *
 * @author Andy
 */
class ReportsAPI extends SmartyAPI {

    public function __construct($request, $origin) {
        parent::__construct($request);
        //Security Handling        
    }

    public function Show($args) {
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Reports');  
        
        $reportProvider = new ReportProvider();
        $data = $reportProvider->GetLastReport(0)[0];
        
        
        
        $smarty->assign("data", $data);
        
        $smarty->display('Reports.tpl');
    }
}
