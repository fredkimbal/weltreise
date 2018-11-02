<?php

require_once $_SESSION['rootfolder'] . '/DataAccess/Database2.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/DataProvider.php';

require_once $_SESSION['rootfolder'] . '/DataProvider/FotoProvider/AdminFotosFunctions.php';

/**
 * Diese Klasse stellt Methoden für die Gallerie Administration zur Verfügung
 *
 * @author Andy
 * @version 1.3.0.2
 * 
 * 02.05.2017 - na   Neue Methode GetTop100Events hinzugefügt
 * 19.05.2017 - na   Neue Methoden für die Admin Seiten hinzugefügt
 *                   Admin Methoden in AdminFotoFunctions.php ausgelagert   
 */
class FotoProvider extends DataProvider {

    const FOTO_FOLDER = '/../gallery';
    const FOTO_FOLDER_DISPLAY = '/gallery';
    
    private $database;

    /**
     * Konstruktor, initialisiert Database Objekt
     */
    public function __construct() {
        $this->database = new Database();
    }

    public function UploadPictures($request) {
        $admin = new AdminFotosFunctions();
        return $admin->UploadPictures($request);
    }

    /**
     * Gibt das n�chste zuzuteilende Bild zur�ck
     * @return type 
     */
    public function GetNextUnallocatePicID() {
        $sql = "SELECT PICS_KEY 
            FROM GAL_PICS 
            WHERE GAL_PICS.PICS_MPIC is null 
            ORDER BY PICS_KEY DESC
            LIMIT 0,1";
        $resultArray = $this->database->query($sql);
        return $resultArray[0][0];
    }    

    /**
     * Erstellt die Verkn�pfung zwischen Pic und Mitglied
     * @param type $memberId
     * @param type $picId
     */
    public function MemberToPic($memberId, $picId) {
        $sql = "INSERT INTO MPIC (MPIC_MITG, MPIC_PIC) VALUES (" . $memberId . ", " . $picId . ")";
        $this->database->iquery($sql);
    }

    /**
     * Markiert ein Bild als zugeteilt
     * @param type $picId
     */
    public function MarkPic($picId) {
        $sql = "UPDATE GAL_PICS SET PICS_MPIC = 1 WHERE PICS_KEY = " . $picId;
        $this->database->iquery($sql);
    }

    /**
     * Gibt ein Array mit den Bildern, der Bild ID und der Anzahl Kommentare einer Gallerie zur�ck
     * @param int $gallerieId ID der Gallerie, welche angezeigt werden soll
     */
    public function GetImagesByGallerieId($gallerieId, $count) {
        $sql = "Select PICS_ANZ, PICS_KEY, 
                    (Select Count(*) from GAL_KOM where KOM_PIC = PICS_KEY) as KOM_COUNT
                FROM GAL_PICS
                WHERE PICS_EVENT = " . $gallerieId;

        if (isset($count)) {
            $sql = $sql . ' Limit ' . $count;
        }

        $result = $this->database->query($sql);
        return $result;
    }

    /**
     * Selektiert die 90 neusten Kommentare
     * @return type
     */
    public function GetNewestComments() {
        $sql = "Select PICS_ANZ, PICS_KEY, 
                    (Select Count(*) from GAL_KOM where KOM_PIC = PICS_KEY) as KOM_COUNT
                FROM (Select DIstinct KOM_PIC FROM (Select KOM_PIC from GAL_KOM Order By KOM_DATE desc Limit 90) as uhu ) as KOM 
                LEFT JOIN GAL_PICS ON KOM.KOM_PIC = GAL_PICS.PICS_KEY";
        $result = $this->database->query($sql);
        return $result;
    }

    /**
     * Gibt den Pfad der Bilder einer Gallerie zur�ck
     * @param type $gallerieId
     * @return 
     */
    public function GetGalleriePath($gallerieId, $withoutBasePath = false) {
        $sql = "SELECT EVENT_PATH FROM GAL_EVENT WHERE GAL_EVENT.EVENT_KEY = " . $gallerieId . " LIMIT 0,1";
        return $this->getGalleryPathBySql($sql, $withoutBasePath);
    }

