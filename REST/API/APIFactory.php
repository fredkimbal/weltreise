<?php
require_once './API/Fotos/FotosAPI.php';
require_once './API/Reports/ReportsAPI.php';
require_once './API/Map/MapAPI.php';
require_once './API/Startseite/StartseiteAPI.php';
require_once './API/GPX/GPXAPI.php';
require_once './API/Notification/NotificationAPI.php';
require_once './API/Comments/CommentsAPI.php';
require_once './API/FotoAdmin/FotoAdminAPI.php';
require_once './API/ReportAdmin/ReportAdminAPI.php';
require_once './API/Portrait/PortraitAPI.php';

/**
 * Factory Klasse, welche die API anhand des Request Types zurückgibt.
 *
 * @author Andy
 */
class APIFactory {
    public static function GetAPI($endpoint, $request, $origin){ 
        if($endpoint === "Fotos"){
            return new FotosAPI($request, $origin);
        }    
        if($endpoint === "Reports"){
            return new ReportsAPI($request, $origin);
        }
        if($endpoint === "Map"){
            return new MapAPI($request, $origin);
        }
        if($endpoint === "Startseite"){
            return new StartseiteAPI($request, $origin);
        }
        if($endpoint === "GPX"){
            return new GPXAPI($request, $origin);
        }
        if($endpoint === "Notification"){
            return new NotificationAPI($request, $origin);
        }
        if($endpoint === "Comment"){
            return new CommentsAPI($request, $origin);
        }
        if($endpoint === "I_FotoAdmin"){
            return new FotoAdminAPI($request, $origin);
        }
        if($endpoint === "I_ReportAdmin"){
            return new ReportAdminAPI($request, $origin);
        }
        if($endpoint === "Portrait"){
            return new PortraitAPI($request, $origin);
        }
        return null;
    }
}
