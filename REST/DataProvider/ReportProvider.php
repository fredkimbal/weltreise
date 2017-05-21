<?php
require_once $_SESSION['rootfolder'] . '/DataAccess/Database2.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/DataProvider.php';

/**
 * Provider für Reports
 *
 * @author Andy Nick
 * @version 1.0.0.0
 */
class ReportProvider extends DataProvider{

    public function GetLastReport($tourPart) {
        
        $db = new Database();
        $sql = "SELECT * FROM onTour_Reports WHERE TourPart = $tourPart ORDER BY CreationDate desc LIMIT 0, 1";
        
        $this->Log($sql, KLogger::DEBUG);
        
        return $db->query($sql);
    }
    
}