    /**
     * Gibt den Pfad eines Bildes anhand der ID zurück
     * @param type $gallerieId
     * @return 
     */
    public function GetGalleriePathByPicID($picId, $withoutBasePath = false) {
        $sql = "SELECT EVENT_PATH FROM GAL_PICS LEFT JOIN GAL_EVENT ON GAL_PICS.PICS_EVENT = GAL_EVENT.EVENT_KEY
                WHERE GAL_PICS.PICS_KEY =  " . $picId;
        return $this->getGalleryPathBySql($sql, $withoutBasePath);
    }
    
    private function getGalleryPathBySql($sql, $withoutBasePath){
        $this->Log($sql, KLogger::DEBUG);
        $resultArray = $this->database->query($sql);
        
        $basePath = FOTO_FOLDER_DISPLAY;
        if ($withoutBasePath) {
            return $resultArray[0][0];
        }
        else {
            return $basePath . "/" . $resultArray[0][0];
        }
    }

    /**
     * 
     * @return typeGibt ein Array mit allen Events in der Gallerie mit 
     * zugehörigem Datum in absteigender Reihenfolge zurück, der neuste also 
     * zuerst.
     */
    public function GetAllEvents() {
        $sql = "SELECT EVENT_BEZ, YEAR(EVENT_DATE), EVENT_KEY FROM GAL_EVENT ORDER BY EVENT_DATE DESC";
        $resultArray = $this->database->query($sql);
        return $resultArray;
    }

    /**
     * 
     * @return typeGibt ein Array mit den neuesten 100 Events in der Gallerie mit 
     * zugehörigem Datum in absteigender Reihenfolge zurück, der neuste also 
     * zuerst.
     */
    public function GetTop100Events() {
        $sql = "SELECT GalleryName, CountryID, ID FROM onTour_Gallery ORDER BY ID DESC Limit 0, 100";
        $resultArray = $this->database->query($sql);
        return $resultArray;
    }

    /**
     * 
     * @return typeGibt ein Array mit den Events des mitgegebenen Jahres zurück. 
     * Wenn kein Jahr angegeben wurde, wird das aktuellste Jahr zurückgegeben.
     */
    public function GetEventsByYear($year) {
        if (isset($year)) {
            $sql = "SELECT EVENT_BEZ, EVENT_KEY FROM GAL_EVENT WHERE YEAR(EVENT_DATE) = $year ORDER BY EVENT_DATE DESC";
        }
        else {
            $sql = "SELECT EVENT_BEZ, EVENT_KEY FROM GAL_EVENT WHERE YEAR(EVENT_DATE) = (SELECT MAX(YEAR(EVENT_DATE)) FROM GAL_EVENT) ORDER BY EVENT_DATE DESC";
        }
        $resultArray = $this->database->query($sql);
        return $resultArray;
    }

    /**
     * Gibt die einzelnen Jahre zurück, zu denen Events vorhanden sind.
     * @return type
     */
    public function GetAllYears() {
        $sql = "SELECT YEAR(EVENT_DATE) as EventYear FROM GAL_EVENT GROUP BY EventYear ORDER BY EventYear DESC";
        $resultArray = $this->database->query($sql);
        return $resultArray;
    }

    /**
     * Gibt die Jahreszahl des mitgegebenen Events zurück
     */
    public function GetYearByEventID($id) {
        $sql = "SELECT YEAR(EVENT_DATE) as EventYear FROM GAL_EVENT WHERE EVENT_KEY = " . $id;
        $resultArray = $this->database->query($sql);
        return $resultArray[0][0];
    }

    /**
     * Gibt die ID des neusten Events zur�ck
     * @return type
     */
    public function GetNewestEvent() {
        $sql = "SELECT MAX(EVENT_KEY) FROM GAL_EVENT";
        $resultArray = $this->database->query($sql);
        return $resultArray[0][0];
    }

