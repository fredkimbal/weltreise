<?php

require_once $_SESSION['rootfolder'] . '/DataProvider/DataProvider.php';

/**
 * Provider für Reports
 *
 * @author Andy Nick
 * @version 1.0.0.0
 */
class ReportProvider extends DataProvider {

    public function GetReportByID($ID) {
        $db = new Database();
        $qry = "SELECT * FROM onTour_Reports WHERE ID = $ID";
        $this->Log($qry, KLogger::DEBUG);
        return $db->query($qry);
    }

    public function GetLastReport($tourPart) {
        $db = new Database();
        $qry = "SELECT * FROM onTour_Reports WHERE TourPart = $tourPart ORDER BY CreationDate desc LIMIT 0, 1";
        $this->Log($qry, KLogger::DEBUG);
        return $db->query($qry);
    }

    public function GetFirstReport($tourPart) {
        $db = new Database();
        $qry = "SELECT * FROM onTour_Reports WHERE TourPart = $tourPart ORDER BY CreationDate LIMIT 0, 1";
        $this->Log($qry, KLogger::DEBUG);
        return $db->query($qry);
    }

    public function GetFirstReportID($tourPart) {
        $db = new Database();
        $qry = "SELECT ID FROM onTour_Reports WHERE TourPart = $tourPart ORDER BY CreationDate LIMIT 0, 1";
        $this->Log($qry, KLogger::DEBUG);
        return $db->query($qry);
    }

    public function GetLastReportID($tourPart) {
        $db = new Database();
        $qry = "SELECT ID FROM onTour_Reports WHERE TourPart = $tourPart ORDER BY CreationDate desc LIMIT 0, 1";
        $this->Log($qry, KLogger::DEBUG);
        return $db->query($qry);
    }

    public function GetNextReport($currentID, $tourPart) {
        if (!isset($currentID)) {
            return 0;
        } else {
            $db = new Database();
            $qry = "SELECT * FROM onTour_Reports where TourPart = $tourPart AND CreationDate > (SELECT CreationDate FROM onTour_Reports WHERE ID = $currentID) ORDER BY CreationDate LIMIT 0, 1 ";
            $this->Log($qry, KLogger::DEBUG);
            return $db->query($qry);
        }
    }

    public function GetPreviewReport($currentID, $tourPart) {
        if (!isset($currentID)) {
            return 0;
        } else {
            $db = new Database();
            $qry = "SELECT * FROM onTour_Reports where TourPart = $tourPart AND CreationDate < (SELECT CreationDate FROM onTour_Reports WHERE ID = $currentID) ORDER BY CreationDate desc LIMIT 0, 1 ";
            $this->Log($qry, KLogger::DEBUG);
            return $db->query($qry);
        }
    }

    public function GetPicsByReportID($id) {
        if (!isset($id)) {
            return 0;
        } else {
            $db = new Database();
            $qry = "SELECT PicPath, Caption FROM  onTour_reportpics as rpic LEFT JOIN onTour_Pics as pic ON pic.ID = rpic.PicID WHERE rpic.ReportID = $id";
            $this->Log($qry, KLogger::DEBUG);
            return $db->query($qry);
        }
    }

    public function GetLastTenReport() {
        $db = new Database();
        $qry = "SELECT * FROM onTour_Reports ORDER BY CreationDate desc LIMIT 0, 10 ";
        $this->Log($qry, KLogger::DEBUG);
        return $db->query($qry);
    }

}
