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
        $reportProvider = new ReportProvider();        
        $data = $reportProvider->GetLastReport(0)[0];
        $this->displayReport($data);
    }
    
    public function ShowFirstReport($args) {
        $reportProvider = new ReportProvider();        
        $data = $reportProvider->GetFirstReport(0)[0];
        $this->displayReport($data);
    }
    
    public function ShowLastReport($args) {
        $reportProvider = new ReportProvider();        
        $data = $reportProvider->GetLastReport(0)[0];
        $this->displayReport($data);
    }
    
    public function ShowReportByID($args) {        
        $reportProvider = new ReportProvider();  
        $id = array_shift($args);
        $data = $reportProvider->GetReportByID($id, 0)[0];        
        $this->displayReport($data);
    }
    
    private function displayReport($data){
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Reports');
        $reportProvider = new ReportProvider();        
        
        $smarty->assign("data", $data);
        
        
        $preview = $reportProvider->GetPreviewReport($data['ID'], 0)[0];
        $next = $reportProvider->GetNextReport($data['ID'], 0)[0];
        
        if(count($preview) > 0){
            $smarty->assign("preview", $preview);
        }
        if(count($next) > 0){
            $smarty->assign("next", $next);
        }
        
        $phpdate = strtotime( $data['CreationDate'] );        
        $smarty->assign("date",  date( 'd.m.Y H:i:s', $phpdate ));
        $smarty->display('Reports.tpl');
    }
    

}