    /**
     * Gibt den Titel einer Gallerie anhand der Event ID zur�ck
     * @param type $id
     * @return type
     */
    public function GetTitleByID($id) {
        $sql = "SELECT EVENT_BEZ FROM GAL_EVENT WHERE EVENT_KEY = " . $id;
        $resultArray = $this->database->query($sql);
        return $resultArray[0][0];
    }

    /**
     * Gibt alle Kommentare eines Bildes anhand der ID zur�ck
     * @param type $id
     * @return type
     */
    public function GetCommentsByPicID($id) {
        $sql = "SELECT KOM_DATE, KOM_NAM, KOM_KOM
                FROM GAL_KOM
                WHERE KOM_PIC = " . $id;
        $resultArray = $this->database->query($sql);
        return $resultArray;
    }

    /**
     * F�gt einen neuen Kommentar in die GAL_KOM Tabelle ein
     * @param type $picId
     * @param type $name
     * @param type $comment
     */
    public function InsertNewComment($picId, $name, $comment) {
        $sql = "INSERT INTO GAL_KOM (KOM_PIC, KOM_DATE, KOM_NAM, KOM_KOM) 
                VALUES (" . $picId . ", NOW(), '" . $name . "', '" . $comment . "')";
        $this->database->iquery($sql);
    }

    /**
     * Gibt die Bilder für ein Mitglied anhand der ID zurück
     * @param type $id
     * @return type
     */
    public function GetPicsByMitgID($id) {
        $sql = "SELECT PICS_KEY, PICS_ANZ, EVENT_PATH, EVENT_KEY
                FROM MPIC 
                Left Join MITG ON MPIC.MPIC_MITG = MITG.MITG_KEY
                Left Join GAL_PICS ON MPIC.MPIC_PIC = GAL_PICS.PICS_KEY
                Left Join GAL_EVENT ON GAL_PICS.PICS_EVENT = GAL_EVENT.EVENT_KEY
                where MITG_KEY = " . $id . " ORDER BY PICS_KEY DESC"
        ;
        $this->Log($sql, KLogger::DEBUG);
        $resultArray = $this->database->query($sql);
        return $resultArray;
    }

    public function CreateAdminThumbnails($thumbWidth) {
        
        $basePath = $_SESSION['rootfolder'] . FotoProvider::FOTO_FOLDER;

        $pathToImages = $basePath . "/upload/";
        $pathToThumbs = $basePath . "/thumbnails/";

        
        $admin = new AdminFotosFunctions();
        return $admin->CreateAdminThumbnails($pathToImages, $pathToThumbs, $thumbWidth);
    }

    public function DeletePicFromDB($id){
        $sql = "DELETE FROM GAL_PICS where PICS_KEY = " . $id;        
        $this->Log($sql, KLogger::DEBUG);
        $this->database->iquery($sql);        
    }
    
    public function DeleteEventFromDB($id){
        $sql = "DELETE FROM GAL_EVENT where EVENT_KEY = " . $id;        
        $this->Log($sql, KLogger::DEBUG);
        $this->database->iquery($sql);        
    }
    
    /**
     * Löscht den Event und alle dazugehörigenBilder und DB Einträge
     * @param type $id
     */
    public function DeleteEvent($id){
        $pics = $this->GetImagesByGallerieId($id); 
        foreach($pics as $pic){            
            $this->DeleteFile($this->GetImagePathByID($pic['PICS_KEY']));
            $this->DeletePicFromDB($pic['PICS_KEY']);
        }
        $this->DeleteEventFromDB($id);
    }
    
    public function DeleteFile($path) {
        $admin = new AdminFotosFunctions();
        return $admin->DeleteFile($path);
    }

    public function RotateLeft($path) {
        $admin = new AdminFotosFunctions();
        return $admin->RotateLeft($path);
    }

    public function RotateRight($path) {
        $admin = new AdminFotosFunctions();
        return $admin->RotateRight($path);
    }
    
    public function RenameEventTitle($id, $newEventTitle) {
        $admin = new AdminFotosFunctions();
        return $admin->RenameEventTitle($id, $newEventTitle);
    }

}
