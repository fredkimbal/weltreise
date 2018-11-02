<?php

require_once $_SESSION['rootfolder'] . '/API/SmartyAPI.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/ReportProvider.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/GalleryProvider.php';

/**
 * API für das Administrieren der Foto Gallerie
 * @author Andy Nick
 * @version 1.0.0.2 
 */
class ReportAdminAPI extends SmartyAPI {

    const SMARTY_DIR = 'ReportAdmin';

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

        $smarty->assign('reports', $this->getReports());

        $smarty->assign('galleries', $this->getGalleries());

        $smarty->display("ReportAdmin.tpl");
    }

    protected function GetGalleryPreview($args) {

        $id = array_shift($args);

        header("Content-Type: text/html");
        $smarty = $this->createSmarty(self::SMARTY_DIR);

        $galleryProvider = new GalleryProvider();
        $smarty->assign("images", $galleryProvider->GetImagesByGallerieId($id));

        $settingsProv = new SettingProvider();
        $smarty->assign('path', $settingsProv->GetSetting("FotoFolderDisplay"));

        $smarty->display("GalleryPreview.tpl");
    }

    protected function Image($args) {
        $action = array_shift($args);
        if ($action === "detail") {
            $this->showImageDetail($args);
        }
        elseif($action === "save"){
            $this->saveImageDetails($args);
        }
    }
    
    private function showImageDetail($args){
        $smarty = $this->createSmarty(self::SMARTY_DIR);
            $imageID = array_shift($args);
            $reportID = array_shift($args);
            if($reportID==''){
                $reportID = 0;
            }
            $settingsProv = new SettingProvider();
            $smarty->assign('path', $settingsProv->GetSetting("FotoFolderDisplay"));

            $galleryProvider = new GalleryProvider();
            $details = $galleryProvider->GetImageByID($imageID, $reportID);
            $smarty->assign("image", $details);
            $smarty->assign("reportID", $reportID);
            
            $smarty->display("ImageDetail.tpl");
    }

    private function saveImageDetails($args){
       $galleryProvider = new GalleryProvider();
       $galleryProvider->SaveImageToReport($_POST['imageID'], $_POST['reportID'], $_POST['caption']);
    }
    
    protected function GetReportDetail($args) {
        $id = array_shift($args);
        $reportProvider = new ReportProvider();
        $report = $reportProvider->GetReportByID($id)[0];

        $smarty = $this->createSmarty(self::SMARTY_DIR);

        $smarty->assign("title", $report['ReportTitle']);
        $smarty->assign("ID", $report['ID']);
        $smarty->assign("Text", substr($report['ReportTitle'], 0, 100));
        $smarty->assign("date", date('d.m.Y H:i:s', strtotime($report['CreationDate'])));

        $reportProvider = new ReportProvider();
        $smarty->assign('fotos', $reportProvider->GetPicsByReportID($report['ID']));

        $smarty->display("ReportDetail.tpl");
    }

    private function getReports() {
        $reportProvider = new ReportProvider();
        $lastReports = $reportProvider->GetLastTenReport();

        $lastSmartyReports = [];
        foreach ($lastReports as $report) {
            $currentReport = [];
            $currentReport['id'] = $report['ID'];
            $currentReport['title'] = $report['ReportTitle'];
            array_push($lastSmartyReports, $currentReport);
        }
        return $lastSmartyReports;
    }

    private function getGalleries() {
        $galleryProvider = new GalleryProvider();
        $lastGalleries = $galleryProvider->GetLastTenGalleries();

        $lastSmartyGalleries = [];
        foreach ($lastGalleries as $gallery) {
            $currentGallery = [];
            $currentGallery['id'] = $gallery['ID'];
            $currentGallery['title'] = $gallery['GalleryName'];
            array_push($lastSmartyGalleries, $currentGallery);
        }
        return $lastSmartyGalleries;
    }

}
