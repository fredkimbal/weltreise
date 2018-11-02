<?php

require_once $_SESSION['rootfolder'] . '/DataProvider/DataProvider.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GalleryProvider
 *
 * @author geischterfaescht
 */
class GalleryProvider extends DataProvider {

    public function GetCountries($tourPart) {
        $qry = "SELECT * FROM onTour_GalleryCountry WHERE ID in (SELECT CountryID from onTour_Gallery WHERE TourPart = $tourPart Group by CountryID)";
        $db = new Database();
        return $db->query($qry);
    }

    public function GetAllCountries($tourPart) {
        $qry = "SELECT * FROM onTour_GalleryCountry";
        $db = new Database();
        return $db->query($qry);
    }

    public function GetNewestCountry($tourPart) {
        $qry = "SELECT CountryID, onTour_GalleryCountry.CountryName "
                . "FROM onTour_Gallery "
                . "LEFT JOIN onTour_GalleryCountry on onTour_Gallery.CountryID = onTour_GalleryCountry.ID "
                . "WHERE TourPart = $tourPart "
                . "ORDER BY GalleryDate DESC "
                . "LIMIT 1 ";
        $db = new Database();
        $this->Log($qry, KLogger::DEBUG);
        return $db->query($qry);
    }

    public function GetgalleryByCountry($countryID, $tourpart) {
        $qry = "SELECT * FROM onTour_Gallery WHERE CountryID = $countryID AND TourPart = $tourpart";
        $db = new Database();
        return $db->query($qry);
    }
    
    public function GetLastTenGalleries() {
        $qry = "SELECT * FROM onTour_Gallery ORDER BY ID desc LIMIT 0, 10";
        $db = new Database();
        return $db->query($qry);
    }

    public function GetCountryByID($countryID) {
        $qry = "SELECT * FROM onTour_GalleryCountry WHERE ID = $countryID";
        $db = new Database();
        return $db->query($qry);
    }

    /**
     * Gibt ein Array mit den Bildern, der Bild ID und der Anzahl Kommentare einer Gallerie zur�ck
     * @param int $gallerieId ID der Gallerie, welche angezeigt werden soll
     */
    public function GetImagesByGallerieId($gallerieId) {
        $qry = "Select PicPath, ID                  
                FROM onTour_Pics
                WHERE GalleryID = " . $gallerieId;

        if (isset($count)) {
            $qry = $qry . ' Limit ' . $count;
        }
        $db = new Database();
        $result = $db->query($qry);
        return $result;
    }

    public function GetGalleryByID($gallerieID) {
        $qry = "SELECT * FROM onTour_Gallery WHERE ID = $gallerieID";
        $db = new Database();
        return $db->query($qry);
    }

    public function GetCountryByGallerieID($gallerieID) {
        $qry = "SELECT * FROM onTour_GalleryCountry WHERE ID in (Select CountryID from onTour_Gallery where ID = $gallerieID)";
        $db = new Database();
        return $db->query($qry);
    }
    
    public function GetImageByID($id, $reportID){
        $qry = "SELECT p.ID, p.GalleryID, p.PicPath, rp.ReportID, rp.PicID, rp.Caption 
                FROM onTour_Pics as p
                LEFT JOIN onTour_reportpics as rp ON p.ID = rp.PicID
                WHERE p.ID = $id AND (ReportID = $reportID OR ReportID is null)";
        
        $db = new Database();        
        $result = $db->query($qry);
        return count($result)>0 ? $result[0] : null;        
    }

    
    public function SaveImageToReport($picID, $reportID, $caption){
        $qry = "INSERT INTO onTour_reportpics (ReportID, PicID, Caption) VALUES ($reportID, $picID, '$caption');";
        $db = new Database();
        $db->iquery($qry);
    }
}
