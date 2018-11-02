<?php

require_once $_SESSION['rootfolder'] . '/API/SmartyAPI.php';
require_once $_SESSION['rootfolder'] . '/DataProvider/CommentProvider.php';
require_once $_SESSION['rootfolder'] . '/Libs/securimage/securimage.php';

/**
 * Description of FotosAPI
 *
 * @author Andy
 */
class CommentsAPI extends SmartyAPI {

    public function __construct($request, $origin) {
        parent::__construct($request);
        //Security Handling        
    }

    public function ByReport($args) {

        $smarty = $this->createSmarty('Comments');

        $id = array_shift($args);
        $provider = new CommentProvider();
        if ($this->method == "GET") {
            if (count($args) > 0) {
                $reportMethod = array_shift($args);
                if ($reportMethod === "count") {
                    header("Content-Type: application/json");
                    return $provider->GetCommentCountByReport($id);
                }
            } else {
                header("Content-Type: text/html");
                $comments = $provider->GetCommentsByReport($id);
                $preparedComents = [];
                foreach ($comments as $comment) {
                    $prepComment = $this->prepareComment($comment);

                    $subcomments = $this->getSubcomments($comment['CommentID']);
                    if (count($subcomments) > 0) {

                        $prepComment['hasAnswers'] = true;
                        $prepComment['subcomment'] = $subcomments;
                    } else {

                        $prepComment['hasAnswers'] = false;
                    }

                    array_push($preparedComents, $prepComment);
                }

                $smarty->assign('comments', $preparedComents);
                $smarty->display('CommentOverview.tpl');
            }
        } else if ($this->method === 'POST') {
            header("Content-Type: text/html");
            try {
                $securimage = new Securimage();
                if (!$securimage->check($_POST['captcha_code'])) {
                    // the code was incorrect
                    // you should handle the error so that the form processor doesn't continue
                    // or you can use the following code if there is no validation or you do not know how
                    echo "Fehler - Der eingegebene Code war nicht korrekt. Probieren sie es doch nochmals";
                } else {
                    if ($_POST['commentID'] != '') {
                        $commentID = $_POST['commentID'];
                    } else {
                        $commentID = "null";
                    }
                    $provider->AddNewComment($_POST['name'], $_POST['message'], $_POST['mail'], $_POST['reportID'], $commentID);
                    echo "Kommentar hinzugefügt.";
                    $this->sendNotification();

                    if ($_POST['commentID'] > 0) {
                        $ToAdresses = $provider->GetAllMailFromComment($_POST['commentID']);


                        $betreff = "Ein Kommentar von dir wurde kommentiert";
                        $nachricht = 'Hallo<br/>Ein Kommentar von dir wurde auf <a href="http://www.luanaundandy.ch">www.luanaundandy.ch</a> wurde kommentiert.';

                        foreach ($ToAdresses as $adress) {
                            if ($adress[0] != $_POST['mail']) {
                                mail($adress[0], $betreff, $nachricht, "From: Luana und Andy <noreply@luanaundandy.ch>\nContent-Type: text/html\n");
                            }
                        }
                    }
                }
            } catch (Exception $ex) {
                echo "Fehler beim hinzufügen des Kommentars.";
                echo $ex->getMessage();
            }
        }
    }

    private function sendNotification() {
        $betreff = "Neuer Kommentar";
        $nachricht = "Es wurde ein neuer Kommentar hinzugef&uml;gt.";
        mail("andy@andynick.ch", $betreff, $nachricht, "From: Luana und Andy <noreply@luanaundandy.ch>\nContent-Type: text/html\n");
        mail("luana_odermatt@gmx.ch", $betreff, $nachricht, "From: Luana und Andy <noreply@luanaundandy.ch>\nContent-Type: text/html\n");
    }

    private function prepareComment($dbComment) {
        $prepComment = [];
        $phpdate = strtotime($dbComment['CommentDate']);
        $prepComment['date'] = date('d.m.Y H:i:s', $phpdate);
        $prepComment['user'] = $dbComment['Name'];
        $prepComment['CommentText'] = nl2br($dbComment['Message']);
        $prepComment['CommentID'] = $dbComment['CommentID'];
        return $prepComment;
    }

    private function getSubcomments($id) {
        $commentProvider = new CommentProvider();
        $dbComments = $commentProvider->GetSubcommentByCommentID($id);
        $comments = [];
        foreach ($dbComments as $dbComment) {
            array_push($comments, $this->prepareComment($dbComment));
        }
        return $comments;
    }

    public function ShowLastReport($args) {
        $tourPart = array_shift($args);
        $reportProvider = new ReportProvider();
        $data = $reportProvider->GetLastReport($tourPart)[0];
        $this->displayReport($data);
    }

    public function ShowReportByID($args) {

        $reportProvider = new ReportProvider();
        $id = array_shift($args);
        $tourPart = array_shift($args);
        $data = $reportProvider->GetReportByID($id)[0];
        $this->displayReport($data);
    }

    private function displayReport($data) {
        header("Content-Type: text/html");
        $smarty = $this->createSmarty('Reports');
        $reportProvider = new ReportProvider();


        $smarty->assign("data", $data);


        $preview = $reportProvider->GetPreviewReport($data['ID'], 0)[0];
        $next = $reportProvider->GetNextReport($data['ID'], 0)[0];

        if (count($preview) > 0) {
            $smarty->assign("preview", $preview);
        }
        if (count($next) > 0) {
            $smarty->assign("next", $next);
        }
        echo "Date:" . $data['CommentDate'];
        $phpdate = strtotime($data['CommentDate']);
        $smarty->assign("date", date('d.m.Y H:i:s', $phpdate));
        //$smarty->display('Reports.tpl');
    }

}
