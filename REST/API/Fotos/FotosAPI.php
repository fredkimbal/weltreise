<?php

require_once $_SESSION['rootfolder'] . '/API/API.php';


/**
 * Description of FotosAPI
 *
 * @author Andy
 */
class FotosAPI extends API {

    public function __construct($request, $origin) {
        parent::__construct($request);
        //Security Handling        
    }

    public function Show($args) {
        header("Content-Type: text/html");
        $smarty = new Smarty();

        $smarty->template_dir = $_SESSION['rootfolder'] . '/API/Fotos/templates/';
        $smarty->compile_dir = $_SESSION['rootfolder'] . '/API/Fotos/templates_c/';
        $smarty->config_dir = $_SESSION['rootfolder'] . '/API/Fotos/configs/';
        $smarty->cache_dir = $_SESSION['rootfolder'] . '/API/Fotos/cache/';

        $galleryProvider = new GallerieProvider();
        $years = $galleryProvider->GetAllYears();
        $smarty->assign('years', $years);



        if ($args[0] != '') {
            $events = $galleryProvider->GetEventsByYear($args[0]);
            $smarty->assign('currentYear', $args[0]);
        }
        else {
            $events = $galleryProvider->GetEventsByYear();
            $smarty->assign('currentYear', max($years[0]));
        }
        $smarty->assign('events', $events);

        $thumbnails = [];

        foreach ($events as $event) {
            $pics = [];
            $picsFromDB = $galleryProvider->GetImagesByGallerieId($event[1], 4);
            $path = $galleryProvider->GetGalleriePath($event[1]);
            for ($i = 0; $i < 4; $i++) {
                if (isset($picsFromDB[$i])) {
                    $pics[$i] = $path . "/ANZ/" . $picsFromDB[$i]['PICS_ANZ'];
                }
            }
            $thumbnails[$event[1]] = $pics;
        }
        $smarty->assign('thumbnails', $thumbnails);
        $smarty->display('Fotos.tpl');
    }

    public function GetYearPreview($args) {
        header("Content-Type: text/html");
        $smarty = new Smarty();

        $smarty->template_dir = $_SESSION['rootfolder'] . '/API/Fotos/templates/';
        $smarty->compile_dir = $_SESSION['rootfolder'] . '/API/Fotos/templates_c/';
        $smarty->config_dir = $_SESSION['rootfolder'] . '/API/Fotos/configs/';
        $smarty->cache_dir = $_SESSION['rootfolder'] . '/API/Fotos/cache/';

        $galleryProvider = new GallerieProvider();
        
        if ($args[0] != '') {
            $events = $galleryProvider->GetEventsByYear($args[0]);
            $smarty->assign('currentYear', $args[0]);
        }
        else {
            $events = $galleryProvider->GetEventsByYear();
            $smarty->assign('currentYear', max($years[0]));
        }
        $smarty->assign('events', $events);

        $thumbnails = [];

        foreach ($events as $event) {
            $pics = [];
            $picsFromDB = $galleryProvider->GetImagesByGallerieId($event[1], 4);
            $path = $galleryProvider->GetGalleriePath($event[1]);
            for ($i = 0; $i < 4; $i++) {
                if (isset($picsFromDB[$i])) {
                    $pics[$i] = $path . "/ANZ/" . $picsFromDB[$i]['PICS_ANZ'];
                }
            }
            $thumbnails[$event[1]] = $pics;
        }
        $smarty->assign('thumbnails', $thumbnails);
        $smarty->display('YearPreview.tpl');
    }

    public function GetEventPreview($args) {
        header("Content-Type: text/html");
        $smarty = new Smarty();

        $smarty->template_dir = $_SESSION['rootfolder'] . '/API/Fotos/templates/';
        $smarty->compile_dir = $_SESSION['rootfolder'] . '/API/Fotos/templates_c/';
        $smarty->config_dir = $_SESSION['rootfolder'] . '/API/Fotos/configs/';
        $smarty->cache_dir = $_SESSION['rootfolder'] . '/API/Fotos/cache/';

        $galleryProvider = new GallerieProvider();
        $path = $galleryProvider->GetGalleriePath($args[0]);
        $pics = $galleryProvider->GetImagesByGallerieId($args[0]);
        for ($i = 0; $i < count($pics); $i ++) {
            $imgPath = $path . "/ANZ/" . $pics[$i]['PICS_ANZ'];

            $size = getimagesize($_SESSION['rootfolder'] . "/../" . $imgPath);

            $picsArray[$i] = [
                "path" => $imgPath,
                "width" => $size[0],
                "height" => $size[1]
            ];
        }

        $smarty->assign('pics', $picsArray);
        $smarty->assign('year', $galleryProvider->GetYearByEventID($args[0]));
        $smarty->assign('eventTitle', $galleryProvider->GetTitleByID($args[0]));

        $smarty->display('EventPreview.tpl');
    }

    public function GetPhotoSwipeForm($args) {
        header("Content-Type: text/html");
        $smarty = new Smarty();

        $smarty->template_dir = $_SESSION['rootfolder'] . '/API/Fotos/templates/';
        $smarty->compile_dir = $_SESSION['rootfolder'] . '/API/Fotos/templates_c/';
        $smarty->config_dir = $_SESSION['rootfolder'] . '/API/Fotos/configs/';
        $smarty->cache_dir = $_SESSION['rootfolder'] . '/API/Fotos/cache/';

        $smarty->display('PhotoSwipe.tpl');
    }

}
