<?php

require_once $_SESSION['rootfolder'] . '/DataAccess/Database2.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/DataProvider.php';

/**
 * Provider für Reports
 *
 * @author Andy Nick
 * @version 1.0.0.0
 */
class ReportProvider extends DataProvider {
    
    public function GetReportByID($ID, $tourPart) {
        $db = new Database();
        $sql = "SELECT * FROM onTour_Reports WHERE TourPart = $tourPart AND ID = $ID";
        $this->Log($sql, KLogger::DEBUG);
        return $db->query($sql);
    }

    public function GetLastReport($tourPart) {
        $db = new Database();
        $sql = "SELECT * FROM onTour_Reports WHERE TourPart = $tourPart ORDER BY CreationDate desc LIMIT 0, 1";
        $this->Log($sql, KLogger::DEBUG);
        return $db->query($sql);
    }
    
    public function GetFirstReport($tourPart) {
        $db = new Database();
        $sql = "SELECT * FROM onTour_Reports WHERE TourPart = $tourPart ORDER BY CreationDate LIMIT 0, 1";
        $this->Log($sql, KLogger::DEBUG);
        return $db->query($sql);
    }

    public function GetNextReport($currentID, $tourPart) {
        $db = new Database();
        $sql = "SELECT * FROM onTour_Reports where TourPart = $tourPart AND CreationDate > (SELECT CreationDate FROM onTour_Reports WHERE ID = $currentID) ORDER BY CreationDate LIMIT 0, 1 ";
        $this->Log($sql, KLogger::DEBUG);
        return $db->query($sql);
    }

    public function GetPreviewReport($currentID, $tourPart) {
        $db = new Database();
        $sql = "SELECT * FROM onTour_Reports where TourPart = $tourPart AND CreationDate < (SELECT CreationDate FROM onTour_Reports WHERE ID = $currentID) ORDER BY CreationDate desc LIMIT 0, 1 ";
        $this->Log($sql, KLogger::DEBUG);
        return $db->query($sql);
    }

}
