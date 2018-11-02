<?php

require_once $_SESSION['rootfolder'] . '/DataProvider/DataProvider.php';

/**
 * Description of GpxProvider
 *
 * @author geischterfaescht
 */
class GpxProvider extends DataProvider {
    
    public function getGpxInRange($latMin, $latMax, $longMin, $longMax, $tourpart){
        
        $sql = "SELECT * FROM onTour_GPX 
                WHERE 
                        (LatMin > $latMin AND LatMin < $latMax 
                        OR LatMax > $latMin AND LatMax < $latMax
                        OR LatMin < $latMin AND LatMax > $latMax)
                    AND 
                        (LongMin > $longMin AND LongMin < $longMax 
                        OR LongMax > $longMin AND LongMax < $longMax
                        OR LongMin < $longMin AND LongMax > $longMax) "
                . "AND TourPart = $tourpart ";
        
        $this->Log($sql, KLogger::DEBUG);
        
        $db = new Database();
            
        
        return $db->query($sql);
    }
    
    public function insertTrackPoint($lat, $long, $ele, $time, $trackID){
        $sql = "INSERT INTO onTour_GPXTrackPoints (Lat, `Long`, Ele, `Time`, TrackID) VALUES ('$lat', '$long', '$ele', '$time', '$trackID')";
        $db = new Database();        
        $db->iquery($sql);
    }
    
     public function insertTrack($time, $distance, $ascent, $tourPart){
        $sql = "INSERT INTO onTour_GPX (TrackDate, `Distance`, Ascent, TourPart) VALUES ('$time', '$distance', '$ascent', $tourPart)";
        $db = new Database();        
        $db->iquery($sql);
        return $db->GetLastID();
     }
     
     public function updateMaxCoords($minLat, $maxLat, $minLong, $maxLong, $trackID){
         $sql = "UPDATE onTour_GPX SET LatMin = '$minLat', LatMax = '$maxLat', LongMin = '$minLong', LongMax = '$maxLong' WHERE ID = $trackID";
         $db = new Database();
         $db->iquery($sql);
     }
     
     public function getAllTracksByTourPart($tourPart){
         $sql = "SELECT * FROM onTour_GPX WHERE TourPart = $tourPart"
                . " ORDER BY TrackDate desc";
         $db = new Database();
         return $db->query($sql);
     }
     
     public function GetTrackPointsByTrackID($trackID){
         $sql = "SELECT Lat, `Long`, Ele FROM `onTour_GPXTrackPoints` WHERE TrackID = $trackID ORDER BY ID";
         $db = new Database();
         return $db->query($sql);
     }
     
     public function GetLastTrackPoint($tourPart){
         $sql = "SELECT Lat, `Long` FROM `onTour_GPXTrackPoints` as gpxp LEFT JOIN `onTour_GPX` as gpx on gpx.ID = gpxp.TrackID  WHERE TourPart = $tourPart ORDER BY gpxp.ID desc LIMIT 1";
         $db = new Database();
         return $db->query($sql);
     }
     
     public function GetFirstTrackPointByTrack($tourPart, $id){
         $sql = "SELECT Lat, `Long` FROM `onTour_GPXTrackPoints` WHERE trackID = $id ORDER BY ID asc LIMIT 1";
         $db = new Database();
         return $db->query($sql);
     }
     public function GetLastTrackPointByTrack($tourPart, $id){
         $sql = "SELECT Lat, `Long` FROM `onTour_GPXTrackPoints` WHERE trackID = $id ORDER BY ID desc LIMIT 1";
         $db = new Database();
         return $db->query($sql);
     }
}
