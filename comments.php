<?php

    /*
    Comments Page

    */

ob_start(); // Output Buffering Start
session_start();
include 'init.php';
include_once 'model/CommentModel.php';
include_once 'view/CommentView.php';

class comment
{
    private $con;
    private $pageTitle;
    private $do;
    private $commentmodel;
    private $view;

    public function __construct($con)
    {
        $this->con = $con;
        $this->pageTitle = 'Comments';
        $this->commentmodel = new CommentModel($con);
        $this->view = new CommentView();

        $this->run();
    }

    private function run()
    {
        if (isset($_SESSION['Username'])) {
            $this->do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

            switch ($this->do) {
                case 'Manage':
                    $this->manageComment();
                    break;

                case 'Edit':
                    $this->editComment();
                    break;
                case 'Update':

                    $this->updateComment();
                    break;
                case 'Delete':

                $this->deleteComment();
                break;
                case 'Approve':

                $this->approveComment();
                break;
                default:
                    $this->manageComment();
            }
        } else {
            header('Location: login.php');
            exit();
        }
    }

    private function manageComment()
    {
        // Select All Comments using commentmodel class
        $comments = $this->commentmodel->getAllComments();

        $this->view->ManageAllCommentsHTML($comments);
    }

    private function editComment()
    {
        // Check If Get Request comid Is Numeric & Get Its Integer Value

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        // Edit Comment using commentmodel class
        $row = $this->commentmodel->editComment($comid);

        $this->view->ManageEditHTML($row, $comid);
    }

    private function updateComment()
    {
        echo "<h1 class='text-center'>Update Comment</h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Variables From The Form

            $comid = $_POST['comid'];
            $comment = $_POST['comment'];

            // Update The Database With This Info
            $stmt = $this->commentmodel->updateComment($comid, $comment);

            // Echo Success Message

            $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

            redirectHome($theMsg);
        }

        echo '</div>';
    }

    private function deleteComment()
    {
        echo "<h1 class='text-center'>Delete Comment</h1>";

        echo "<div class='container'>";

        // Check If Get Request comid Is Numeric & Get The Integer Value Of It

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        // Select All Data Depend On This ID

        $check = checkItem('c_id', 'comments', $comid);

        // If There's Such ID Show The Form

        if ($check > 0) {
            $stmt = $this->commentmodel->deleteComment($comid);

            $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Deleted</div>';

            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

            redirectHome($theMsg);
        }

        echo '</div>';
    }

    private function approveComment()
    {
        echo "<h1 class='text-center'>Approve Comment</h1>";
        echo "<div class='container'>";

        // Check If Get Request comid Is Numeric & Get The Integer Value Of It

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        // Select All Data Depend On This ID

        $check = checkItem('c_id', 'comments', $comid);

        // If There's Such ID Show The Form

        if ($check > 0) {
            $stmt = $this->commentmodel->approveComment($comid);

            $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Approved</div>';

            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

            redirectHome($theMsg);
        }

        echo '</div>';
    }
}
$comment = new comment($con);
include $tpl.'footer.php';
    ob_end_flush(); // Release The Output
