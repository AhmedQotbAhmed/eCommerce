<?php

    /*
    Comments Page

    */

ob_start(); // Output Buffering Start
session_start();
include 'init.php';
include_once 'model/CommentModel.php';
$comment = new comment($con);
include $tpl.'footer.php';

class comment
{
    private $con;
    private $pageTitle;
    private $do;
    private $commentmodel;

    public function __construct($con)
    {
        $this->con = $con;
        $this->pageTitle = 'Comments';
        $this->commentmodel = new CommentModel($con);
        $this->run();
    }

    public function run()
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

        $this->ManageCommentsHTML($comments);
    }

    private function ManageCommentsHTML($comments)
    {
        if (!empty($comments)) {
            ?>

<h1 class="text-center">Manage Comments</h1>
<div class="container">
    <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td>ID</td>
                <td>Comment</td>
                <td>Item Name</td>
                <td>User Name</td>
                <td>Added Date</td>
                <td>Control</td>
            </tr>
            <?php
                            foreach ($comments as $comment) {
                                echo '<tr>';
                                echo '<td>'.$comment['c_id'].'</td>';
                                echo '<td>'.$comment['comment'].'</td>';
                                echo '<td>'.$comment['Item_Name'].'</td>';
                                echo '<td>'.$comment['Member'].'</td>';
                                echo '<td>'.$comment['comment_date'].'</td>';
                                echo "<td>
										<a href='comments.php?do=Edit&comid=".$comment['c_id']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='comments.php?do=Delete&comid=".$comment['c_id']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                if ($comment['status'] == 0) {
                                    echo "<a href='comments.php?do=Approve&comid="
                                                     .$comment['c_id']."' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Approve</a>";
                                }
                                echo '</td>';
                                echo '</tr>';
                            } ?>
            <tr>
        </table>
    </div>
</div>

<?php
        } else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Comments To Show</div>';
            echo '</div>';
        } ?>

<?php
    }

    private function editComment()
    {
        // Check If Get Request comid Is Numeric & Get Its Integer Value

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        // Edit Comment using commentmodel class
        $row = $this->commentmodel->editComment($comid);

        $this->ManageEditHTML($row, $comid);
    }

    private function ManageEditHTML($row, $comid)
    {
        if (!empty($row)) { ?>

<h1 class="text-center">Edit Comment</h1>
<div class="container">
    <form class="form-horizontal" action="?do=Update" method="POST">
        <input type="hidden" name="comid" value="<?php echo $comid; ?>" />
        <!-- Start Comment Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Comment</label>
            <div class="col-sm-10 col-md-6">
                <textarea class="form-control" name="comment"><?php echo $row['comment']; ?></textarea>
            </div>
        </div>
        <!-- End Comment Field -->
        <!-- Start Submit Field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Save" class="btn btn-primary btn-sm" />
            </div>
        </div>
        <!-- End Submit Field -->
    </form>
</div>

<?php

            // If There's No Such ID Show Error Message
            } else {
                echo "<div class='container'>";

                $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

                redirectHome($theMsg);

                echo '</div>';
            }
    }

    public function updateComment()
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

    public function deleteComment()
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

    public function approveComment()
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

    ob_end_flush(); // Release The Output

?>