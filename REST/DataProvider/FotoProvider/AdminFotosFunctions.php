<?php

/**
 * Diese Klasse stellt Methoden zur Verfügung, welche das Administrieren der 
 * Fotos erlauben
 *
 * @author Andy Nick
 * @version 1.0.0.2
 */
class AdminFotosFunctions {

    const FOTO_FOLDER = '/../gallery';
    const FOTO_FOLDER_DISPLAY = '/gallery';
    const FOTO_FOLDER_DEST = '/large';

    /**
     * Mit dieser Methode werden Vorschaubilder für das hochladen der Bilder erstellt.
     * Die Pfade auf diese Bilder werden dann zurückgegeben
     * @param type $pathToImages
     * @param type $pathToThumbs
     * @param type $thumbWidth
     */
    function CreateAdminThumbnails($pathToImages, $pathToThumbs, $thumbWidth) {

        $this->deleteFilesInFolder($pathToThumbs);

// open the directory
        $dir = opendir($pathToImages);
        $thumbs = array();

// loop through it, looking for any/all JPG files:
        while (false !== ($fname = readdir($dir))) {
// parse path for the extension           
            $info = pathinfo($pathToImages . $fname);

// continue only if this is a JPEG image
            if (strtolower($info['extension']) == 'jpg') {

                $filepath = "$pathToImages/" . $fname;
                $thumbPath = "$pathToThumbs/" . $info['filename'] . ".jpg";
// load image and get image size
                ini_set('gd.jpeg_ignore_warning', 1);
                $img = ImageCreateFromJPEG($filepath);


                if (!$img) {
                    echo "Versuch 2";
                    $img = imagecreatefromstring(file_get_contents($filepath));
                }

                $imgsize = getimagesize($filepath);
                $width = $imgsize[0];
                $height = $imgsize[1];

// calculate thumbnail size
                $new_width = $thumbWidth;
                $new_height = floor($height * ( $thumbWidth / $width ));

// create a new temporary image
                $tmp_img = ImageCreateTruecolor($new_width, $new_height);

                if (!$tmp_img) {
                    echo "Fehler beim Erstellen des thumbnails";
                }

// copy and resize old image into new image               
                if (!ImageCopyResized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height)) {
                    echo "Fehler beim komprimieren von Bild: $fname <br/>";
                }

// save thumbnail into a file

                if (!ImageJpeg($tmp_img, $thumbPath, 90)) {
                    echo "Fehler beim Speichern der Datei";
                }


                $path_parts = pathinfo($thumbPath);

                array_push($thumbs, $path_parts['filename']);
            }
        }
//close the directory
        closedir($dir);
        return $thumbs;
    }

    /**
     * Löscht ein File
     * @param type $path
     */
    function DeleteFile($path) {
        unlink($path);
    }

    function RotateLeft($path) {
        ini_set('gd.jpeg_ignore_warning', 1);
        $img = ImageCreateFromJPEG($path);
        $rotate = imagerotate($img, 90, 0);
        ImageJpeg($rotate, $path);
    }

    function RotateRight($path) {


        ini_set('gd.jpeg_ignore_warning', 1);
        $img = ImageCreateFromJPEG($path);
        $rotate = imagerotate($img, -90, 0);
        ImageJpeg($rotate, $path);
    }

    /**
     * Fügt neue Bilder in die Website ein.
     */
    public function UploadPictures($request) {

        $basePath = $_SESSION['rootfolder'] . self::FOTO_FOLDER;
        
        $destPath = $basePath . "/" . self::FOTO_FOLDER_DEST;

        if (isset($_POST['checkboxExistingGallery']) && $_POST['checkboxExistingGallery'] === 'on') {
            $event_bez = $this->getEventInfo($_POST['eventID'])[0][1];
            $eventID = $_POST['eventID'];
        } else {
            $eventID = $this->createNewEvent($_POST['eventname'], $_POST['eventdate'], $_POST['countryID'], $_POST['tourPart']);
            $event_bez = $_POST['eventname'];
        }


        $picIndex = $this->getLastPicIndex();

        $i = 1;

        $uploadfolder = $basePath . '/upload';
        //$dir = opendir($uploadfolder);

        $files = array();
        
        $dir = new DirectoryIterator($uploadfolder);
        
        foreach ($dir as $fileinfo) {
            $files[$fileinfo->getMTime()][] = $fileinfo->getFilename();
        }

        krsort($files);

        foreach($files as $fname){
        //while (false !== ($fname = readdir($dir))) {
            if ($fname[0] != '.' && $fname[0] != '..') {
                $bild = $uploadfolder . "/" . $fname[0];
                if (file_exists($bild)) {
                    $imgsize = getimagesize($bild);

                    $bild_new = $this->getEventSuffix($event_bez) . "_"
                            . date("Ymd", time()) . "_"
                            . str_pad($picIndex, 10, '0', STR_PAD_LEFT)
                            . ".jpg";

                    ini_set('gd.jpeg_ignore_warning', 1);
                    $im = ImageCreateFromJPEG($bild);
                    $width = $imgsize[0];
                    $height = $imgsize[1];

                    if ($imgsize[0] > $imgsize[1]) {
                        // Querformat		
                        $anztwidth = 575 * $width / $height;
                        $anztheight = 575;
                    } else {
                        // Hochformat
                        $anztheight = 800;
                        $anztwidth = 800 * $width / $height;
                    }

                    $im2 = ImageCreateTruecolor($anztwidth, $anztheight);
                    ImageCopyResized($im2, $im, 0, 0, 0, 0, $anztwidth, $anztheight, $width, $height);
                    ImageJpeg($im2, $destPath . "/" . $bild_new, 90);

                    echo " Bild $bild_new erstellt... <br/>";

                    $this->insertPic($bild_new, $eventID);
                    echo ' Eintrag in DB...';

                    unlink($bild);
                    //unlink($basePath . "/thumbnails/" . $fname);
                    echo ' Original gelöscht... <br />';
                } else {
                    echo "Das Bild ", $fname, " ist nicht auf dem Server vorhanden.";
                }
                $picIndex++;
            }
        }
//        unset($_SESSION['pic']);
    }

    public function RenameEventTitle($id, $newEventTitle) {
        $sql = "UPDATE GAL_EVENT  SET EVENT_BEZ = '$newEventTitle' where EVENT_KEY = $id";
        $db = new Database();
        return $db->query($sql);
    }

    private function getEventInfo($id) {
        $sql = 'select * from onTour_Gallery where ID = ' . $id;
        $db = new Database();
        return $db->query($sql);
    }

    private function getLastPicIndex() {
        $sql = 'SELECT max(`ID`) as max FROM onTour_Pics';
        $db = new Database();
        $resultArray = $db->query($sql);
        return $resultArray[0]['max'];
    }

    /**
     * Loopt einen String durch und gibt nur alphabetische Zeichen ohne Umlaute zurück
     * @param type $string
     */
    private function getEventSuffix($string) {
        $returnValue = "";
        $chars = str_split($string);
        for ($i = 0; $i < count($chars) && strlen($returnValue) < 3; $i++) {
            $char = strtoupper($chars[$i]);
            $charValue = ord($char);
            if ($charValue >= 65 && $charValue <= 90) {
                $returnValue = $returnValue . $char;
            }
        }
        return $returnValue;
    }

    /**
     * Fügt einen neuen Datensatz in die Event Table ein und gibt die ID zurück
     * @param type $name
     * @param type $date
     * @return type
     */
    private function createNewEvent($name, $date, $country, $tourpart) {
// Neuen Eintrag in Eventtabelle machen
        $sql = "INSERT INTO onTour_Gallery ( `GalleryName`, `GalleryDate`, `TourPart`, `CountryID`) 
		VALUES ('$name','$date','$tourpart', $country)";
        $db = new Database();
        $db->iquery($sql);

        return $db->GetLastID();
    }

    private function insertPic($picName, $picEvent) {
        $db = new Database();
        $sql = "INSERT INTO `onTour_Pics` ( `GalleryID`, `PicPath`) 
                VALUES ($picEvent,'$picName')";
        $db->iquery($sql);
    }

    private function deleteFilesInFolder($folder) {
//Ordnername festlegen in dem die zu löschenden Files liegen
//überprüfen ob das Verzeichnis überhaupt existiert

        if (is_dir($folder)) {
//Schleife, bis alle Files im Verzeichnis ausgelesen wurden
            $files = scandir($folder);

            foreach ($files as $file) {
//Oft werden auch die Standardordner . und .. ausgelesen, diese sollen ignoriert werden
                if ($file != "." && $file != "..") {
//Files vom Server entfernen
                    unlink("$folder/$file");
                }
            }
        }
    }

}
