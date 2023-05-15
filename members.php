<?php

// Manage Members Page

ob_start(); // Output Buffering Start
session_start();
include 'init.php';
include_once 'model/MemberModel.php';
include_once 'view/MemberView.php';

class MembersPage
{
    private $con;
    private $pageTitle;
    private $do;
    private $model;
    private $view;

    public function __construct($con)
    {
        $this->con = $con;
        $this->pageTitle = 'Members';
        $this->model = new MembersModel($con);
        $this->view = new MemberView();
        $this->run();
    }

    private function run()
    {
        if (isset($_SESSION['Username'])) {
            $this->do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

            switch ($this->do) {
                case 'Manage':
                    $this->manageMembers();
                    break;
                case 'Add':
                    $this->view->addMember();
                    break;
                case 'Insert':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $this->insertMember();
                    }
                    break;
                case 'Edit':
                    $this->editMember();
                    break;
                case 'Update':
                    $this->updateMember();
                    break;
                case 'Delete':
                    $userid = $this->getUserID();
                   // Select All Data Depend On This ID
                    $check = checkItem('userid', 'users', $userid);
                    $check > 0 ? $this->deleteMember($userid) : $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>'; redirectHome($theMsg);
                    break;
                case 'Activate':
                    $userid = $this->getUserID();
                    $check = checkItem('userid', 'users', $userid);

                    $check > 0 ? $this->activateMember($userid) : $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>'; redirectHome($theMsg);

                    break;
                default:
                    $this->manageMembers();
            }
        } else {
            header('Location: login.php');
            exit();
        }
    }

    private function manageMembers()
    {
        // call model class

        $row = $this->model->getAllMembers();
        $this->view->ManageMembersHTML($row);
    }

    public function getAvatarName()
    {
        $avatarName = $_FILES['avatar']['name'];
        $avatarSize = $_FILES['avatar']['size'];
        $avatarTmp = $_FILES['avatar']['tmp_name'];
        $avatarType = $_FILES['avatar']['type'];

        $avatarAllowedExtension = ['jpeg', 'jpg', 'png', 'gif'];

        // Get Avatar Extension
        $avatarParts = explode('.', $avatarName);
        $avatar = strtolower(end($avatarParts));

        return $avatar;
    }

    // Insert Member Function
    public function insertMember()
    {
        echo "<h1 class='text-center'>Insert Member</h1>";
        echo "<div class='container'>";

        // Get Variables From The Form
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $fullName = $_POST['full'];
        $avatar = $this->getAvatarName();
        // call model class
        $stmt = $this->model->insertMember($username, $password, $email, $fullName, $avatar);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Inserted</div>';
            redirectHome($theMsg, 'back');

            return true; // Return true to indicate success
        } else {
            echo '<div class="alert alert-danger">Failed to insert the member.</div>';

            return false; // Return false to indicate an error occurred
        }
    }

    public function getUserID()
    {
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        return $userid;
    }

    // Edit Member Function
    public function editMember()
    {
        $userid = $this->getUserID();
        // call model class
        $row = $this->model->editMember($userid);

        $this->view->editMemberHTML($row);
    }

    // Update Member Function
    public function updateMember()
    {
        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";

        $userID = $_POST['userid'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $fullName = $_POST['full'];
        $password = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

        // Update Member using model class
        $stmt = $this->model->updateMember($userID, $username, $email, $fullName, $password);

        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

        redirectHome($theMsg, 'back');

        return true;
    }

    // Delete Member Function
    public function deleteMember($userID)
    {
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

        // Delete Member using model class
        $stmt = $this->model->deleteMember($userID);
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

        // call model class

        redirectHome($theMsg, 'back');

        return $stmt->rowCount();
    }

    // Activate Member Function
    public function activateMember($userID)
    {
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";

        // Activate Member using model class
        $stmt = $this->model->activateMember($userID);
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount().' Record Updated</div>';

        redirectHome($theMsg, 'back');

        return $stmt->rowCount();
    }
}
$member = new MembersPage($con);
include $tpl.'footer.php';
