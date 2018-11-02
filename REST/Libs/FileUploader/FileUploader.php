<?php

require_once $_SESSION['rootfolder'] . '/Libs/Core/SimpleStatus.php';

/**
 * Description of FileUploader
 *
 * @author geischterfaescht
 */
class FileUploader {

    private $targetDir;
    
    public $destinationFile;

    public function __construct($targetDir) {
        $this->targetDir = $targetDir;
        if (!$this->endsWith($targetDir, '/')) {
            $this->targetDir = $this->targetDir . '/';
        }
    }

    /**
     * Lädt ein File hoch und schreibt es in die Datenbank
     * @return text/json Status
     * 0 = OK
     * 1 = Kopieren der Datei fehlgeschlagen
     * 2 = Fehler beim updaten der Datenbank
     */
    public function Upload() {
        $message[0] = "Upload erfolgreich";
        $message[1] = "Fehler beim hochladen der Datei";
        $message[2] = "Fehler beim Aktualisieren der Datenbank";

        header("Content-Type: text/html");
        $status = new SimpleStatus();
        $filepath = $this->targetDir . $_FILES['uploadedfile']['name'];
        if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $filepath)) {
            try {                
                $status->Status = 0;
                $this->destinationFile = $filepath;
            } catch (Exception $ex) {
                $status->Status = 2;
            }
        } else {
            $status->Status = 1;
        }
        echo "<p>" . $message[$status->Status] . "</p>";
        return $filepath;
    }

    private function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }

}
