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

    public function GetFirstReportID($args) {
        header("Content-Type: application/json");
        $tourpart = array_shift($args);
        $reportProvider = new ReportProvider();
        $data = $reportProvider->GetFirstReportID($tourpart)[0][0];
        return $data;
    }

    public function GetLastReportID($args) {
        header("Content-Type: application/json");
        $tourpart = array_shift($args);
        $reportProvider = new ReportProvider();
        $data = $reportProvider->GetLastReportID($tourpart)[0][0];
        if (count($data) > 0) {

            return $data;
        } else {
            return [];
        }
    }

    public function Show($args) {
        $tourPart = array_shift($args);
        $reportProvider = new ReportProvider();

        if (count($args) > 0) {
            $id = array_shift($args);
            $data = $reportProvider->GetReportByID($id)[0];
        } else {
            $data = $reportProvider->GetLastReport($tourPart)[0];            
        }
        $this->displayReport($data, $tourPart);
    }

    public function ShowFirstReport($args) {
        $tourPart = array_shift($args);
        $reportProvider = new ReportProvider();
        $data = $reportProvider->GetFirstReport($tourPart)[0];
        $this->displayReport($data, $tourPart);
    }

    public function ShowLastReport($args) {
        $tourPart = array_shift($args);
        $reportProvider = new ReportProvider();
        $data = $reportProvider->GetLastReport($tourPart)[0];
        $this->displayReport($data, $tourPart);
    }

    public function ShowReportByID($args) {

        $reportProvider = new ReportProvider();
        $id = array_shift($args);
        $tourPart = array_shift($args);
        $data = $reportProvider->GetReportByID($id)[0];
        $this->displayReport($data, $tourPart);
    }

    private function displayReport($data, $tourPart) {
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Reports');
        $reportProvider = new ReportProvider();


        $smarty->assign("data", $data);


        $preview = $reportProvider->GetPreviewReport($data['ID'], $tourPart)[0];
        $next = $reportProvider->GetNextReport($data['ID'], $tourPart)[0];

        if (count($preview) > 0) {
            $smarty->assign("preview", $preview);
        }
        if (count($next) > 0) {
            $smarty->assign("next", $next);
        }
        $pics = $reportProvider->GetPicsByReportID($data['ID']);
        if (count($pics) > 0) {
            $path = "gallery/large";

            for ($i = 0; $i < count($pics); $i ++) {
                $imgPath = $path . "/" . $pics[$i]['PicPath'];
                $size = getimagesize($_SESSION['rootfolder'] . "/../" . $imgPath);

                $picsArray[$i] = [
                    "path" => $imgPath,
                    "width" => $size[0],
                    "height" => $size[1],
                    "caption" => $pics[$i]['Caption']
                ];
            }

            $smarty->assign('pics', $picsArray);
        }
        $phpdate = strtotime($data['CreationDate']);
        $smarty->assign("time", time());
        $smarty->assign("date", date('d.m.Y H:i:s', $phpdate));
        $smarty->display('Reports.tpl');
    }

}
