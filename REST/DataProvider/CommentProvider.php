<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommentProvider
 *
 * @author geischterfaescht
 */
class CommentProvider extends DataProvider {

    public function GetCommentsByReport($id) {
        $qry = "SELECT c.ID as CommentID, rc.*, c.* FROM onTour_ReportComments as rc LEFT JOIN onTour_Comments as c ON c.ID = rc.CommentID WHERE c.ParentComment is null AND rc.ReportID = " . $id;

        $db = new Database();
        return $db->query($qry);
    }
    
    public function GetSubcommentByCommentID($id) {
        $qry = "SELECT c.ID as CommentID, c.* FROM onTour_Comments as c WHERE c.ParentComment = " . $id;
        $db = new Database();
        return $db->query($qry);
    }

    public function GetCommentCountByReport($id) {
        $qry = "SELECT Count(*) FROM onTour_ReportComments as rc LEFT JOIN onTour_Comments as c ON c.ID = rc.CommentID WHERE rc.ReportID = " . $id;

        $db = new Database();
        return $db->query($qry)[0][0];
    }

    public function AddNewComment($name, $message, $mail, $reportID, $commentID) {
        $qry = "INSERT INTO onTour_Comments (Name, Message, Mail, CommentDate, ParentComment) VALUES ('$name', '$message', '$mail', NOW(), $commentID)";
        
        $db = new Database();
        $db->iquery($qry);

        $commentID = $db->GetLastID();

        $qry = "INSERT INTO onTour_ReportComments (COmmentID, ReportID) VALUES ($commentID, $reportID)";
        $db->iquery($qry);
    }
    
    public function GetAllMailFromComment($commentID) {
        $qry = "SELECT DISTINCT Mail FROM onTour_Comments as c WHERE c.ID = $commentID OR ParentComment = $commentID";
        $db = new Database();
        return $db->query($qry);        
    }

}
