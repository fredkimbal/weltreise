<?php

require_once $_SESSION['rootfolder'] . '/API/SmartyAPI.php';
require_once $_SESSION['rootfolder'] . '/Libs/Core/SimpleStatus.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/FotoProvider/FotoProvider.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/GalleryProvider.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/SettingProvider.php';

/**
 * API für das Administrieren der Foto Gallerie
 * @author Andy Nick
 * @version 1.0.0.2 
 */
class FotoAdminAPI extends SmartyAPI {

    const SMARTY_DIR = 'FotoAdmin';
    const FOTO_FOLDER = '/../gallery';
    const FOTO_FOLDER_DISPLAY = '/gallery';

    /**
     * Konstruktor
     * @param type $request
     * @param type $origin
     */
    public function __construct($request, $origin) {

        parent::__construct($request);

        //Security Handling        
    }

    /**
     * Zeigt die Startseite an
     */
    protected function Show() {
        header("Content-Type: text/html");



        $smarty = $this->createSmarty(self::SMARTY_DIR);
        $smarty->assign("events", $this->getEvents());
        $smarty->assign("countries", $this->getCountries());
        $thumbs = [];

        foreach ($this->getThumbnails() as $t) {
            array_push($thumbs, array($t, "$t.jpg"));
        }

        $settingsProv = new SettingProvider();

        $fotofolder = $settingsProv->GetSetting("FotoFolderDisplay");        
        $smarty->assign("pictures", $thumbs);
        
        $smarty->assign("galleryPath", "$fotofolder/thumbnails");
        $smarty->display("FotoAdmin.tpl");
    }

    protected function RotateLeftUploadFoto($args) {
        header("Content-Type: text/html");
        $file = array_shift($args);
        $settingsProv = new SettingProvider();
        $basePath = $settingsProv->GetSetting("FotoFolder");
        $thumbFile = "$basePath/thumbnails/$file";
        $originalFile = "$basePath/upload/$file";

        $galProvider = new FotoProvider();
        $galProvider->RotateLeft($thumbFile);
        $galProvider->RotateLeft($originalFile);
    }

    protected function RotateRightUploadFoto($args) {
        header("Content-Type: text/html");

        $settingsProv = new SettingProvider();
        $file = array_shift($args);

        $basePath = $settingsProv->GetSetting("FotoFolder");
        $thumbFile = "$basePath/thumbnails/$file";
        echo $thumbFile;
        $originalFile = "$basePath/upload/$file";

        $galProvider = new FotoProvider();
        $galProvider->RotateRight($thumbFile);
        $galProvider->RotateRight($originalFile);
    }

    protected function GetContent($args) {
        header("Content-Type: text/html");
        $key = array_shift($args);
        $smarty = $this->createSmarty(self::SMARTY_DIR);
        if ($key === "upload") {
            $smarty->assign("events", $this->getEvents());
            $thumbs = [];
            foreach ($this->getThumbnails() as $t) {
                array_push($thumbs, array($t, "$t.jpg"));
            }
            $smarty->assign("countries", $this->getCountries());
            $smarty->assign("pictures", $thumbs);
            $fotofolder = FotoAdminAPI::FOTO_FOLDER_DISPLAY;
            $smarty->assign("galleryPath", "$fotofolder/thumbnails");

            $smarty->display("uploadFotos.tpl");
        } else if ($key === "assign") {
            $mitgProv = new MitgliederProvider();
            $smarty->assign("mitglieder", $mitgProv->GetAllActive());
            $nextPic = $this->getNextUnaloccatedPic();
            $smarty->assign("pic", $nextPic["path"]);
            $smarty->assign("id", $nextPic["id"]);
            $smarty->display("assignFotos.tpl");
        } else if ($key === "admin") {
            $galleryProvider = new FotoProvider();
            $years = $galleryProvider->GetAllYears();
            $smarty->assign('years', $years);

            $events = $galleryProvider->GetEventsByYear($years[0][0]);
            $smarty->assign('events', $events);


            $smarty->display("adminGalleries.tpl");
        }
    }

    protected function DeleteUploadFoto($args) {
        header("Content-Type: text/html");
        $file = array_shift($args);
        $settingsProv = new SettingProvider();

        $basePath = $settingsProv->GetSetting("FotoFolder");

        $thumbFile = "$basePath/thumbnails/$file";
        $originalFile = "$basePath/upload/$file";

        $galProvider = new FotoProvider();
        $galProvider->DeleteFile($thumbFile);
        $galProvider->DeleteFile($originalFile);
    }

