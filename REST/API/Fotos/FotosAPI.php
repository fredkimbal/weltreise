<?php

require_once $_SESSION['rootfolder'] . '/API/SmartyAPI.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/GalleryProvider.php';

/**
 * Description of FotosAPI
 *
 * @author Andy
 */
class FotosAPI extends SmartyAPI {

    public function __construct($request, $origin) {
        parent::__construct($request);
        //Security Handling        
    }

    public function ShowVelo($args) {
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Fotos');

        $galleryProvider = new GalleryProvider();
        $countries = $galleryProvider->GetCountries(1);

        $smarty->assign("countries", $countries);

        if (count($args) > 0) {
            $currentCountryID = array_shift($args);
            $currentCountry = $galleryProvider->GetCountryByID($currentCountryID)[0][1];
        } else {
            $currentCountry = $galleryProvider->GetNewestCountry(1)[0][1];
            $currentCountryID = $galleryProvider->GetNewestCountry(1)[0][0];
        }
        $smarty->assign("currentCountry", $currentCountry);

        $galleries = $galleryProvider->GetgalleryByCountry($currentCountryID, 1);

        $smarty->assign("galleries", $galleries);

        $smarty->display('Fotos.tpl');
    }

    public function ShowBenelux($args) {
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Fotos');

        $galleryProvider = new GalleryProvider();
        $countries = $galleryProvider->GetCountries(2);

        $smarty->assign("countries", $countries);

        if (count($args) > 0) {
            $currentCountryID = array_shift($args);
            $currentCountry = $galleryProvider->GetCountryByID($currentCountryID)[0][1];
        } else {
            $currentCountry = $galleryProvider->GetNewestCountry(2)[0][1];
            $currentCountryID = $galleryProvider->GetNewestCountry(2)[0][0];
        }
        $smarty->assign("currentCountry", $currentCountry);

        $galleries = $galleryProvider->GetgalleryByCountry($currentCountryID, 2);

        $smarty->assign("galleries", $galleries);

        $smarty->display('Fotos.tpl');
    }
    
    public function Show($args) {
        header("Content-Type: text/html");
        
        $tourpart = array_shift($args);
        
        $smarty = $this->createSmarty('Fotos');

        $galleryProvider = new GalleryProvider();
        $countries = $galleryProvider->GetCountries(2);

        $smarty->assign("countries", $countries);

        if (count($args) > 0) {
            $currentCountryID = array_shift($args);
            $currentCountry = $galleryProvider->GetCountryByID($currentCountryID)[0][1];
        } else {
            $currentCountry = $galleryProvider->GetNewestCountry($tourpart)[0][1];
            $currentCountryID = $galleryProvider->GetNewestCountry($tourpart)[0][0];
        }
        $smarty->assign("currentCountry", $currentCountry);
        echo $currentCountry;
        $galleries = $galleryProvider->GetgalleryByCountry($currentCountryID, $tourpart);

        $smarty->assign("galleries", $galleries);

        $smarty->display('Fotos.tpl');
    }

    public function Country($args) {

        $smarty = $this->createSmarty('Fotos');

        $galleryProvider = new GalleryProvider();
        $action = array_shift($args);
        $countryID = array_shift($args);

        if ($action == 'preview') {
            header("Content-Type: text/html");
            $tourpart = array_shift($args);
            $galleries = $galleryProvider->GetgalleryByCountry($countryID, $tourpart);            
            $smarty->assign("galleries", $galleries);
            $smarty->display('CountryPreview.tpl');
        }
        if ($action == 'name') {
            header("Content-Type: application/json");
            $result = $galleryProvider->GetCountryByID($countryID);
            return json_encode(count($result) == 1 ? $result[0][1] : "");
        }
    }

    public function Pics($args) {

        $action = array_shift($args);
        if ($action == "bygallery") {
            $id = array_shift($args);


            header("Content-Type: text/html");
            $smarty = $this->createSmarty("Fotos");
            $galleryProvider = new GalleryProvider();
            $path = "gallery/large";
            $pics = $galleryProvider->GetImagesByGallerieId($id);
            for ($i = 0; $i < count($pics); $i ++) {
                $imgPath = $path . "/" . $pics[$i]['PicPath'];
                $size = getimagesize($_SESSION['rootfolder'] . "/../" . $imgPath);

                $picsArray[$i] = [
                    "path" => $imgPath,
                    "width" => $size[0],
                    "height" => $size[1]
                ];
            }

            $smarty->assign('pics', $picsArray);
            $smarty->assign('country', $galleryProvider->GetCountryByGallerieID($id)[0][0]);
            $smarty->assign('eventTitle', $galleryProvider->GetGalleryByID($id)[0][1]);

            $smarty->display('EventPreview.tpl');
        }
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