    private function getEvents() {
        $galProvider = new FotoProvider();
        return $galProvider->GetTop100Events();
    }

    private function getCountries() {
        $galProvider = new GalleryProvider();
        return $galProvider->GetAllCountries(2);
    }

    private function getThumbnails() {
        $galProvider = new FotoProvider();
        return $galProvider->CreateAdminThumbnails(150);
    }

    public function AddFotos() {
        header("Content-Type: text/html");
        $fotoProvider = new FotoProvider();
        $fotoProvider->UploadPictures($this->request);
    }

    public function AssignFoto() {
        header("Content-Type: text/html");

        $fotoProvider = new FotoProvider();
        $picId = $this->request['picID'];

        foreach ($this->request['mitg'] as $memberId) {
            $fotoProvider->MemberToPic($memberId, $picId);
        }
        $fotoProvider->MarkPic($picId);
    }

    private function getNextUnaloccatedPic() {
        $fotoProv = new FotoProvider();
        $response = array();
        $response["id"] = $fotoProv->GetNextUnallocatePicID();
        $response["path"] = $fotoProv->GetImagePathByID($response["id"]);
        return $response;
    }

    public function GetNextUnaloccatedPicture() {
        return json_encode($this->getNextUnaloccatedPic());
    }

    public function GetEventComboItemsByYear($args) {
        header("Content-Type: text/html");
        $year = array_shift($args);

        $fotoProv = new FotoProvider();
        $events = $fotoProv->GetEventsByYear($year);

        $response = "";

        foreach ($events as $event) {
            $key = $event['EVENT_KEY'];
            $bez = $event['EVENT_BEZ'];
            $response = $response . "<a class='eventComboChilds w3-bar-item w3-button w3-hover-light-grey' data-event-ID='$key' href='#'>"
                    . $event['EVENT_BEZ']
                    . "</a>";
        }
        return $response;
    }

    protected function GetEventAdminView($args) {
        header("Content-Type: text/html");
        $eventID = array_shift($args);

        $smarty = $this->createSmarty("Intern/Admin/FotoAdmin");

        $fotoProvider = new FotoProvider();

        $smarty->assign("title", $fotoProvider->GetTitleByID($eventID));
        $smarty->assign("eventID", $eventID);

        $smarty->display("eventAdminView.tpl");
    }

    protected function UpdateEventTitle($args) {
        $eventID = array_shift($args);
        $title = array_shift($args);
        $mysql = new mysqli();

        //$title = $mysql->escape_string($title);

        $fotoProvider = new FotoProvider();
        $fotoProvider->RenameEventTitle($eventID, $title);

        $updatedTitle = $fotoProvider->GetTitleByID($eventID);

        return json_encode(array("newtitle" => $updatedTitle, "id" => $eventID));
    }

    protected function Event($args) {

        $id = array_shift($args);
        $fotoProvider = new FotoProvider();
        if ($this->method === 'GET') {

            $function = array_shift($args);

            if ($function === 'showpictures') {
                header("Content-Type: text/html");
                $smarty = $this->createSmarty(self::SMARTY_DIR);


                $galleriePath = $fotoProvider->GetGalleriePath($id, true);
                $fotofolder = FOTO_FOLDER_DISPLAY;
                $smarty->assign("galleryPath", "$fotofolder/$galleriePath/ANZ/");

                $pictures = [];

                foreach ($fotoProvider->GetImagesByGallerieId($id) as $pic) {
                    array_push($pictures, array($pic['PICS_KEY'], $pic['PICS_ANZ']));
                }
                $smarty->assign("pictures", $pictures);
                $smarty->display("picturePreview.tpl");
            }
        }

        if ($this->method === 'DELETE') {
            $fotoProvider->DeleteEvent($id);
            return json_encode(true);
        }
    }

    protected function Pic($args) {
        if ($this->method === 'UPDATE') {
            $id = array_shift($args);
            $function = array_shift($args);

            if ($function === 'rotateleft') {
                $fotoProvider = new FotoProvider();
                $fotoProvider->RotateLeft($fotoProvider->GetImagePathByID($id, false));
                return json_encode(true);
            }

            if ($function === 'rotateright') {
                $fotoProvider = new FotoProvider();
                $fotoProvider->RotateRight($fotoProvider->GetImagePathByID($id, false));
                return json_encode(true);
            }
        }

        if ($this->method === 'DELETE') {
            $id = array_shift($args);
            $fotoProvider = new FotoProvider();
            $fotoProvider->DeleteFile($fotoProvider->GetImagePathByID($id, false));
            $fotoProvider->DeletePicFromDB($id);
            return json_encode(true);
        }
    }

}
